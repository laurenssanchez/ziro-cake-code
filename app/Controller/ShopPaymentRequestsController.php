<?php

require_once '../Vendor/spreadsheet/vendor/autoload.php';

use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

App::uses('AppController', 'Controller');

/**
 * ShopPaymentRequests Controller
 *
 * @property ShopPaymentRequest $ShopPaymentRequest
 * @property PaginatorComponent $Paginator
 */
class ShopPaymentRequestsController extends AppController
{

	/**
	 * Components
	 *
	 * @var array
	 */
	public $components = array('Paginator');

	public function index1() {

		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);

		$query = $this->request->query;

		if(in_array(AuthComponent::user("role"), [4])){

			$saldos = $this->validateShopCom(true);
			$this->set("saldos",$saldos);
			//$conditions["ShopPaymentRequest.shop_id"] = AuthComponent::user("shop_id");
			if (AuthComponent::user("role") == 4) {
				$conditions["ShopPaymentRequest.shop_commerce_id"] = $this->getCommercesByShop(null, true);
			} else {
				$conditions["ShopPaymentRequest.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
			}
		}


		if(isset($query["state"]) && $query["state"] != "" && in_array($query["state"], [0,1,2])){
			$conditions["ShopPaymentRequest.state"] = $query["state"];
			$this->set("estados",$query["state"]);
		}else{
			$this->set("estados","");
		 	//$conditions["ShopPaymentRequest.state"] = 3;
			//$this->set("estados",3);
		}

		if(isset($query["commerce"]) && $query["commerce"] != "" ){
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->set("commerce",$query["commerce"]);
		}else{
			$this->set("commerce","");
		}

	   if(isset($query["request_date"]) && $query["request_date"] != "" ){
			$conditions["DATE(ShopPaymentRequest.request_date)"] >= $query["request_date"];
			$this->set("request_date",$query["request_date"]);
		}else{
			$this->set("request_date","");
		//	$conditions["DATE(ShopPaymentRequest.request_date)"] = date('Y-m-d', strtotime('12 day'));//$query["request_date"];
		//	$this->set("request_date",$query["request_date"]);
		}

		if(isset($query["final_date"]) && $query["final_date"] != "" ){
			$conditions["DATE(ShopPaymentRequest.final_date)"] <= $query["final_date"];
			$this->set("final_date",$query["final_date"]);
		}else{
			$this->set("final_date","");
		}

		if(isset($query["customer"]) && $query["customer"] != "" ){
			$customers 	  = $this->ShopPaymentRequest->Disbursement->Credit->Customer->find("list",["fields" => ["id","id"], "conditions" => ["Customer.identification" => $query["customer"]] ]);

			if(!empty($customers)){
				$disbursments = $this->ShopPaymentRequest->Disbursement->find("all",["fields" => ["Disbursement.shop_payment_request_id"], "conditions" => ["Credit.customer_id" => $customers] ]);
				if(!empty($disbursments)){
					$conditions["ShopPaymentRequest.id"] = Set::extract($disbursments,"{n}.Disbursement.shop_payment_request_id");
				}else{
					$conditions["ShopPaymentRequest.id"] = null;
				}
			}else{
				$conditions["ShopPaymentRequest.id"] = null;
			}

			$this->set("customer",$query["customer"]);
		}else{
			$this->set("customer","");
		}

		$this->ShopPaymentRequest->recursive = 2;

		if(AuthComponent::user("role") == 4){
			$this->Paginator->settings 			 = array('order'=>array('ShopPaymentRequest.modified'=>'DESC'),'limit' => 20,'maxLimit' => 40);
			//$conditions["1"] = "1";
			$shopPaymentRequests 				 = $this->Paginator->paginate(null, $conditions);
		}else{
			//WHERE ShopCommerce.shop_id = ".AuthComponent::user("shop_id")." AND ".$conditions["1"]."
			//$shopPaymentRequests 				 = $this->ShopPaymentRequest->find("all",$config);
            $shopPaymentRequests                 = $this->ShopPaymentRequest->query("SELECT  ShopPaymentRequest.*,ShopCommerce.id,ShopCommerce.name,ShopCommerce.address,ShopCommerce.phone,ShopCommerce.state,ShopCommerce.shop_id,ShopCommerce.code,WEEK(ShopPaymentRequest.request_date,1) as semana FROM shop_payment_requests ShopPaymentRequest LEFT JOIN shop_commerces ShopCommerce ON ShopCommerce.id = ShopPaymentRequest.shop_commerce_id  WHERE  1=1 ; ORDER BY ShopPaymentRequest.modified asc");
			$allRequest	 						 = $shopPaymentRequests;
			$shopPaymentRequests 				 = [];

			foreach ($allRequest as $key => $value) {

				if( in_array(AuthComponent::user("role"), [1,2]) ){
					$disbursments =  $this->ShopPaymentRequest->Disbursement->find("all",["fields" => ["Credit.customer_id"], "conditions" => ["Disbursement.shop_payment_request_id" => $value["ShopPaymentRequest"]["id"],"Disbursement.shop_commerce_id" => $value["ShopCommerce"]["id"]] ]);

					if(!empty($disbursments)){
						$customersIDs = Set::extract($disbursments, "{n}.Credit.customer_id");
						$customers 	  = $this->ShopPaymentRequest->Disbursement->Credit->Customer->find("list",["fields" => ["id","identification"], "conditions" => ["Customer.id" => $customersIDs] ]);
						$value["ShopPaymentRequest"]["customers"] = $customers;
					}else{
						$value["ShopPaymentRequest"]["customers"] = [];
					}
				}

				if ($value["ShopPaymentRequest"]["payment_type"] == 2) {
					$shopPaymentRequests[$value["ShopPaymentRequest"]["payment_type"]][$value["0"]["semana"]." - ".date("Y",strtotime($value["ShopPaymentRequest"]["request_date"]))][] = $value;
				}else{
					$shopPaymentRequests[$value["ShopPaymentRequest"]["payment_type"]][] = $value;
				}
			}

		}

		$prueba = $this->request->query;
		$this->set(compact('shopPaymentRequests','prueba'));
	}

	public function informe() {
		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);
		$query 		= $this->request->query;

