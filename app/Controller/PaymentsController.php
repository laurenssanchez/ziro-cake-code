<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;


set_time_limit(0);

App::uses('AppController', 'Controller');
App::uses('HttpSocket', 'Network/Http');
date_default_timezone_set('America/Bogota');
/**
 * Payments Controller
 *
 * @property Payment $Payment
 * @property PaginatorComponent $Paginator
 */
class PaymentsController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('detail');
    }

    public $components = array('Paginator');

    public function index_old()
    {

        if (!isset($this->request->query["tab"])) {
            echo "entre 2";
            $this->redirect(["action" => "index", "?" => ["tab" => 1]]);
        }

        $conditions = $this->Payment->buildConditions($this->request->query);

        if ($this->request->query["tab"] == 2) {
            $conditions["Receipt.shop_commerce_id"] = null;
            echo "entre 3";
        }

        if (AuthComponent::user("role") == 5) {
            $credits = $this->Payment->CreditsPlan->Credit->find("list", ["conditions" => ["Credit.customer_id" => AuthComponent::user("customer_id"), "Credit.credits_request_id !=" => 0]]);

            if (!empty($credits)) {
                $cuotesId = $this->Payment->CreditsPlan->find("list", ["conditions" => ["CreditsPlan.credit_id" => $credits]]);
                if (!empty($cuotesId)) {
                    $conditions["Payment.credits_plan_id"] = $cuotesId;
                } else {
                    $conditions["Payment.credits_plan_id"] = 0;
                }

            } else {
                $conditions["Payment.credits_plan_id"] = 0;
            }
        } elseif (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7) {

            $this->loadModel("ShopCommerce");
            $conditions2 = ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")];

            $commerces = $this->ShopCommerce->find("list", ["fields" => ["id"], "recursive" => -1, "conditions" => $conditions2]); //$conditions2

            if (!empty($commerces)) {
                $conditions["Payment.shop_commerce_id"] = $commerces;
            } else {
                $conditions["Payment.shop_commerce_id"] = 0;
            }

        } elseif (AuthComponent::user("role") == 6) {
            $conditions["Payment.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
        }

        if (in_array(AuthComponent::user("role"), [1, 2])) {
            $totales = $this->Payment->getTotalesByCommerce(null, null, $this->request->query["tab"]);
            $this->set("totales", $totales);
            if (isset($this->request->query["commerce"])) {
                $conditions["Payment.shop_commerce_id"] = $this->decrypt($this->request->query["commerce"]);
            }
        }

        $q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
        $this->set("q", $q);
        $this->Payment->recursive = 0;
        $this->Paginator->settings = array('limit' => 100000, 'recursive' => 1, 'order' => array('Payment.modified' => 'DESC'), "fields" => ["SUM(Payment.value) as total", "Payment.credits_plan_id", "shop_commerce_id", "Payment.created as fecha", "Payment.user_id", "User.name", "ShopCommerce.name", "CreditsPlan.number", "CreditsPlan.credit_id", "ShopCommerce.shop_id", "Payment.receipt_id", "Receipt.shop_commerce_id"], "group" => ["Payment.created"]);

        if (isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer'])) {
            $this->loadModel("Credit");

            $customerCuotes = [];

            $customerCredits = $this->Credit->find("all", ["conditions" => ["Customer.identification" => $this->request->query['ccCustomer'], "Credit.credits_request_id !=" => 0], "fields" => ["Credit.customer_id"]]);

            if (!empty($customerCredits)) {

                foreach ($customerCredits as $keyCredit => $credit) {
                    if (isset($credit["CreditsPlan"])) {
                        foreach ($credit["CreditsPlan"] as $key => $value) {
                            $customerCuotes[] = $value["id"];
                        }
                    }
                }

                $conditions["Payment.credits_plan_id"] = empty($customerCuotes) ? null : $customerCuotes;

            }

            $this->Set("ccCustomer", $this->request->query['ccCustomer']);
        }

        $payments = $this->Paginator->paginate(null, $conditions);

        $paymentsData = $payments;

        $payments = [];
        $customers = [];

        if (!empty($paymentsData)) {
            foreach ($paymentsData as $key => $value) {
                $creditInfo = $this->Payment->CreditsPlan->Credit->findById($value["CreditsPlan"]["credit_id"]);
                if (is_null($creditInfo) || !isset($creditInfo["Credit"]) || $creditInfo["Credit"]["credits_request_id"] == 0) {
                    continue;
                }
                $value["CreditsPlan"]["number_credit"] = $creditInfo["Credit"]["code_pay"];
                $value["ShopCommerce"]["shop"] = $this->Payment->ShopCommerce->Shop->field("social_reason", ["id" => $value["ShopCommerce"]["shop_id"]]);
                $payments[$creditInfo["Credit"]["customer_id"]][] = $value;
                $customers[$creditInfo["Credit"]["customer_id"]] = $creditInfo["Customer"];
            }
        }

        $this->set("tab", $this->request->query["tab"]);
        $this->set(compact('payments', 'customers'));

    }

    public function index() {

        if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d",strtotime("-2 day"));
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
            $this->set("fechas",true);
		}

		if(!isset($this->request->query["tab"])){
            $num = AuthComponent::user("role") == 11 ? 3 : 1;
			$this->redirect(["action"=>"index","?" => ["tab"=>$num]]);
		}

		$conditions = $this->Payment->buildConditions($this->request->query);

		if ($this->request->query["tab"] == 2) {
			$conditions["Receipt.shop_commerce_id"] = null;
			// echo "entre 3";

		}elseif($this->request->query["tab"] == 3){
			$conditions["Payment.juridic"] = 1;
		}else if ($this->request->query["tab"] == 1){

		   $credits = $this->Payment->CreditsPlan->Credit->find("list",["conditions"=>["Credit.juridico" => 0]]);

            if(!empty($credits)){

				$cuotesId = $this->Payment->CreditsPlan->find("list",["conditions"=>["CreditsPlan.credit_id" => $credits ]]);

				if(!empty($cuotesId)){
					$conditions["Payment.credits_plan_id"] = $cuotesId;
				}else{
					$conditions["Payment.credits_plan_id"] = 0;
				}

			}else{
				$conditions["Payment.credits_plan_id"] = 0;
			}
		}

		if(AuthComponent::user("role") == 5){
			$credits = $this->Payment->CreditsPlan->Credit->find("list",["conditions"=>["Credit.customer_id" => AuthComponent::user("customer_id"),"Credit.credits_request_id !=" => 0]]);

			if(!empty($credits)){
				$cuotesId = $this->Payment->CreditsPlan->find("list",["conditions"=>["CreditsPlan.credit_id" => $credits ]]);
				if(!empty($cuotesId)){
					$conditions["Payment.credits_plan_id"] = $cuotesId;
				}else{
					$conditions["Payment.credits_plan_id"] = 0;
				}

			}else{
				$conditions["Payment.credits_plan_id"] = 0;
			}
		}elseif(AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7){


			$this->loadModel("ShopCommerce");
			$conditions2 	= ["ShopCommerce.shop_id"=>AuthComponent::user("shop_id")];

          	$commerces  	= $this->ShopCommerce->find("list",["fields"=>["id"],"recursive"=>-1, "conditions"=> $conditions2 ]); //$conditions2



          	if(!empty($commerces)){
				$conditions["Payment.shop_commerce_id"] = $commerces;
          	}else{
				$conditions["Payment.shop_commerce_id"] = 0;
			}



        }elseif(AuthComponent::user("role") == 6 ) {
        	$conditions["Payment.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
        }

		if (isset($this->request->query["commerce_code"]) and (AuthComponent::user("role") != 4 || AuthComponent::user("role") != 7)) {
			if (!empty($this->request->query["commerce_code"])) {
                $this->loadModel("ShopCommerce");
                $conditions3 	= ["ShopCommerce.code"=>$this->request->query["commerce_code"]];
                $commercesCod  = $this->ShopCommerce->find("list",["fields"=>["id"],"conditions"=> $conditions3 ]); //$conditions2
                if(!empty($commercesCod)){
                    $conditions["Payment.shop_commerce_id"] = $commercesCod;
                }
                $this->set("commerce_code",$this->request->query["commerce_code"]);
            }
		}

        if(AuthComponent::user("role") == 11 )
        {
		    $conditions["Payment.user_id"] = AuthComponent::user("id");
        }

        if (isset($this->request->query["commerce"]) && !empty($this->request->query["commerce"])) {
            $conditions["Payment.shop_commerce_id"] = $this->decrypt($this->request->query["commerce"]);
        }

        $conditions["DATE(Payment.created) >=" ] = $fechaInicioReporte;
        $conditions["DATE(Payment.created) <=" ] = $fechaFinReporte;

        if (in_array(AuthComponent::user("role"), [1,2,9])) {

            if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
                $totales = $this->Payment->getTotalesByCommerce(null,null,$this->request->query["tab"],["ini"=>$this->request->query["ini"],"end" => $this->request->query["end"]]);
            }else{
                $totales = $this->Payment->getTotalesByCommerce(null,null,$this->request->query["tab"]);
            }
            $totales  = Set::sort($totales, '{n}.0.total', 'desc');

            $this->set("totales",$totales);
        }

		$this->set(compact("fechaInicioReporte","fechaFinReporte"));
		//$this->set("fechas",false);


		$this->Payment->recursive = 0;
		$this->Paginator->settings = array('limit'=>100000,'recursive'=>1,'order'=>array('Payment.modified'=>'DESC'),"fields"=>["SUM(Payment.value) as total","Payment.credits_plan_id","shop_commerce_id","Payment.created as fecha","Payment.user_id","User.name","ShopCommerce.name","CreditsPlan.number","CreditsPlan.credit_id","ShopCommerce.shop_id","Payment.receipt_id","Receipt.shop_commerce_id,Receipt.value as Totl"],"group"=>["Payment.created"]);

        if (isset($this->request->query["credit"]) && !empty($this->request->query["credit"])) {
            $this->set("credit",$this->request->query["credit"]);
            $credit = preg_replace('/^0+/', '', $this->request->query["credit"]);
            $this->loadModel("Credit");
            $conditions["CreditsPlan.credit_id"] = $this->Credit->field("id",["credits_request_id"=>$credit]);
        }
		if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
			$this->loadModel("Credit");

			$customerCuotes = [];

			$customerCredits = $this->Credit->find("all",["conditions"=>["Customer.identification" => $this->request->query['ccCustomer'],"Credit.credits_request_id !=" => 0  ],"fields" => ["Credit.customer_id"] ]);

			if(!empty($customerCredits)){

				foreach ($customerCredits as $keyCredit => $credit) {
					if (isset($credit["CreditsPlan"])) {
						foreach ($credit["CreditsPlan"] as $key => $value) {
							$customerCuotes[] = $value["id"];
						}
					}
				}

				$conditions["Payment.credits_plan_id"] = empty($customerCuotes) ? null : $customerCuotes;

			}


			$this->Set("ccCustomer",$this->request->query['ccCustomer']);
		}

        if (AuthComponent::user("role") == 1) {
            if ( ( isset($this->request->query["commerce"]) && !empty($this->request->query["commerce"]) ) || (isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer'])) ) {
                $payments = $this->Paginator->paginate(null, $conditions);
            }else{
                $payments = $this->Paginator->paginate(null, ["Payment.id" => 0]);;
            }
        }else{
            $payments = $this->Paginator->paginate(null, $conditions);
        }



		//$payments += [ "TOTALR" => 2020];
		$paymentsData = $payments;

		$payments = [];
		$customers = [];

		if(!empty($paymentsData)){
			foreach ($paymentsData as $key => $value) {
				$creditInfo = $this->Payment->CreditsPlan->Credit->findById($value["CreditsPlan"]["credit_id"]);
				if (is_null($creditInfo) || !isset($creditInfo["Credit"]) || $creditInfo["Credit"]["credits_request_id"] == 0) {
					continue;
				}
				$value["CreditsPlan"]["number_credit"] 	= $creditInfo["Credit"]["credits_request_id"];
				$value["ShopCommerce"]["shop"] 			= $this->Payment->ShopCommerce->Shop->field("social_reason",["id" => $value["ShopCommerce"]["shop_id"]]);
				$payments[ $creditInfo["Credit"]["customer_id"] ][] = $value;
				$customers[ $creditInfo["Credit"]["customer_id"] ] = $creditInfo["Customer"];
			}
		}

		$this->set("tab",$this->request->query["tab"]);
		$this->set(compact('payments','customers'));

	}



	public function payments_reporte() {
		$this->autoRender = false;
		$this->loadModel("Payment");

		if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
			$conditions["DATE(Payment.created) >="] = $this->request->query["ini"];
			$conditions["DATE(Payment.created) <="] = $this->request->query["end"];
			$this->set("fechas", true);
		}

		if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
            $this->loadModel("Credit");
            $customerCuotes = [];
            $customerCredits = $this->Credit->find("all",[
					"conditions"=>["Customer.identification" => $this->request->query['ccCustomer'],
					"Credit.credits_request_id !=" => 0
				],
				"fields" => ["Credit.customer_id"]
			]);

            if(!empty($customerCredits)){
                foreach ($customerCredits as $keyCredit => $credit) {
                    if (isset($credit["CreditsPlan"])) {
                        foreach ($credit["CreditsPlan"] as $key => $value) {
                            $customerCuotes[] = $value["id"];
                        }
                    }
                }
                $conditions["Payment.credits_plan_id"] = empty($customerCuotes) ? null : $customerCuotes;
            }
        }


		if(isset($this->request->query['numeroObligacion']) && !empty($this->request->query['numeroObligacion']) ){
            $this->loadModel("Credit");
            $customerCredits = $this->Credit->find("all",[
					"conditions"=>["Credit.code_pay" => $this->request->query['numeroObligacion'],
					"Credit.credits_request_id !=" => 0
				],
				"fields" => ["Credit.id"]
			]);

            if(!empty($customerCredits)){
                foreach ($customerCredits as $keyCredit => $credit) {
                    if (isset($credit["CreditsPlan"])) {
                        foreach ($credit["CreditsPlan"] as $key => $value) {
                            $creditsPlansIds[] = $value["id"];
                        }
                    }
                }
                $conditions["Payment.credits_plan_id"] = empty($creditsPlansIds) ? null : $creditsPlansIds;
            }
        }


        $options["conditions"] = $conditions;
        $payments = $this->Payment->find("all",$options);
		$datos=$this->request->query;


		debug($payments);
		die();

		if (!isset($this->request->query['accion']) || $this->request->query['accion'] =='buscar') {
			$datos=$this->request->query;
			$pagos = $this->Paginator->paginate($this->Payment, $conditions);

			$this->set(compact("clientes","datos"));
		} else{
		}
		debug(1);
		die();
	}

    public function view($id = null)
    {
        $id = $this->decrypt($id);
        if (!$this->Payment->exists($id)) {
            throw new NotFoundException(__('Página no encontrada'));
        }
        $this->Payment->recursive = 0;
        $conditions = array('Payment.' . $this->Payment->primaryKey => $id);
        $this->set('payment', $this->Payment->find('first', compact('conditions')));
    }

    public function reports()
{
    if (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 6) {

        $this->loadModel("ShopCommerce");
        if (AuthComponent::user("role") == 4) {
            $commerces = $this->ShopCommerce->find("list", ["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")]]);
        } else {
            $commerces = $this->ShopCommerce->find("list", ["conditions" => ["ShopCommerce.id" => AuthComponent::user("shop_commerce_id")]]);
        }

        if (!empty($commerces)) {
            if (!empty($commerces) && !isset($this->request->query["tab"])) {
                $comercesValues = array_keys($commerces);
                $this->redirect(["action" => $reports, "?" => ["tab" => $this->encrypt($comercesValues[0])]]);
            }

            if (!isset($this->request->query["ini"])) {
                $fechaInicioReporte = date("Y-m-d");
            } else {
                $fechaInicioReporte = $this->request->query["ini"];
            }

            if (!isset($this->request->query["end"])) {
                $fechaFinReporte = date("Y-m-d");
            } else {
                $fechaFinReporte = $this->request->query["end"];
            }

            $query = $this->request->query;
            $otherQuery = " ";

            $conditions = ["Disbursement.shop_commerce_id" => $this->decrypt($query['tab'])];

            if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
                $otherQuery .= " AND DATE(payments.created) >= '" . $this->request->query["ini"] . "'";
                $otherQuery .= " AND DATE(payments.created) <= '" . $this->request->query["end"] . "'";
                $conditions["DATE(Disbursement.created) >="] = $this->request->query["ini"];
                $conditions["DATE(Disbursement.created) <="] = $this->request->query["end"];
                $this->set("fechas", true);

            }

            $commerce = $this->ShopCommerce->findById($this->decrypt($this->request->query["tab"]));

            $id = $this->decrypt($query['tab']);
            $query = "SELECT SUM(payments.value) total,customers.identification, credits.credits_request_id
            FROM payments
            INNER JOIN credits_plans ON credits_plans.id = payments.credits_plan_id
            INNER JOIN credits ON credits.id = credits_plans.credit_id
            INNER JOIN  customers ON credits.customer_id = customers.id
            WHERE credits.credits_request_id != 0 AND payments.value >= 0 and payments.shop_commerce_id = ".$this->decrypt($query['tab']).$otherQuery." GROUP BY credits.credits_request_id ORDER BY payments.created ASC";


            $this->loadModel("Disbursement");
			if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
                $datos = $this->ShopCommerce->query($query);
                $allDisbursement = $this->Disbursement->find("all", ["conditions" => $conditions]);
            }
            if (!empty($allDisbursement)) {
                $this->loadModel("Customer");
                foreach ($allDisbursement as $key => $value) {

                    $customer = $this->Customer->find('first', array(
                        'conditions' => array('Customer.id' => $value["Credit"]["customer_id"]),
                        'fields' => array('identification', 'user_id_commerce','name','last_name')
                    ));

                    if (!empty($customer)) {
                        $this->loadModel('CreditsRequest');
                        $creditRequest = $this->CreditsRequest->findById($value["Credit"]["credits_request_id"]);

                        if (!empty($creditRequest)) {
                            $allDisbursement[$key]["Credit"]["customer"] = $customer["Customer"]["identification"];
                            $allDisbursement[$key]["Credit"]["user_id_commerce"] = $customer["Customer"]["user_id_commerce"];
                            $allDisbursement[$key]["Credit"]["name"] = strtoupper($customer["Customer"]["name"]) . ' ' . strtoupper($customer["Customer"]["last_name"]);
                            $allDisbursement[$key]["Credit"]["numero_orden"] = $creditRequest["CreditsRequest"]["order_number"];
                            $allDisbursement[$key]["Credit"]["date_disbursed"] = date('Y-m-d', strtotime($creditRequest["CreditsRequest"]["date_disbursed"]));
                        }
                    }
                }
            }

            $this->set("tab", $this->request->query["tab"]);
            $this->set(compact("fechaInicioReporte", "fechaFinReporte", "datos", "commerce", "allDisbursement"));

        }

        $this->set(compact("commerces", "id"));

    } else {
        $this->redirect(["controller" => "credits_requests", "action" => "index"]);
    }
}



    public function index_excel() {
        $this->autoRender = false;
        if(!isset($this->request->query["ini"])){
            $fechaInicioReporte = date("Y-m-d",strtotime("-2 day"));
        }else{
            $fechaInicioReporte = $this->request->query["ini"];
        }

        if(!isset($this->request->query["end"])){
            $fechaFinReporte = date("Y-m-d");
        }else{
            $fechaFinReporte = $this->request->query["end"];
        }

        $conditions = $this->Payment->buildConditions($this->request->query);
        $conditions["Payment.juridic"] = 1;

        if (!empty($this->request->query["commerce"])) {
            $this->loadModel("ShopCommerce");
            $conditions3    = ["ShopCommerce.code"=>$this->request->query["commerce"]];
            $commercesCod  = $this->ShopCommerce->find("list",["fields"=>["id"],"recursive"=>-1, "conditions"=> $conditions3 ]);
            if(!empty($commercesCod)){
                $conditions["Payment.shop_commerce_id"] = $commercesCod;
            }
        }
        $conditions["Payment.user_id"] = AuthComponent::user("id");

        $conditions["DATE(Payment.created) >=" ] = $fechaInicioReporte;
        $conditions["DATE(Payment.created) <=" ] = $fechaFinReporte;

        $options =  array(
                        'limit'=>100000,
                        'recursive'=>1,
                        'order'=>array('Payment.modified'=>'DESC'),
                        "fields"=>["SUM(Payment.value) as total","Payment.credits_plan_id","shop_commerce_id","Payment.created as fecha","Payment.user_id","User.name","ShopCommerce.name","CreditsPlan.number","CreditsPlan.credit_id","ShopCommerce.shop_id","Payment.receipt_id","Receipt.shop_commerce_id,Receipt.value as Totl","Receipt.id"],
                        "group"=>["Payment.created"]
                    );

        if (isset($this->request->query["credit"]) && !empty($this->request->query["credit"])) {
            $credit = preg_replace('/^0+/', '', $this->request->query["credit"]);
            $this->loadModel("Credit");
            $conditions["CreditsPlan.credit_id"] = $this->Credit->field("id",["credits_request_id"=>$credit]);
        }
        if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
            $this->loadModel("Credit");

            $customerCuotes = [];

            $customerCredits = $this->Credit->find("all",["conditions"=>["Customer.identification" => $this->request->query['ccCustomer'],"Credit.credits_request_id !=" => 0  ],"fields" => ["Credit.customer_id"] ]);

            if(!empty($customerCredits)){

                foreach ($customerCredits as $keyCredit => $credit) {
                    if (isset($credit["CreditsPlan"])) {
                        foreach ($credit["CreditsPlan"] as $key => $value) {
                            $customerCuotes[] = $value["id"];
                        }
                    }
                }

                $conditions["Payment.credits_plan_id"] = empty($customerCuotes) ? null : $customerCuotes;

            }
        }
        $options["conditions"] = $conditions;
        $payments = $this->Payment->find("all",$options);



        if(!empty($payments)){
            foreach ($payments as $key => $value) {
                $creditInfo = $this->Payment->CreditsPlan->Credit->findById($value["CreditsPlan"]["credit_id"]);
                if (is_null($creditInfo) || !isset($creditInfo["Credit"]) || $creditInfo["Credit"]["credits_request_id"] == 0) {
                    continue;
                }
                $value["CreditsPlan"]["number_credit"]  = $creditInfo["Credit"]["code_pay"];
                $value["ShopCommerce"]["shop"]          = $this->Payment->ShopCommerce->Shop->field("social_reason",["id" => $value["ShopCommerce"]["shop_id"]]);
                $value["Customer"] = $creditInfo["Customer"];
                $payments[$key] = $value;
            }
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        $spreadsheet->getProperties()->setCreator('CREDISHOP')
            ->setLastModifiedBy('CREDISHOP')
            ->setTitle('RECAUDOS JURIDICO')
            ->setSubject('RECAUDOS JURIDICO')
            ->setDescription('RECAUDOS JURIDICO ZÍRO')
            ->setKeywords('RECAUDOS JURIDICO')
            ->setCategory('RECAUDOS JURIDICO');

        $spreadsheet->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Número de obligación')
            ->setCellValue('B1', 'CC Cliente')
            ->setCellValue('C1', 'Nombre Cliente')
            ->setCellValue('D1', 'Recaudado')
            ->setCellValue('E1', 'Fecha')
            ->setCellValue('F1', 'Recibo');

        $i = 2;

        foreach ($payments as $key => $value) {
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["CreditsPlan"]["number_credit"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Customer"]["identification"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["Customer"]["name"]." ".$value["Customer"]["last_name"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["0"]["total"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["Payment"]["fecha"]);
            $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["Payment"]["receipt_id"]);
            $i++;
        }

        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

        $spreadsheet->getActiveSheet()->setTitle('Recaudos Júridico');
        $spreadsheet->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $name = "files/recaudos_juridicos_" . time() . ".xlsx";
        $writer->save($name);

        $url = Router::url("/", true) . $name;
        $this->redirect($url);

    }

    public function reports_export()
    {
        $this->autoRender = false;
        switch (AuthComponent::user("role")) {
            case 6:

                $this->loadModel("ShopCommerce");

                $commerces = $this->ShopCommerce->find("list", ["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")]]);

                if (!empty($commerces) && !isset($this->request->query["tab"])) {
                    $comercesValues = array_keys($commerces);
                    $this->redirect(["action" => $reports, "?" => ["tab" => $this->encrypt($comercesValues[0])]]);
                }

                if (!isset($this->request->query["ini"])) {
                    $fechaInicioReporte = date("Y-m-d");
                } else {
                    $fechaInicioReporte = $this->request->query["ini"];
                }

                if (!isset($this->request->query["end"])) {
                    $fechaFinReporte = date("Y-m-d");
                } else {
                    $fechaFinReporte = $this->request->query["end"];
                }

                $query = $this->request->query;
                $conditions = ["Disbursement.shop_commerce_id" => $this->decrypt($query['tab'])];
                $otherQuery = " ";

                if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
                    $otherQuery .= " AND DATE(payments.created) >= '" . $this->request->query["ini"] . "'";
                    $otherQuery .= " AND DATE(payments.created) <= '" . $this->request->query["end"] . "'";
                    $conditions["DATE(Disbursement.created) >="] = $this->request->query["ini"];
                    $conditions["DATE(Disbursement.created) <="] = $this->request->query["end"];
                    $this->set("fechas", true);
                }

                $commerce = $this->ShopCommerce->findById($this->decrypt($this->request->query["tab"]));

                $query = "SELECT SUM(payments.value) total,customers.identification, credits.credits_request_id,payments.created
                FROM payments
                INNER JOIN credits_plans ON credits_plans.id = payments.credits_plan_id
                INNER JOIN credits ON credits.id = credits_plans.credit_id
                INNER JOIN  customers ON credits.customer_id = customers.id
                WHERE credits.credits_request_id != 0 and payments.shop_commerce_id = ".$this->decrypt($query['tab']).$otherQuery." GROUP BY credits.credits_request_id ORDER BY payments.created ASC";

                $datos = $this->ShopCommerce->query($query);

                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                $spreadsheet->getProperties()->setCreator('CREDISHOP')
                    ->setLastModifiedBy('CREDISHOP')
                    ->setTitle('RECAUDOS')
                    ->setSubject('RECAUDOS')
                    ->setDescription('RECAUDOS ZÍRO')
                    ->setKeywords('RECAUDOS')
                    ->setCategory('RECAUDOS');

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $commerce["ShopCommerce"]["name"])
                    ->setCellValue('B1', $commerce["ShopCommerce"]["address"])
                    ->setCellValue('C1', $commerce["ShopCommerce"]["phone"]);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'Número de obligación')
                    ->setCellValue('B3', 'CC Cliente')
                    ->setCellValue('C3', 'Recaudado')
                    ->setCellValue('D3', 'Fecha')
                    ->setCellValue('G3', 'Número de obligación')
                    ->setCellValue('H3', 'CC Cliente')
                    ->setCellValue('I3', 'Desembolso')
                    ->setCellValue('J3', 'Fecha');

                $total = 0;
                if (!empty($datos)) {
                    $i = 4;
                    foreach ($datos as $key => $value) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["credits"]["code_pay"]);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["customers"]["identification"]);
                        $total += $value["0"]["total"];
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["0"]["total"]);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["payments"]["created"]);
                        $i++;
                    }

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, '')
                        ->setCellValue('B' . $i, 'Total')
                        ->setCellValue('C' . $i, $total);

                }

                $this->loadModel("Disbursement");

                $allDisbursement = $this->Disbursement->find("all", ["conditions" => $conditions]);


                if (!empty($allDisbursement)) {
                    $i = 4;
                    $total2 = 0;
                    $this->loadModel("Customer");
                    foreach ($allDisbursement as $key => $value) {
                        if ($value["Credit"]["credits_request_id"] == 0) {
                            continue;
                        }
                        $identificacion = $this->Customer->field("identification", ["id" => $value["Credit"]["customer_id"]]);

                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["Credit"]["code_pay"]);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $identificacion);
                        $total2 += $value["Disbursement"]["value"];
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["Disbursement"]["value"]);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Disbursement"]["created"]);
                        $i++;
                    }
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('G' . $i, '')
                        ->setCellValue('H' . $i, 'Total')
                        ->setCellValue('I' . $i, $total2);
                }

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

                $spreadsheet->getActiveSheet()->setTitle('Recaudos');
                $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);

                $writer = IOFactory::createWriter($spreadsheet, 'Xls');
                $name = "files/recaudos_ventas_" . time() . ".xls";
                $writer->save($name);

                echo Router::url("/", true) . $name;

                break;

            case 4:

                $this->loadModel("ShopCommerce");

                $commerces = $this->ShopCommerce->find("list", ["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")]]);

                if (!empty($commerces) && !isset($this->request->query["tab"])) {
                    $comercesValues = array_keys($commerces);
                    $this->redirect(["action" => $reports, "?" => ["tab" => $this->encrypt($comercesValues[0])]]);
                }

                if (!isset($this->request->query["ini"])) {
                    $fechaInicioReporte = date("Y-m-d");
                } else {
                    $fechaInicioReporte = $this->request->query["ini"];
                }

                if (!isset($this->request->query["end"])) {
                    $fechaFinReporte = date("Y-m-d");
                } else {
                    $fechaFinReporte = $this->request->query["end"];
                }

                $query = $this->request->query;
                $conditions = ["Disbursement.shop_commerce_id" => $this->decrypt($query['tab'])];
                $otherQuery = " ";

                if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
                    $otherQuery .= " AND DATE(payments.created) >= '" . $this->request->query["ini"] . "'";
                    $otherQuery .= " AND DATE(payments.created) <= '" . $this->request->query["end"] . "'";
                    $conditions["DATE(Disbursement.created) >="] = $this->request->query["ini"];
                    $conditions["DATE(Disbursement.created) <="] = $this->request->query["end"];
                    $this->set("fechas", true);
                }

                $commerce = $this->ShopCommerce->findById($this->decrypt($this->request->query["tab"]));

                $query = "SELECT SUM(VALUE) total,customers.identification, credits.credits_request_id,payments.created
                FROM payments
                INNER JOIN credits_plans ON credits_plans.id = payments.credits_plan_id
                INNER JOIN credits ON credits.id = credits_plans.credit_id
                INNER JOIN	customers ON credits.customer_id = customers.id
                WHERE credits.credits_request_id != 0 and payments.shop_commerce_id = " . $commerce['ShopCommerce']['id'] . $otherQuery . "	GROUP BY credits.credits_request_id";

                $datos = $this->ShopCommerce->query($query);

                $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

                $spreadsheet->getProperties()->setCreator('CREDISHOP')
                    ->setLastModifiedBy('CREDISHOP')
                    ->setTitle('RECAUDOS')
                    ->setSubject('RECAUDOS')
                    ->setDescription('RECAUDOS ZÍRO')
                    ->setKeywords('RECAUDOS')
                    ->setCategory('RECAUDOS');

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A1', $commerce["ShopCommerce"]["name"])
                    ->setCellValue('B1', $commerce["ShopCommerce"]["address"])
                    ->setCellValue('C1', $commerce["ShopCommerce"]["phone"]);

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A3', 'Número de obligación')
                    ->setCellValue('B3', 'CC Cliente')
                    ->setCellValue('C3', 'Recaudado')
                    ->setCellValue('D3', 'Fecha')
                    ->setCellValue('G3', 'Número de obligación')
                    ->setCellValue('H3', 'CC Cliente')
                    ->setCellValue('I3', 'Desembolso')
                    ->setCellValue('J3', 'Fecha');

                $total = 0;
                if (!empty($datos)) {
                    $i = 4;
                    foreach ($datos as $key => $value) {
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i,$value["credits"]["code_pay"]);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["customers"]["identification"]);
                        $total += $value["0"]["total"];
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["0"]["total"]);
                        $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["payments"]["created"]);
                        $i++;
                    }

                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A' . $i, '')
                        ->setCellValue('B' . $i, 'Total')
                        ->setCellValue('C' . $i, $total);

                }

                $this->loadModel("Disbursement");

                $allDisbursement = $this->Disbursement->find("all", ["conditions" => $conditions]);

                if (!empty($allDisbursement)) {
                    $i = 4;
                    $total = 0;
                    $this->loadModel("Customer");
                    foreach ($allDisbursement as $key => $value) {
                        if ($value["Credit"]["credits_request_id"] != 0) {

							$identificacion = $this->Customer->field("identification", ["id" => $value["Credit"]["customer_id"]]);

							$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["Credit"]["code_pay"]);
							$spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $identificacion);
							$total += $value["Credit"]["value_request"];
							$spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["Credit"]["value_request"]);
							$spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Credit"]["created"]);
							$i++;
						}
                    }
                    $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('G' . $i, '')
                        ->setCellValue('H' . $i, 'Total')
                        ->setCellValue('I' . $i, $total);
                }

                $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
                $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

                $spreadsheet->getActiveSheet()->setTitle('Recaudos');
                $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
                $spreadsheet->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);

                $writer = IOFactory::createWriter($spreadsheet, 'Xls');
                $name = "files/recaudos_ventas_" . time() . ".xls";
                $writer->save($name);

                echo Router::url("/", true) . $name;

                break;

            default:

                //$this->redirect(["controller" => "credits_requests", "action" => "index"]);

                break;
        }
        /*if (AuthComponent::user("role") == 4) {

            $this->loadModel("ShopCommerce");

            $commerces = $this->ShopCommerce->find("list", ["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")]]);

            if (!empty($commerces) && !isset($this->request->query["tab"])) {
                $comercesValues = array_keys($commerces);
                $this->redirect(["action" => $reports, "?" => ["tab" => $this->encrypt($comercesValues[0])]]);
            }

            if (!isset($this->request->query["ini"])) {
                $fechaInicioReporte = date("Y-m-d");
            } else {
                $fechaInicioReporte = $this->request->query["ini"];
            }

            if (!isset($this->request->query["end"])) {
                $fechaFinReporte = date("Y-m-d");
            } else {
                $fechaFinReporte = $this->request->query["end"];
            }

            $query = $this->request->query;
            $conditions = ["Disbursement.shop_commerce_id" => $this->decrypt($query['tab'])];
            $otherQuery = " ";

            if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
                $otherQuery .= " AND DATE(payments.created) >= '" . $this->request->query["ini"] . "'";
                $otherQuery .= " AND DATE(payments.created) <= '" . $this->request->query["end"] . "'";
                $conditions["Disbursement.created >="] = $this->request->query["ini"];
                $conditions["Disbursement.created <="] = $this->request->query["end"];
                $this->set("fechas", true);
            }

            $commerce = $this->ShopCommerce->findById($this->decrypt($this->request->query["tab"]));

            $query = "SELECT SUM(VALUE) total,customers.identification, credits.credits_request_id,credits.created
			FROM payments
			INNER JOIN credits_plans ON credits_plans.id = payments.credits_plan_id
			INNER JOIN credits ON credits.id = credits_plans.credit_id
			INNER JOIN	customers ON credits.customer_id = customers.id
			WHERE credits.credits_request_id != 0 and payments.shop_commerce_id = " . $this->decrypt($query['tab']) . $otherQuery . "	GROUP BY credits.credits_request_id";

            $datos = $this->ShopCommerce->query($query);

            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

            $spreadsheet->getProperties()->setCreator('CREDISHOP')
                ->setLastModifiedBy('CREDISHOP')
                ->setTitle('RECAUDOS')
                ->setSubject('RECAUDOS')
                ->setDescription('RECAUDOS Credishop')
                ->setKeywords('RECAUDOS')
                ->setCategory('RECAUDOS');

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1', $commerce["ShopCommerce"]["name"])
                ->setCellValue('B1', $commerce["ShopCommerce"]["address"])
                ->setCellValue('C1', $commerce["ShopCommerce"]["phone"]);

            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A3', 'Número de obligación')
                ->setCellValue('B3', 'CC Cliente')
                ->setCellValue('C3', 'Recaudado')
                ->setCellValue('D3', 'Fecha')
                ->setCellValue('G3', 'Número de obligación')
                ->setCellValue('H3', 'CC Cliente')
                ->setCellValue('I3', 'Desembolso')
                ->setCellValue('J3', 'Fecha');

            $total = 0;
            if (!empty($datos)) {
                $i = 4;
                foreach ($datos as $key => $value) {
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, str_pad($value["credits"]["credits_request_id"], 6, "0", STR_PAD_LEFT));
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["customers"]["identification"]);
                    $total += $value["0"]["total"];
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["0"]["total"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["credits"]["created"]);
                    $i++;
                }

                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, '')
                    ->setCellValue('B' . $i, 'Total')
                    ->setCellValue('C' . $i, $total);

            }

            $this->loadModel("Disbursement");

            $allDisbursement = $this->Disbursement->find("all", ["conditions" => $conditions]);

            if (!empty($allDisbursement)) {
                $i = 4;
                $total = 0;
                $this->loadModel("Customer");
                foreach ($allDisbursement as $key => $value) {
                    if ($value["Credit"]["credits_request_id"] == 0) {
                        continue;
                    }
                    $identificacion = $this->Customer->field("identification", ["id" => $value["Credit"]["customer_id"]]);

                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["Credit"]["code_pay"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $identificacion);
                    $total += $value["Disbursement"]["value"];
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["Disbursement"]["value"]);
                    $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Disbursement"]["created"]);
                    $i++;
                }
                $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('G' . $i, '')
                    ->setCellValue('H' . $i, 'Total')
                    ->setCellValue('I' . $i, $total);
            }

            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

            $spreadsheet->getActiveSheet()->setTitle('Recaudos');
            $spreadsheet->getActiveSheet()->getStyle('A1:C1')->getFont()->setBold(true);
            $spreadsheet->getActiveSheet()->getStyle('A3:J3')->getFont()->setBold(true);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $name = "files/recaudos_ventas_" . time() . ".xlsx";
            $writer->save($name);

            echo Router::url("/", true) . $name;

        } else {
            $this->redirect(["controller" => "credits_requests", "action" => "index"]);
        }*/
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->Payment->create();
            if ($this->Payment->save($this->request->data)) {
                $this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
            }
        }
        $creditsPlans = $this->Payment->CreditsPlan->find('list');
        $users = $this->Payment->User->find('list');
        $shopCommerces = $this->Payment->ShopCommerce->find('list');
        $this->set(compact('creditsPlans', 'users', 'shopCommerces'));
    }

    public function edit($id = null)
    {
        $id = $this->decrypt($id);
        $this->Payment->id = $id;
        if (!$this->Payment->exists($id)) {
            throw new NotFoundException(__('Página no encontrada'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Payment->save($this->request->data)) {
                $this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
            }
        } else {
            $conditions = array('Payment.' . $this->Payment->primaryKey => $id);
            $this->request->data = $this->Payment->find('first', compact('conditions'));
        }
        $creditsPlans = $this->Payment->CreditsPlan->find('list');
        $users = $this->Payment->User->find('list');
        $shopCommerces = $this->Payment->ShopCommerce->find('list');
        $this->set(compact('creditsPlans', 'users', 'shopCommerces'));
    }

    public function detail($receipt_id, $return = null)
    {

        if (is_null($return)) {
            $this->layout = false;
        }else{
            $this->autoRender = false;
        }

        $this->loadModel("Receipt");
        $this->Receipt->recursive = 2;
        $receipt = $this->Receipt->findById($this->decrypt($receipt_id));

        if (!empty($receipt)) {
            $payment = end($receipt["Payment"]);

            $credit = $this->Payment->CreditsPlan->Credit->find("first", ["recursive" => 2, "conditions" => ["Credit.id" => $this->Payment->CreditsPlan->field("credit_id", ["id" => $payment["credits_plan_id"]])]]);

            $totalCredit = $this->Payment->CreditsPlan->getCreditDeuda($credit["Credit"]["id"],null,null,true);

            $saldoCliente = $this->totalQuote(true, $credit["Credit"]["customer_id"]);

            $payments = $receipt["Payment"];

            if (!empty($payments)) {
                $this->Payment->recursive = 2;
                $dataPayment = $this->Payment->findById($payment["id"]);
				$this->loadModel("Shop");
				$shop=$this->Shop->findById($dataPayment["ShopCommerce"]['shop_id']);
                $this->set(compact("dataPayment","shop"));
            }
            if (!is_null($return)) {
                return json_encode(compact("payments", "credit", "fecha", "receipt", "totalCredit", "saldoCliente","dataPayment"));
            }


            $this->set(compact("payments", "credit", "fecha", "receipt", "totalCredit", "saldoCliente"));

        }
    }

    public function return_payments($receipt)
    {
        $this->autoRender = false;

        $payments = $this->Payment->findAllByReceiptId($this->decrypt($receipt));

        $this->loadModel("Repayment");
        $this->loadModel("CreditsPlan");
        $this->loadModel("Credit");
        $this->loadModel("Receipt");

        foreach ($payments as $key => $value) {

            $creditsPlanData = $this->Payment->CreditsPlan->findById($value["Payment"]["credits_plan_id"]);

            if ($value["Payment"]["type"] == 3) {
				$creditsPlanData["CreditsPlan"]["others_payment"] -= $creditsPlanData["CreditsPlan"]["others_payment"];
            }

            if ($value["Payment"]["type"] == 2) {
				$creditsPlanData["CreditsPlan"]["interest_payment"] -= $creditsPlanData["CreditsPlan"]["interest_payment"];
            }

            if ($value["Payment"]["type"] == 1) {

				$creditsPlanData["CreditsPlan"]["capital_payment"] -= $value["Payment"]["value"];
                $this->Credit->recursive = -1;
                $credit = $this->Credit->findById($value["CreditsPlan"]["credit_id"]);
                $credit["Credit"]["state"] = 0;
                $credit["Credit"]["last_payment_date"] = null;


                $customer_id = $this->Credit->field("customer_id", ["id" => $value["CreditsPlan"]["credit_id"]]);
				$paymentId   =  $value["Payment"]["id"];

                $creditsPlanData["CreditsPlan"]["date_debt"] = null;
                $creditsPlanData["CreditsPlan"]["capital_returned"] += $value["Payment"]["value"];
                $creditsPlanData["CreditsPlan"]["date_payment"] = null;
                $creditsPlanData["CreditsPlan"]["modified"] = date("Y-m-d H:i:s");
                $creditsPlanData["CreditsPlan"]["others_payment"] = null;
                $creditsPlanData["CreditsPlan"]["interest_payment"] = null;

                if (($creditsPlanData["CreditsPlan"]["capital_returned"] + 1000) >= $creditsPlanData["CreditsPlan"]["capital_value"] || $value["Payment"]["value"] == $creditsPlanData["CreditsPlan"]["capital_value"] || $creditsPlanData["CreditsPlan"]["state"] == 1 ) {
                    $credit["Credit"]["value_pending"] += $value["Payment"]["value"];
                    $creditsPlanData["CreditsPlan"]["capital_returned"] = 0;
                }

                $creditsPlanData["CreditsPlan"]["state"] = 0;

                $this->CreditsPlan->save($creditsPlanData["CreditsPlan"]);
                $this->Credit->save($credit);

                $this->Credit->query("delete from  credit_limits where  credit_limits.payment_id= " . $paymentId);

            }

            $this->Repayment->create();
            $this->Repayment->save($value["Payment"]);
            $this->Payment->delete($value["Payment"]["id"]);

        }

        $this->Receipt->delete($this->decrypt($receipt));

        $url        = Configure::read("URL_CREDIVENTAS");
        try {
            $HttpSocket = new HttpSocket(['ssl_allow_self_signed' => false, 'ssl_verify_peer' => false, 'ssl_verify_host' =>false ]);
            $results = $HttpSocket->post("${url}/payments/return_payments/".$receipt, []);
            $this->log($results->body(), "debug");

        } catch (Exception $e) {
            $this->log($e->getMessage(), "debug");
        }

        $this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
    }

    public function set_data()
    {
        $this->autoRender = false;
        $this->Payment->setReceipts();
    }

    public function pendings()
    {
        $conditions = ["Payment.state_credishop" => 0, "Payment.value > 0","Payment.juridic"=>0];

        if (AuthComponent::user("role") == 5) {
            $credits = $this->Payment->Credit->find("list", ["conditions" => ["Credit.customer_id" => AuthComponent::user("customer_id")]]);

            if (!empty($credits)) {
                $cuotesId = $this->Payment->Credit->CreditsPlan->find("list", ["conditions" => ["CreditsPlan.credit_id" => $credits]]);
                if (!empty($cuotesId)) {
                    $conditions["Payment.credits_plan_id"] = $cuotesId;
                } else {
                    $conditions["Payment.credits_plan_id"] = 0;
                }

            } else {
                $conditions["Payment.credits_plan_id"] = 0;
            }
        } elseif (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7) {

            $this->loadModel("ShopCommerce");
            $conditions2 = ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")];
            $commerces = $this->ShopCommerce->find("list", ["fields" => ["id"], "recursive" => -1, "conditions" => $conditions2]);

            if (!empty($commerces)) {
                $conditions["Payment.shop_commerce_id"] = $commerces;
            } else {
                $conditions["Payment.shop_commerce_id"] = 0;
            }
        } elseif (AuthComponent::user("role") == 6) {
            $conditions["Payment.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
        }

        $q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
        $this->set("q", $q);
        $this->Payment->recursive = 0;

        if (in_array(AuthComponent::user("role"), [1, 2])) {

            if(!isset($this->request->query["ini"])){
                $fechaInicioReporte = date("Y-m-d",strtotime("-30 day"));
            }else{
                $fechaInicioReporte = $this->request->query["ini"];
            }

            if(!isset($this->request->query["end"])){
                $fechaFinReporte = date("Y-m-d");
            }else{
                $fechaFinReporte = $this->request->query["end"];
            }

            $conditions["DATE(Payment.created) >="] = $fechaInicioReporte;
            $conditions["DATE(Payment.created) <="] = $fechaFinReporte;

            if(isset($this->request->query["ini"]) && isset($this->request->query["end"])){
                $this->set("fechas",true);
            }

            $totales = $this->Payment->getTotalesByCommerce(null, true,null,["ini"=>$fechaInicioReporte,"end"=>$fechaFinReporte]);
            $this->set("totales", $totales);
            if (isset($this->request->query["commerce"])) {
                $conditions["Payment.shop_commerce_id"] = $this->decrypt($this->request->query["commerce"]);
                $this->loadModel("Receipt");
                $this->Paginator->settings = [
                    "joins" => [
                        ['table' => 'payments','alias' => 'Payment','type' => 'INNER','conditions' => array('Receipt.id = Payment.receipt_id')],
                    ],
                    "conditions" => $conditions,
                    "recursive" => 1,
                    "order" => ['Payment.modified' => 'DESC'],
                    "limit" => 20,
                    "group" => ["Payment.receipt_id"]
                ];
                $payments = $this->Paginator->paginate("Receipt");
                $this->set("commerceData",$this->request->query["commerce"]);
            }else{
                $payments = [];
            }

            $this->set(compact("fechaInicioReporte", "fechaFinReporte"));
        } else {
            $this->Paginator->settings = array('recursive' => 1, 'order' => array('Payment.modified' => 'DESC'));
            $payments = $this->Paginator->paginate(null, $conditions);
        }

        if (!empty($payments)) {
            foreach ($payments as $key => $value) {
                $payments[$key]["CreditsPlan"]["number_credit"] = $this->Payment->CreditsPlan->Credit->field("code_pay", ["id" => $value["CreditsPlan"]["credit_id"]]);

                $payments[$key]["ShopCommerce"]["shop"] = $this->Payment->ShopCommerce->Shop->field("social_reason", ["id" => $value["ShopCommerce"]["shop_id"]]);
            }
        }

        $datosPayment = $payments;

        $payments = [];

        foreach ($datosPayment as $key => $value) {
            $payments[$value["Receipt"]["id"]] = $value;
        }

        $this->set(compact('payments'));
    }

    public function payment_actual()
    {
		//$this->Session->setFlash(__('entro'), 'flash_success');
        //$data = "hola";
		//$this->set(compact('data'));

        $this->autoRender = false;
        $dateUnique = time();

        $this->Payment->updateAll(
            ["Payment.state_credishop" => 1, "Payment.date_credishop" => $dateUnique],
            ["Payment.state_credishop" => 0, "Payment.id" => $this->request->data["ids"]]
        );

        $this->Session->setFlash(__('Pagos aplicados correctamente'), 'flash_success');
        // $this->redirect(["action"=>"payments_receipt"]);*/

    }

    public function payments_receipt()
    {
        $conditions = ["Payment.state_credishop" => 1, "Payment.value > 0"];

        if (AuthComponent::user("role") == 5) {
            $credits = $this->Payment->Credit->find("list", ["conditions" => ["Credit.customer_id" => AuthComponent::user("customer_id")]]);

            if (!empty($credits)) {
                $cuotesId = $this->Payment->Credit->CreditsPlan->find("list", ["conditions" => ["CreditsPlan.credit_id" => $credits]]);
                if (!empty($cuotesId)) {
                    $conditions["Payment.credits_plan_id"] = $cuotesId;
                } else {
                    $conditions["Payment.credits_plan_id"] = 0;
                }

            } else {
                $conditions["Payment.credits_plan_id"] = 0;
            }
        } elseif (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7) {

            $this->loadModel("ShopCommerce");
            $conditions2 = ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")];
            $commerces = $this->ShopCommerce->find("list", ["fields" => ["id"], "recursive" => -1, "conditions" => $conditions2]);

            if (!empty($commerces)) {
                $conditions["Payment.shop_commerce_id"] = $commerces;
            } else {
                $conditions["Payment.shop_commerce_id"] = 0;
            }
        } elseif (AuthComponent::user("role") == 6) {
            $conditions["Payment.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
        }

        $q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
        $this->set("q", $q);
        $this->Payment->recursive = 0;
        $this->Paginator->settings = array('recursive' => 1, 'order' => array('Payment.modified' => 'DESC'));

        $payments = $this->Paginator->paginate(null, $conditions);

        if (!empty($payments)) {
            foreach ($payments as $key => $value) {
                $payments[$key]["CreditsPlan"]["number_credit"] = $this->Payment->CreditsPlan->Credit->field("code_pay", ["id" => $value["CreditsPlan"]["credit_id"]]);

                $payments[$key]["ShopCommerce"]["shop"] = $this->Payment->ShopCommerce->Shop->field("social_reason", ["id" => $value["ShopCommerce"]["shop_id"]]);
            }
        }

        $dataPayment = empty($payments) ? null : end($payments);

        $this->set(compact('payments', 'dataPayment'));
    }

    public function reports_export_download($name){

        $file = Router::url("/", true) . 'files/' . $name;

        header('Content-type: application/octet-stream');
        header("Content-Type: ".mime_content_type($file));
        header("Content-Disposition: attachment; filename=".$name);
        while (ob_get_level()) {
            ob_end_clean();
        }
        readfile($file);
    }
}