		if (isset($query["state"]) && $query["state"] != "" && in_array($query["state"], [0, 1, 2])) {
			$conditions["ShopPaymentRequest.state"] = $query["state"];
			$this->set("estados", $query["state"]);
		} else {
			$this->set("estados", "");

		}

		if (isset($query["commerce"]) && $query["commerce"] != "") {
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->set("commerce", $query["commerce"]);
		} else {
			$this->set("commerce", "");
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

        if (isset($query["type_date"]) && $query["type_date"] != "") {
			$this->set("type_date", $query["type_date"]);
			$campo = $query["type_date"] == 1 ? "request_date" : "final_date";
		} else {
			$this->set("type_date", "1");
		}

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {

        	if (!isset($campo) || is_null($campo)) {
        		$campo = "request_date";
        	}

            $conditions["DATE(ShopPaymentRequest.${campo}) >="] = $this->request->query["ini"];
            $conditions["DATE(ShopPaymentRequest.${campo}) <="] = $this->request->query["end"];
            $this->set("request_date", $query["ini"]);
            $this->set("request_date_end", $query["end"]);
        }

		if (isset($query["type"]) && $query["type"] != "") {
			$conditions["ShopPaymentRequest.payment_type"] = $query["type"];
			$this->set("type", $query["type"]);
		} else {
			$this->set("type", "");
		}

		if (isset($query["final_date"]) && $query["final_date"] != "") {
			$conditions["DATE(ShopPaymentRequest.final_date)"] = $query["final_date"];
			$this->set("final_date", $query["final_date"]);
		} else {
			$this->set("final_date", "");
		}

        if (isset($this->request->query["ini2"]) && isset($this->request->query["end2"])) {
            $conditions["DATE(ShopPaymentRequest.final_date) >="] = $this->request->query["ini2"];
            $conditions["DATE(ShopPaymentRequest.final_date) <="] = $this->request->query["end2"];
            $this->set("final_date", $query["ini2"]);
            $this->set("final_date_end", $query["end2"]);
        }

		$this->paginate 		= array(
			"recursive"			=> -1,
	        'limit' 			=> 20,
	        "joins" => [
	        	['table' => 'shop_commerces','alias' => 'ShopCommerce','type' => 'LEFT','conditions' => array('ShopCommerce.id = ShopPaymentRequest.shop_commerce_id')],
	        	['table' => 'shops','alias' => 'Shop','type' => 'LEFT','conditions' => array('ShopCommerce.shop_id = Shop.id')],
	        	['table' => 'users','alias' => 'User','type' => 'LEFT','conditions' => array('ShopPaymentRequest.user_id = User.id')],
				['table' => 'shops_debts','alias' => 'ShopsDebts','type' => 'LEFT','conditions' => array('ShopPaymentRequest.id = ShopsDebts.shop_payment_request_id')],
	        ],
	        'order' 			=> ["ShopPaymentRequest.state","ShopPaymentRequest.modified"=>"DESC"],
	        'conditions'		=> $conditions,
	        "fields" 			=> ["ShopPaymentRequest.*","ShopCommerce.*","Shop.*","User.*", "WEEK(ShopPaymentRequest.request_date,1) as semana", "ShopsDebts.reason", "ShopsDebts.value as mo_tipo_pago"]
	    );

		$shopPaymentRequests  = $this->paginate('ShopPaymentRequest');

		// var_dump($shopPaymentRequests);
		// var_dump($conditions);
		// die;

		$allRequest = $shopPaymentRequests;
		$shopPaymentRequests = [];
		$this->loadModel("ShopsDebt");
		foreach ($allRequest as $key => $value) {

			if (in_array(AuthComponent::user("role"), [1, 2])) {

				if (in_array($value["ShopPaymentRequest"]["state"], [0,2])) {
					$finalValue   = $value["ShopPaymentRequest"]["request_value"];
					$othersValues = 0;
					$othersIva = 0;
					$othersPaymentIva = 0;
					$finalIva = 0;


					$otherDepts = $this->ShopsDebt->find("first",["fields"=> ["SUM(ShopsDebt.value) as total"],"conditions" => ["ShopsDebt.state" => 0, "ShopCommerce.shop_id"=>$value["ShopCommerce"]["shop_id"]] ]);

					$actualDepts = $this->ShopsDebt->find("first",["fields"=> ["SUM(ShopsDebt.value) as total"],"conditions" => ["ShopsDebt.shop_payment_request_id"=>$value["ShopPaymentRequest"]["id"]] ]);

					$othersValues =  empty($otherDepts["0"]["total"]) ? 0 : $otherDepts["0"]["total"];
					$actualValues =  empty($actualDepts["0"]["total"]) ? 0 : $actualDepts["0"]["total"];
					$finalValue   -= $othersValues;

					if ($othersValues > 0) {
						$othersIva = $othersValues * 0.19;
						$finalIva  += $othersIva;
						$finalValue -= $othersIva;
					}

					$finalIva 	+= $value["ShopPaymentRequest"]["iva"];
					$finalValue -= $finalIva;
					$finalValue -= $actualValues;
					$value["ShopPaymentRequest"]["request_value"] = $finalValue;
				}

			}
			$shopPaymentRequests[] = $value;
		}



		$this->set(compact('shopPaymentRequests','conditions','fechaInicioReporte','fechaInicioReporte2','fechaFinReporte','fechaFinReporte2'));
	}

	public function informe_export() {

		$this->autoRender = false;

		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);
		$query 		= $this->request->query;

		if (isset($query["state"]) && $query["state"] != "" && in_array($query["state"], [0, 1, 2])) {
			$conditions["ShopPaymentRequest.state"] = $query["state"];
			$this->set("estados", $query["state"]);
		} else {
			$this->set("estados", "");

		}

		if (isset($query["commerce"]) && $query["commerce"] != "") {
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->set("commerce", $query["commerce"]);
		} else {
			$this->set("commerce", "");
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

        if (isset($query["type_date"]) && $query["type_date"] != "") {
			$this->set("type_date", $query["type_date"]);
			$campo = $query["type_date"] == 1 ? "request_date" : "final_date";
		} else {
			$this->set("type_date", "1");
		}

        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {

        	if (!isset($campo) || is_null($campo)) {
        		$campo = "request_date";
        	}

            $conditions["DATE(ShopPaymentRequest.${campo}) >="] = $this->request->query["ini"];
            $conditions["DATE(ShopPaymentRequest.${campo}) <="] = $this->request->query["end"];
            $this->set("request_date", $query["ini"]);
            $this->set("request_date_end", $query["end"]);
        }

		if (isset($query["type"]) && $query["type"] != "") {
			$conditions["ShopPaymentRequest.payment_type"] = $query["type"];
			$this->set("type", $query["type"]);
		} else {
			$this->set("type", "");
		}

		if (isset($query["final_date"]) && $query["final_date"] != "") {
			$conditions["DATE(ShopPaymentRequest.final_date)"] = $query["final_date"];
			$this->set("final_date", $query["final_date"]);
		} else {
			$this->set("final_date", "");
		}

        if (isset($this->request->query["ini2"]) && isset($this->request->query["end2"])) {
            $conditions["DATE(ShopPaymentRequest.final_date) >="] = $this->request->query["ini2"];
            $conditions["DATE(ShopPaymentRequest.final_date) <="] = $this->request->query["end2"];
            $this->set("final_date", $query["ini2"]);
            $this->set("final_date_end", $query["end2"]);
        }

		$shopPaymentRequests  = $this->ShopPaymentRequest->find('all', array(
			"recursive"			=> -1,
	        "joins" => [
	        	['table' => 'shop_commerces','alias' => 'ShopCommerce','type' => 'LEFT','conditions' => array('ShopCommerce.id = ShopPaymentRequest.shop_commerce_id')],
	        	['table' => 'shops','alias' => 'Shop','type' => 'LEFT','conditions' => array('ShopCommerce.shop_id = Shop.id')],
	        	['table' => 'users','alias' => 'User','type' => 'LEFT','conditions' => array('ShopPaymentRequest.user_id = User.id')],
				['table' => 'shops_debts','alias' => 'ShopsDebts','type' => 'LEFT','conditions' => array('ShopPaymentRequest.id = ShopsDebts.shop_payment_request_id')],
	        ],
	        'order' 			=> ["ShopPaymentRequest.state","ShopPaymentRequest.modified"=>"DESC"],
	        'conditions'		=> $conditions,
	        "fields" 			=> ["ShopPaymentRequest.*","ShopCommerce.*","Shop.*","User.*", "WEEK(ShopPaymentRequest.request_date,1) as semana", "ShopsDebts.reason", "ShopsDebts.value as mo_tipo_pago"]
	    ));

		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$spreadsheet->getProperties()->setCreator('CREDISHOP')
			->setLastModifiedBy('CREDISHOP')
			->setTitle('Saldos y desembolsos')
			->setSubject('Saldos y desembolsos')
			->setDescription('Saldos y desembolsos ZÍRO')
			->setKeywords('Saldos y desembolsos')
			->setCategory('Saldos y desembolsos');

		$spreadsheet->setActiveSheetIndex(0)
			->setCellValue('A1', 'Código de proveedor')
			->setCellValue('B1', 'Nit proveedor')
			->setCellValue('C1', 'Estado')
			->setCellValue('D1', 'Total solicitado')
			->setCellValue('E1', 'IVA')
			->setCellValue('F1', 'Razón')
			->setCellValue('G1', 'Valor Comisión')
			->setCellValue('H1', 'Valor pagado')
			->setCellValue('I1', 'Fecha solicitud')
			->setCellValue('J1', 'Fecha pago');


		if (!empty($shopPaymentRequests)) {
			$i = 2;
			foreach ($shopPaymentRequests as $key => $shopPaymentRequest) {

				$estado = '';

				switch ($shopPaymentRequest['ShopPaymentRequest']['state']) {
					case '0':
						$estado = "Solicitado";
						break;
					case '1':
						$estado = "Pagado";
						break;
					case '2':
						$estado = "Pendiente";
						break;
				}

				$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $shopPaymentRequest["ShopCommerce"]["code"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $shopPaymentRequest["Shop"]["nit"]);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $estado);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, number_format($shopPaymentRequest['ShopPaymentRequest']['request_value'], 2));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, number_format($shopPaymentRequest['ShopPaymentRequest']['iva'], 2));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $shopPaymentRequest['ShopsDebts']['reason']);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, number_format($shopPaymentRequest['ShopsDebts']['mo_tipo_pago'], 2));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, number_format($shopPaymentRequest['ShopPaymentRequest']['final_value'], 2));
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $shopPaymentRequest['ShopPaymentRequest']['request_date']);
				$spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $shopPaymentRequest['ShopPaymentRequest']['final_date']);

				$i++;
			}
		}

		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

		$spreadsheet->getActiveSheet()->setTitle('Saldos y desembolsos');
		$spreadsheet->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
		//$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

		$writer = IOFactory::createWriter($spreadsheet, 'Xls');
		$name = "files/saldo_desembolso_" . time() . ".xls";
		$writer->save($name);

		$url = Router::url("/", true) . $name;
		$this->redirect($url);

		var_dump($creditRequest);
		die;

	}

	public function index(){

		if (!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"], [1,2]) ) ) {
            $this->redirect(["action" => "index", "?" => ["tab" => 1]]);
        }
        $tab 		= $this->request->query["tab"];
		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);
		$query 		= $this->request->query;

		if (in_array(AuthComponent::user("role"), [4])) {

			$saldos = $this->validateShopCom(true);
			$this->set("saldos", $saldos);
			// $conditions["ShopPaymentRequest.shop_id"] = AuthComponent::user("shop_id");

		}


		if (isset($query["state"]) && $query["state"] != "" && in_array($query["state"], [0, 1, 2])) {
			$conditions["ShopPaymentRequest.state"] = $query["state"];
			$this->set("estados", $query["state"]);
		} else {
			$this->set("estados", "");

		}

		if (isset($query["commerce"]) && $query["commerce"] != "") {
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->set("commerce", $query["commerce"]);
		} else {
			$this->set("commerce", "");
		}

		if (isset($query["request_date"]) && $query["request_date"] != "") {
			$conditions["DATE(ShopPaymentRequest.request_date)"] = $query["request_date"];
			$this->set("request_date", $query["request_date"]);
		} else {
			$this->set("request_date", "");
		}

		if (isset($query["final_date"]) && $query["final_date"] != "") {
			$conditions["DATE(ShopPaymentRequest.final_date)"] = $query["final_date"];
			$this->set("final_date", $query["final_date"]);
		} else {
			$this->set("final_date", "");
		}

		if (isset($query["customer"]) && $query["customer"] != "") {
			$customers = $this->ShopPaymentRequest->Disbursement->Credit->Customer->find("list", ["fields" => ["id", "id"], "conditions" => ["Customer.identification" => $query["customer"]]]);

			if (!empty($customers)) {
				$disbursments = $this->ShopPaymentRequest->Disbursement->find("all", ["fields" => ["Disbursement.shop_payment_request_id"], "conditions" => ["Credit.customer_id" => $customers]]);
				if (!empty($disbursments)) {
					$conditions["ShopPaymentRequest.id"] = Set::extract($disbursments, "{n}.Disbursement.shop_payment_request_id");
				} else {
					$conditions["ShopPaymentRequest.id"] = null;
				}
			} else {
				$conditions["ShopPaymentRequest.id"] = null;
			}

			$this->set("customer", $query["customer"]);
		} else {
			$this->set("customer", "");
		}

		$this->ShopPaymentRequest->recursive = 2;

		if (AuthComponent::user("role") == 4) {
			$shop_commerces = $this->getCommercesByShop(null, true);
			$conditions["ShopPaymentRequest.shop_commerce_id"] = $shop_commerces;
			$shopPaymentRequests = $this->Paginator->paginate(null, $conditions);
			//$conditions["ShopCommerce.shop_commerce_id"] = $this->getCommercesByShop(null, true);
			//if (in_array(AuthComponent::user("role"), [4, 7])) {
			$saldos = $this->validateShopCom(true);
			$this->set("saldos", $saldos);

		} else {
			$conditions["ShopPaymentRequest.payment_type"] = $tab;
			$this->paginate 		= array(
				"recursive"			=> -1,
		        'limit' 			=> 20,
		        "joins" => [
		        	['table' => 'shop_commerces','alias' => 'ShopCommerce','type' => 'LEFT','conditions' => array('ShopCommerce.id = ShopPaymentRequest.shop_commerce_id')],
		        	['table' => 'shops','alias' => 'Shop','type' => 'LEFT','conditions' => array('ShopCommerce.shop_id = Shop.id')],
		        	['table' => 'users','alias' => 'User','type' => 'LEFT','conditions' => array('ShopPaymentRequest.user_id = User.id')],
		        ],
		        'order' 			=> ["ShopPaymentRequest.state","ShopPaymentRequest.modified"=>"DESC"],
		        'conditions'		=> $conditions,
		        "fields" 			=> ["ShopPaymentRequest.*","ShopCommerce.*","Shop.*","User.*", "WEEK(ShopPaymentRequest.request_date,1) as semana"]
		    );

			$shopPaymentRequests  = $this->paginate('ShopPaymentRequest');


			$allRequest = $shopPaymentRequests;
			$shopPaymentRequests = [];
			$this->loadModel("ShopsDebt");
			foreach ($allRequest as $key => $value) {

				if (in_array(AuthComponent::user("role"), [1, 2])) {

					if (in_array($value["ShopPaymentRequest"]["state"], [0,2])) {
						$finalValue   = $value["ShopPaymentRequest"]["request_value"];
						$othersValues = 0;
						$othersIva = 0;
						$othersPaymentIva = 0;
						$finalIva = 0;


						$otherDepts = $this->ShopsDebt->find("first",["fields"=> ["SUM(ShopsDebt.value) as total"],"conditions" => ["ShopsDebt.state" => 0, "ShopCommerce.shop_id"=>$value["ShopCommerce"]["shop_id"]] ]);

						$actualDepts = $this->ShopsDebt->find("first",["fields"=> ["SUM(ShopsDebt.value) as total"],"conditions" => ["ShopsDebt.shop_payment_request_id"=>$value["ShopPaymentRequest"]["id"]] ]);

						$othersValues =  empty($otherDepts["0"]["total"]) ? 0 : $otherDepts["0"]["total"];
						$actualValues =  empty($actualDepts["0"]["total"]) ? 0 : $actualDepts["0"]["total"];
						$finalValue   -= $othersValues;

						if ($othersValues > 0) {
							$othersIva = $othersValues * 0.19;
							$finalIva  += $othersIva;
							$finalValue -= $othersIva;
						}

						$finalIva 	+= $value["ShopPaymentRequest"]["iva"];
						$finalValue -= $finalIva;
						$finalValue -= $actualValues;
						$value["ShopPaymentRequest"]["request_value"] = $finalValue;
					}


					$disbursments = $this->ShopPaymentRequest->Disbursement->find("all", ["fields" => ["Credit.customer_id"], "conditions" => ["Disbursement.shop_payment_request_id" => $value["ShopPaymentRequest"]["id"]]]);

					if (!empty($disbursments)) {
						$customersIDs = Set::extract($disbursments, "{n}.Credit.customer_id");
						$customers = $this->ShopPaymentRequest->Disbursement->Credit->Customer->find("list", ["fields" => ["id", "identification"], "conditions" => ["Customer.id" => $customersIDs]]);
						$value["ShopPaymentRequest"]["customers"] = $customers;
					} else {
						$value["ShopPaymentRequest"]["customers"] = [];
					}
				}

				if ($value["ShopPaymentRequest"]["payment_type"] == 2) {
					$shopPaymentRequests[$value["ShopPaymentRequest"]["payment_type"]][$value["0"]["semana"] . " - " . date("Y", strtotime($value["ShopPaymentRequest"]["request_date"]))][] = $value;
				} else {
					$shopPaymentRequests[$value["ShopPaymentRequest"]["payment_type"]][] = $value;
				}
			}

		}

		$this->set(compact('shopPaymentRequests','conditions','tab'));
	}

	public function index1_old()
	{
		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);

		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q", $q);

		if (in_array(AuthComponent::user("role"), [4, 7])) {
			$saldos = $this->validateShopCom(true);
			$this->set("saldos", $saldos);
			if (AuthComponent::user("role") == 4) {
				$conditions["ShopPaymentRequest.shop_commerce_id"] = $this->getCommercesByShop(null, true);
			} else {
				$conditions["ShopPaymentRequest.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
			}
		}

		$this->ShopPaymentRequest->recursive = 2;
		$this->Paginator->settings = array('order' => array('ShopPaymentRequest.modified' => 'DESC'));
		$shopPaymentRequests = $this->Paginator->paginate(null, $conditions);

		$this->set(compact('shopPaymentRequests'));
	}

	public function pending($id)
	{
		$this->autoRender = false;
		$id = $this->decrypt($id);

		$this->ShopPaymentRequest->recursive = -1;
		$request = $this->ShopPaymentRequest->findById($id);
		$request["ShopPaymentRequest"]["state"] = 2;
		$request["ShopPaymentRequest"]["notes"] = $this->request->data["reason"];
		$request["ShopPaymentRequest"]["date_pending"] = date("Y-m-d H:i:s");
		$this->ShopPaymentRequest->save($request);
		$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
	}

	public function view_old($id = null)
	{

		$id = $this->decrypt($id);

		if (!$this->ShopPaymentRequest->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->ShopPaymentRequest->recursive = 3;
		$conditions = array('ShopPaymentRequest.' . $this->ShopPaymentRequest->primaryKey => $id);
		$shopPaymentRequest = $this->ShopPaymentRequest->find('first', compact('conditions'));
		/*echo '<pre>';
		var_dump($shopPaymentRequest);
		echo '</pre>';
		exit();
		echo $shopPaymentRequest["ShopCommerce"]["shop_id"];exit();*/
		$shop = $this->ShopPaymentRequest->findById($shopPaymentRequest["ShopCommerce"]["shop_id"]);


		//$shop = $this->ShopPaymentRequest->Shop->findById($shopPaymentRequest["ShopPaymentRequest"]["shop_id"]);

		/*		$otherDepts = $this->ShopPaymentRequest->ShopsDebt->find("all", ["conditions" => ["ShopsDebt.shop_i" => $shopPaymentRequest["ShopCommerce"]["shop_id"], "ShopsDebt.state" => "0"], "recursive" => -1]);*/

		//`u244965014_dcredishopco`.
		$query = "SELECT
			`ShopsDebt`.`id`,
			`ShopsDebt`.`user_id`,
			`ShopsDebt`.`shop_commerce_id`,
			`ShopsDebt`.`credit_id`,
			`ShopsDebt`.`shop_payment_request_id`,
			`ShopsDebt`.`type`,
			`ShopsDebt`.`value`,
			`ShopsDebt`.`reason`,
			`ShopsDebt`.`state`,
			`ShopsDebt`.`created`,
			`ShopsDebt`.`modified`,
			`ShopCommerce`.`id`
		FROM
			`shops_debts` AS `ShopsDebt`
		LEFT JOIN `shop_commerces` AS `ShopCommerce` ON (
			`ShopCommerce`.`id` = `ShopsDebt`.`shop_commerce_id`
		)
		LEFT JOIN `shop_references` AS `ShopReference` ON (
			`ShopReference`.`shop_id` = `ShopCommerce`.`shop_id`
		)
		WHERE
			`ShopCommerce`.`shop_id` = '" . $shopPaymentRequest["ShopCommerce"]["shop_id"] . "'
		AND `ShopsDebt`.`state` = 0";

		$otherDepts = $this->ShopPaymentRequest->query($query);

		if ($this->request->is("post")) {
			if (!empty($otherDepts)) {
				foreach ($otherDepts as $key => $value) {
					$value["ShopsDebt"]["shop_payment_request_id"] = $id;
					$value["ShopsDebt"]["state"] = 2;
					$this->ShopPaymentRequest->ShopsDebt->save($value);
				}
			}

			$this->ShopPaymentRequest->ShopsDebt->updateAll(
				["ShopsDebt.state" => 2],
				["ShopsDebt.shop_payment_request_id" => $id]
			);
			$this->ShopPaymentRequest->Disbursement->updateAll(
				["Disbursement.state" => 3],
				["Disbursement.shop_payment_request_id" => $id]
			);

			$this->request->data["ShopPaymentRequest"]["id"] = $id;
			$this->request->data["ShopPaymentRequest"]["state"] = 1;

			$this->ShopPaymentRequest->save($this->request->data);

			$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			$this->redirect(["action" => "view", $this->encrypt($id)]);
		}

		$this->set('otherDepts', $otherDepts);
		$this->set('shopPaymentRequest', $shopPaymentRequest);
		$this->set('shop', $shop);


	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->ShopPaymentRequest->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}

		$shopPaymentRequest=$this->ShopPaymentRequest->findById($id);
		$this->loadModel('Disbursement');
		// $disbursements= $this->Disbursement->find('all', [
		// 	'contain' => [
		// 		'Credit' => [
		// 			'Customer'
		// 		]
		// 	],
		// 	'conditions' => ['shop_payment_request_id' => $id]
		// ]);

		$query = "SELECT
		`disbursements`.`id`,
		`disbursements`.`value`,
		`disbursements`.`shop_payment_request_id`,
		`disbursements`.`credit_id`,
		`credits`.`id`,
		`credits`.`code_pay`,
		`credits`.`customer_id`,
		`customers`.`id`,
		`customers`.`identification`

		FROM
			`disbursements`
		INNER JOIN `credits`  ON (
			`credits`.`id` = `disbursements`.`credit_id`
		)
		INNER JOIN `customers` ON (
			`customers`.`id` = `credits`.`customer_id`
		)
		WHERE
			`disbursements`.`shop_payment_request_id` = '".$id."'";


	$disbursements = $this->Disbursement->query($query);

		// debug($shopPaymentRequest);
		// die();
		// $this->ShopPaymentRequest->recursive = 3;
		// $conditions = array('ShopPaymentRequest.' . $this->ShopPaymentRequest->primaryKey => $id);
		// $shopPaymentRequest = $this->ShopPaymentRequest->find('first', compact('conditions'));

		//$shop 		= $this->ShopPaymentRequest->Shop->findById($shopPaymentRequest["ShopPaymentRequest"]["shop_id"]);
		//	$shop = $this->ShopPaymentRequest->findById($shopPaymentRequest["ShopCommerce"]["shop_id"]);

		$this->loadModel("Shop");

		$shop = $this->Shop->findById($shopPaymentRequest["ShopCommerce"]["shop_id"]);

		$socialreason =  $shop["Shop"]["social_reason"];//$shop["ShopCommerce"]["Shop"]["social_reason"];
		$socialcode = $shop["Shop"]["social_reason"];// $shop["ShopCommerce"]["Shop"]["social_reason"];
		//$otherDepts = $this->ShopPaymentRequest->ShopsDebt->find("all",["conditions"=>["ShopsDebt.shop_id"=>$shopPaymentRequest["ShopPaymentRequest"]["shop_id"],"ShopsDebt.state"=>"0"],"recursive" => -1]);
		$query = "SELECT
		`ShopsDebt`.`id`,
		`ShopsDebt`.`user_id`,
		`ShopsDebt`.`shop_commerce_id`,
		`ShopsDebt`.`credit_id`,
		`ShopsDebt`.`shop_payment_request_id`,
		`ShopsDebt`.`type`,
		`ShopsDebt`.`value`,
		`ShopsDebt`.`reason`,
		`ShopsDebt`.`state`,
		`ShopsDebt`.`created`,
		`ShopsDebt`.`modified`,
		`ShopCommerce`.`id`
		FROM
			`shops_debts` AS `ShopsDebt`
		LEFT JOIN `shop_commerces` AS `ShopCommerce` ON (
			`ShopCommerce`.`id` = `ShopsDebt`.`shop_commerce_id`
		)
		LEFT JOIN `shop_references` AS `ShopReference` ON (
			`ShopReference`.`shop_id` = `ShopCommerce`.`shop_id`
		)
		WHERE
			`ShopCommerce`.`shop_id` = '" . $shopPaymentRequest["ShopCommerce"]["shop_id"] . "'
		AND `ShopsDebt`.`state` = 0";


		$otherDepts = $this->ShopPaymentRequest->query($query);

		if ($this->request->is("post")) {

			if(!empty($otherDepts)){
				foreach ($otherDepts as $key => $value) {
					$value["ShopsDebt"]["shop_payment_request_id"] = $id;
					$value["ShopsDebt"]["state"] = 2;
					$this->ShopPaymentRequest->ShopsDebt->save($value);
				}
			}

			$this->ShopPaymentRequest->ShopsDebt->updateAll(
				["ShopsDebt.state" => 2],
				["ShopsDebt.shop_payment_request_id" => $id]
			);
			$this->ShopPaymentRequest->Disbursement->updateAll(
				["Disbursement.state" => 3],
				["Disbursement.shop_payment_request_id" => $id]
			);

			$this->request->data["ShopPaymentRequest"]["id"] 	= $id;
			$this->request->data["ShopPaymentRequest"]["state"] = 1;
			$this->request->data['ShopPaymentRequest']['final_value']=$this->request->data['valorPagoSinDecimal'];
			$this->ShopPaymentRequest->save($this->request->data);
			$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			$this->redirect(["action"=>"view", $this->encrypt($id)]);
		}


		$this->set('otherDepts', $otherDepts);
		$this->set('shopPaymentRequest', $shopPaymentRequest);
		$this->set('shop', $shop);
		$this->set('socialreason', $socialreason);
		$this->set('socialcode', $socialcode);
		$this->set('disbursements', $disbursements);



	}

	/*	public function view($id = null)
		{
			$id = $this->decrypt($id);

			echo $id;exit();
			if (!$this->ShopPaymentRequest->exists($id)) {
				throw new NotFoundException(__('Página no encontrada'));
			}
			$this->ShopPaymentRequest->recursive = 3;
			$conditions = array('ShopPaymentRequest.' . $this->ShopPaymentRequest->primaryKey => $id);
			$shopPaymentRequest = $this->ShopPaymentRequest->find('first', compact('conditions'));

			$shop = $this->ShopPaymentRequest->Shop->findById($shopPaymentRequest["ShopPaymentRequest"]["shop_id"]);
	var_dump($shop);exit();
			$otherDepts = $this->ShopPaymentRequest->ShopsDebt->find("all", ["conditions" => ["ShopsDebt.shop_id" => $shopPaymentRequest["ShopPaymentRequest"]["shop_id"], "ShopsDebt.state" => "0"], "recursive" => -1]);

			if ($this->request->is("post")) {
				if (!empty($otherDepts)) {
					foreach ($otherDepts as $key => $value) {
						$value["ShopsDebt"]["shop_payment_request_id"] = $id;
						$value["ShopsDebt"]["state"] = 2;
						$this->ShopPaymentRequest->ShopsDebt->save($value);
					}
				}

				$this->ShopPaymentRequest->ShopsDebt->updateAll(
					["ShopsDebt.state" => 2],
					["ShopsDebt.shop_payment_request_id" => $id]
				);
				$this->ShopPaymentRequest->Disbursement->updateAll(
					["Disbursement.state" => 3],
					["Disbursement.shop_payment_request_id" => $id]
				);

				$this->request->data["ShopPaymentRequest"]["id"] = $id;
				$this->request->data["ShopPaymentRequest"]["state"] = 1;

				$this->ShopPaymentRequest->save($this->request->data);

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(["action" => "view", $this->encrypt($id)]);
			}

			$this->set('otherDepts', $otherDepts);
			$this->set('shopPaymentRequest', $shopPaymentRequest);
			$this->set('shop', $shop);


		}*/


	/*public function add($shop_commerce_id = null)
	{

		$this->ShopPaymentRequest->Shop->ShopCommerce->recursive = -1;

		$id = AuthComponent::user("shop_id");
		$shopCommerces = $this->ShopPaymentRequest->Shop->ShopCommerce->findAllByShopId($id);
		$shop = $this->ShopPaymentRequest->Shop->findById($id);

		if (!empty($shopCommerces)) {
			$shopCommerces = Set::extract($shopCommerces, "{n}.ShopCommerce.id");
		}

		$this->loadModel("ShopsDebt");
		$this->loadModel("Disbursement");
		$this->loadModel("Payment");

		$debts = $this->ShopsDebt->find("all", ["conditions" => ["ShopsDebt.shop_id" => $id, "state" => "0"], "recursive" => -1]);
		$disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.shop_commerce_id" => $shopCommerces, "Disbursement.state" => "1"], "recursive" => 2]);

		$payments = $this->Payment->find("all", ["conditions" => ["Payment.shop_commerce_id" => $shopCommerces, "Payment.state" => "0"], "recursive" => 2]);

		$debtsTotal = $this->ShopsDebt->field("SUM(value)", ["ShopsDebt.shop_id" => $id, "state" => "0"]);
		$disbursmentsTotal = $this->Disbursement->field("SUM(value)", ["Disbursement.shop_commerce_id" => $shopCommerces, "state" => "1"]);
		$total = 0;


		$debtsTotal = is_null($debtsTotal) ? 0 : $debtsTotal;
		$disbursmentsTotal = is_null($disbursmentsTotal) ? 0 : $disbursmentsTotal;

		$total = $disbursmentsTotal - $debtsTotal;

		if ($total <= 0) {
			$this->redirect(["action" => "index"]);
		}


		if (is_null($shop)) {
			throw new NotFoundException(__('Página no encontrada'));
		}

		if ($this->request->is('post')) {
			$this->autoRender = false;
			$porcentual = $this->request->data["type"] == 1 ? ($shop["Shop"]["cost_min"] / 100) : ($shop["Shop"]["cost_max"] / 100);

			$otherDebpt = $total * $porcentual;

			$debtInfo = [
				"ShopsDebt" => [
					"user_id" => 0,
					"type" => 2,
					"value" => $otherDebpt,
					"reason" => $this->request->data["type"] == 1 ? "Comisión Pago 1" : "Comisión Pago 2",
					"state" => 0,
					"shop_id" => $id,
				]
			];

			$this->ShopsDebt->create();
			$this->ShopsDebt->save($debtInfo);

			$debtInfo["ShopsDebt"]["id"] = $this->ShopsDebt->id;

			$debts[] = $debtInfo;

			$ivaPago = ($otherDebpt * 0.19) + ($debtsTotal * 0.19);
			$totalPago = $total - $otherDebpt - $ivaPago;

			$data = [
				"ShopPaymentRequest" => [
					"request_value" => $totalPago,
					"iva" => $ivaPago,
					"request_date" => date("Y-m-d H:i:s"),
					"shop_id" => $id,
					"payment_type" => $this->request->data["type"],
					"user_id" => AuthComponent::user("id")
				]
			];

			$this->ShopPaymentRequest->create();
			if ($this->ShopPaymentRequest->save($data)) {
				$shopPaymentRequestId = $this->ShopPaymentRequest->id;

				foreach ($debts as $key => $value) {
					$value["ShopsDebt"]["state"] = 1;
					$value["ShopsDebt"]["shop_payment_request_id"] = $shopPaymentRequestId;
					$this->ShopsDebt->save($value);
				}

				foreach ($disbursments as $key => $value) {
					$value["Disbursement"]["state"] = 2;
					$value["Disbursement"]["shop_payment_request_id"] = $shopPaymentRequestId;
					$this->Disbursement->save($value);
				}

				$this->loadModel("User");
				$this->User->recursive = -1;
				$users = $this->User->findAllByRole([1, 2]);

				$emails = Set::extract($users, "{n}.User.email");

				$options = [
					"subject" => "Pago solicitado " . $shop["Shop"]["social_reason"],
					"to" => $emails,
					"vars" => ["name" => $shop["Shop"]["social_reason"] . " - " . $shop["Shop"]["code"], "total" => $totalPago],
					"template" => "payment_request",
				];
				$this->sendMail($options);

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		$this->set(compact('shop', "debts", "disbursments", "total", "debtsTotal", "paymentsTotal", "payments"));
	}*/

	public function add($shop_commerce_id)
	{
		$id = $this->decrypt($shop_commerce_id);
		$this->ShopPaymentRequest->ShopCommerce->unBindModel(["hasMany" => ["User", "CreditsRequest"]]);
		$shopCommerce = $this->ShopPaymentRequest->ShopCommerce->findById($id);

		//$fecha_inicial = date('Y-m-d', strtotime('last monday'));
		$fecha_inicial = date('Y-m-d', strtotime('last sunday'));

		$this->loadModel("ShopsDebt");
		$r = $this->loadModel("Disbursement");
		$this->loadModel("Payment");

		$debts = $this->ShopsDebt->find("all", ["conditions" => ["ShopsDebt.shop_commerce_id" => $id, "state" => "0"], "recursive" => -1]);


		if ($this->request->is('post')) {
			$this->autoRender = false;
			$porcentual 		=  $this->request->data["porcentual"];
			$disbursmentpago 	=  $this->request->data["disbursmentpago"];
			$iddisbursments 	= explode(",",$this->request->data["iddisbursments"]);

			//$porcentual = $this->request->data["type"] == 1 ? ($shopCommerce["Shop"]["cost_max"] / 100) : ($shopCommerce["Shop"]["cost_min"] / 100);
			//$otherDebpt = $total * $porcentual;
			$otherDebpt = $disbursmentpago * ($porcentual/100);

			$debtInfo = [
				"ShopsDebt" => [
					"user_id" => 0,
					"type" => 2,
					"value" => $otherDebpt,
					// "reason" => $this->request->data["type"] == 1 ? "Comisión Pago 1" : "Comisión Pago 2",
					"reason" => "Comisión Pago",
					"state" => 0,
					"shop_commerce_id" => $id,
				]
			];

			$this->ShopsDebt->create();
			$this->ShopsDebt->save($debtInfo);

			$debtInfo["ShopsDebt"]["id"] = $this->ShopsDebt->id;

			$debts[] = $debtInfo;

			//$ivaPago = ($otherDebpt * 0.19) + ($debtsTotal * 0.19);
			//$totalPago = $total - $otherDebpt - $ivaPago;

			$ivaPago 	= ($otherDebpt * 0.19);
			$retefuente = ($otherDebpt * 0.11);

			// $ivaPago 	= 0;
			$totalPago 	= $disbursmentpago;

			$data = [
				"ShopPaymentRequest" => [
					"request_value" => $totalPago,
					"iva" => $ivaPago,
					"retefuente" => $retefuente,
					"request_date" => date("Y-m-d H:i:s"),
					"shop_commerce_id" => $id,
					"payment_type" => $this->request->data["type"],
					"user_id" => AuthComponent::user("id")
				]
			];

			if ($this->request->data["type"] == 1) {
				$disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.id" => $iddisbursments, "Disbursement.state" => "1"], "recursive" => 1]);

				// $disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.id" => $iddisbursments, "Disbursement.state" => "1", "DATE(Disbursement.modified) > " => $fecha_inicial], "recursive" => 1]);
			}else{
				$disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.id" => $iddisbursments, "Disbursement.state" => "1", "DATE(Disbursement.modified) <=" => $fecha_inicial], "recursive" => 1]);
			}

			$this->ShopPaymentRequest->create();
			if ($this->ShopPaymentRequest->save($data)) {
				$shopPaymentRequestId = $this->ShopPaymentRequest->id;

				foreach ($debts as $key => $value) {
					$value["ShopsDebt"]["state"] = 1;
					$value["ShopsDebt"]["shop_payment_request_id"] = $shopPaymentRequestId;
					$this->ShopsDebt->save($value);
				}

				foreach ($disbursments as $key => $value) {
					$value["Disbursement"]["state"] = 2;
					$value["Disbursement"]["shop_payment_request_id"] = $shopPaymentRequestId;
					$this->Disbursement->save($value);
				}

				$this->loadModel("User");
				$this->User->recursive = -1;
				$users = $this->User->findAllByRole([1, 2]);

				$emails = Set::extract($users, "{n}.User.email");

				$options = [
					"subject" => "Pago solicitado " . $shopCommerce["Shop"]["social_reason"] . " - " . $shopCommerce["ShopCommerce"]["name"],
					"to" => $emails,
					"vars" => ["name" => $shopCommerce["Shop"]["social_reason"] . " - " . $shopCommerce["ShopCommerce"]["name"], "total" => $totalPago],
					"template" => "payment_request",
				];

				if (!$this->validarLocalHost()) {
					$this->sendMail($options);
				}

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		$this->Disbursement->unBindModel(["belongsTo"=>["ShopCommerce"]]);

		$disbursments1 = $this->Disbursement->find("all",
			["conditions" => [
				"Disbursement.shop_commerce_id" => $id,
				"Disbursement.state" => "1",
				//"DATE(Disbursement.modified) >" => $fecha_inicial,
				'Credit.id !='=>null
			],
			"recursive" => 1
		]);


		$disbursments2 = $this->Disbursement->find("all", [
			// 'contain' => ['CreditsRequest'],
			"conditions" => [
				"Disbursement.shop_commerce_id" => $id,
				"Disbursement.state" => "1",
				//"DATE(Disbursement.modified) <=" => $fecha_inicial,
				'Credit.id !='=>null
			],
			"recursive" => 1
		]);

		$this->loadModel("CreditsRequest");

		if (!empty($disbursments1)) {
			$this->CreditsRequest->recursive = -1;
			foreach ($disbursments1 as $key => $value) {
				$disbursments1[$key]["Credit"]["CreditsRequest"] = $this->CreditsRequest->findById($value["Credit"]["credits_request_id"])["CreditsRequest"];
			}
		}

		if (!empty($disbursments2)) {
			$this->CreditsRequest->recursive = -1;
			foreach ($disbursments2 as $key => $value) {
				$disbursments2[$key]["Credit"]["CreditsRequest"] = $this->CreditsRequest->findById($value["Credit"]["credits_request_id"])["CreditsRequest"];
			}
		}

		$payments = [];

		$debtsTotal = $this->ShopsDebt->field("SUM(value)", ["ShopsDebt.shop_commerce_id" => $id, "state" => "0",]);

		$query = "SELECT
			SUM(`ShopsDebt`.`value`)
		FROM
			`shops_debts` AS `ShopsDebt`
		LEFT JOIN `disbursements` AS `Disbursement` ON (
			`Disbursement`.`shop_commerce_id` = `ShopsDebt`.`shop_commerce_id`
		)
		WHERE
			`ShopsDebt`.`shop_commerce_id` = ".$id."
		AND `ShopsDebt`.`state` = 0
		AND DATE(Disbursement.modified) >'" . $fecha_inicial . "'
		LIMIT 1";

		//`u244965014_dcredishopco`.
		$query2 = "SELECT
				SUM(`ShopsDebt`.`value`)
			FROM
				`shops_debts` AS `ShopsDebt`
			LEFT JOIN `disbursements` AS `Disbursement` ON (
				`Disbursement`.`shop_commerce_id` = `ShopsDebt`.`shop_commerce_id`
			)
			WHERE
				`ShopsDebt`.`shop_commerce_id` = ".$id."
			AND `ShopsDebt`.`state` = 0
			AND DATE(Disbursement.modified) <= '" . $fecha_inicial . "'
			LIMIT 1";

		$debtsTotal1 = $this->ShopsDebt->query($query);
		$debtsTotal2 = $this->ShopsDebt->query($query2);

		$disbursmentsTotal = $this->Disbursement->field("SUM(value)", ["Disbursement.shop_commerce_id" => $id, "state" => "1"]);
		$total = 0;

		$debtsTotal = is_null($debtsTotal) ? 0 : $debtsTotal;
		$debtsTotal1 = is_null($debtsTotal1) ? 0 : $debtsTotal1;
		$debtsTotal2 = is_null($debtsTotal2) ? 0 : $debtsTotal2;
		$disbursmentsTotal = is_null($disbursmentsTotal) ? 0 : $disbursmentsTotal;

		$total = $disbursmentsTotal - $debtsTotal;

		if ((empty($disbursments1) && empty($disbursments2)) || $total <= 0 ) {
			$this->redirect(["action" => "index1"]);
		}
		/*
				echo '<pre>';
				var_dump($disbursments);
				echo '</pre>';*/

		if (is_null($shopCommerce)) {
			throw new NotFoundException(__('Página no encontrada'));
		}

		$this->set(compact('shopCommerce', "debts","disbursments", "disbursments1", "disbursments2", "total", "debtsTotal", "paymentsTotal", "payments", "debtsTotal1", "debtsTotal2", "debtsTotal"));
		//$this->set(compact('shopCommerce', "debts", "disbursments2", "total", "debtsTotal", "paymentsTotal", "payments"));
	}


	public function edit($id = null)
	{
		$id = $this->decrypt($id);
		$this->ShopPaymentRequest->id = $id;
		if (!$this->ShopPaymentRequest->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ShopPaymentRequest->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('ShopPaymentRequest.' . $this->ShopPaymentRequest->primaryKey => $id);
			$this->request->data = $this->ShopPaymentRequest->find('first', compact('conditions'));
		}
		$shopCommerces = $this->ShopPaymentRequest->ShopCommerce->find('list');
		$users = $this->ShopPaymentRequest->User->find('list');
		$this->set(compact('shopCommerces', 'users'));
	}
}
