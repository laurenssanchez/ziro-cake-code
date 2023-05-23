<?php


require_once '../Vendor/spreadsheet/vendor/autoload.php';
require_once ROOT.'/app/Vendor/CifrasEnLetras.php';


ini_set("memory_limit", "-1");
set_time_limit(0);

use Cake\Log\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

App::uses('AppController', 'Controller');
/**
 * CreditsRequests Controller
 *
 * @property CreditsRequest $CreditsRequest
 * @property PaginatorComponent $Paginator
 */
class CreditsRequestsController extends AppController {

	public $components = array('Paginator');
	public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('send_codes_credit','request_crediventas','validate_limits','create_request_approved','validateCode','applyCredit','sendCodesCredit');
    }

	public function index_support(){
		$conditions = $this->CreditsRequest->buildConditions($this->request->query);
		$conditions["CreditsRequest.state"] = 5;
		if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
			$conditions["Customer.identification"] = $this->request->query['ccCustomer'];
			$this->Set("ccCustomer",$this->request->query['ccCustomer']);
		}
		$order = ["CreditsRequest.date_disbursed desc"];
		try {
			$this->CreditsRequest->recursive 	= 2;
			$this->Paginator->settings 			= array('order'=>$order);
			$creditsRequests 					= $this->Paginator->paginate(null, $conditions);
		} catch (Exception $e) {
			$creditsRequests = [];
		}
		$this->set("creditsRequests",$creditsRequests);
	}

	public function request_crediventas(){
		$this->autoRender = false;

		$this->log("=========Inicio Crediventas============","debug");

		$data = $this->request->data;
		$this->log("data received: ".json_encode($data),"debug");

		$this->loadModel("Customer");
		$this->loadModel("ShopCommerce");

		$existsCommerce = $this->ShopCommerce->field("id",["code" => '73221084',"state" => 1]);

		if(!$existsCommerce){
          return -2;
      	}else{
      		$customer = $this->Customer->find("first",["conditions" => ["identification"=> trim($this->request->data["Customer"]["identification"]) ,"type" => 1],"recursive" => -1 ]);

      		if(!empty($customer)){
      			$this->log("Cliente existe","debug");
	            $this->loadModel("CreditsRequest");
	            $actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState($customer["Customer"]["id"],$existsCommerce,[0,1,2]);

	            if(!empty($actualStudy)){
	            	$this->log("Cliente con estudio","debug");
	              	return -1;
	            }
	        }
	        if(empty($customer)){
	        	$this->log("Cliente no existe","debug");
	            $this->Customer->Create();
	            $customer = $this->request->data["Customer"];
	        }else{
	            $customer["Customer"] = array_merge($customer["Customer"],$this->request->data["Customer"]);
	        }

	        if (!empty($customer["document_file_up"])) {
	        	$fileData = file_get_contents($customer["url_files"].$customer["document_file_up"]);
	        	file_put_contents(WWW_ROOT."files".DS."customers".DS.$customer["document_file_up"],$fileData);
	        }
	        if (!empty($customer["document_file_down"])) {
	        	$fileData = file_get_contents($customer["url_files"].$customer["document_file_down"]);
	        	file_put_contents(WWW_ROOT."files".DS."customers".DS.$customer["document_file_down"],$fileData);
	        }
	        if (!empty($customer["image_file"])) {
	        	$fileData = file_get_contents($customer["url_files"].$customer["image_file"]);
	        	file_put_contents(WWW_ROOT."files".DS."customers".DS.$customer["image_file"],$fileData);
	        }

	        if($this->Customer->save($customer)){
	        	$this->log("Cliente guardado","debug");
	            $customerID = $this->Customer->id;

	            $this->Customer->CustomersPhone->deleteAll(array('CustomersPhone.customer_id' => $customerID), false);
	            $this->Customer->CustomersAddress->deleteAll(array('CustomersAddress.customer_id' => $customerID), false);
	            $this->Customer->CustomersReference->deleteAll(array('CustomersReference.customer_id' => $customerID), false);

	            $data = $this->request->data;

	            if(!empty($data["CustomersReference"])){
	              foreach ($data["CustomersReference"] as $key => $value) {
	                $value["customer_id"] = $customerID;
	                $this->Customer->CustomersReference->create();
	                $this->Customer->CustomersReference->save($value);
	              }
	            }

	            if(!empty($data["CustomersAddress"])){
	              $data["CustomersAddress"]["customer_id"] = $customerID;
	              $this->Customer->CustomersAddress->create();
	              $this->Customer->CustomersAddress->save($data["CustomersAddress"]);
	            }

	            if(!empty($data["CustomersPhone"])){
	              foreach ($data["CustomersPhone"] as $key => $value) {
	                $value["customer_id"] = $customerID;
	                if(!empty($value["phone_number"])){
	                  $this->Customer->CustomersPhone->create();
	                  $this->Customer->CustomersPhone->save($value);
	                }
	              }
	            }

	            $this->loadModel("ShopCommerce");
	            $this->loadModel("CreditsRequest");
	            $this->loadModel("CreditsLine");

	            $shop_commerce_id = $this->ShopCommerce->field("id",["code" => $this->request->data["Customer"]["code"] ]);
	            $creditLineId = $this->CreditsLine->findByState(1);

	            $dataRequest 					= $this->request->data["CreditsRequest"];
	            $dataRequest["shop_commerce_id"]= $existsCommerce;
	            $dataRequest["customer_id"] 	= $customerID;
	            $dataRequest["credits_line_id"] = is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"];

	            $this->CreditsRequest->create();
	            if ($this->CreditsRequest->save($dataRequest)) {
	            	$this->log("Solicitud creada","debug");
	              return 1;
	            }else{
	            	$this->log("Solicitud no creada","debug");
	            }
	          }else{
	          	$this->log("Cliente no guardó","debug");
	            return -3;
	          }

      	}

      	$this->log("respuesta: ".json_encode($customer),"debug");
      	$this->log("=========Fin Crediventas============","debug");

		return json_encode($customer);
		die;
	}


	public function index() {

		// debug(AuthComponent::user("role"));
		// die();

		if(AuthComponent::user("role") == 9){
			$this->redirect(["action"=>"cobranza"]);
		}

		if(AuthComponent::user("role") == 11){
			$this->redirect(["action"=>"juridico"]);
		}

 		if(AuthComponent::user("role") == 10){
   			$this->redirect(["controller"=>"customers_codes","action" => "index","?"=>["tab" => 1]]);
  		}

 		if(AuthComponent::user("role") == 12){
   			$this->redirect(["controller"=>"customers_codes","action" => "index","?"=>["tab" => 1]]);
  		}



		$conditions = $this->CreditsRequest->buildConditions($this->request->query);
		$conditions["CreditsRequest.state !="] = 7;
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$order = array('CreditsRequest.created'=>'DESC');

		if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
			$conditions["Customer.identification"] = $this->request->query['ccCustomer'];
           //echo $conditions ;
			$this->Set("ccCustomer",$this->request->query['ccCustomer']);
		}

		if(isset($this->request->query['idrequest']) && !empty($this->request->query['idrequest']) ){
			$conditions["CreditsRequest.id"] = $this->request->query['idrequest'];
			$this->Set("idrequest",$this->request->query['idrequest']);
		}

		$fechaInicioReporte = date("Y-m-d");
		$fechaFinReporte = date("Y-m-d");


		if(isset($this->request->query['commerce']) && !empty($this->request->query['commerce']) ){
			$this->loadModel("Shop");
			$shopCommerce 	= $this->ShopCommerce->findByCode($this->request->query['commerce']);
			if(!empty($shopCommerce)){
				$conditions["CreditsRequest.shop_commerce_id"] = $shopCommerce["ShopCommerce"]["id"];
			}else{
				$conditions["CreditsRequest.shop_commerce_id"] = null;
			}
			$this->Set("commerce",$this->request->query['commerce']);
		}

		if (AuthComponent::user("role") == 15) {

			if(!isset($this->request->query["ini"])){
				$fechaInicioReporte = date("Y-m-d",strtotime("-1 day"));
			}else{
				$fechaInicioReporte = $this->request->query["ini"];
			}
			if(!isset($this->request->query["end"])){
				$fechaFinReporte = date("Y-m-d");
			}else{
				$fechaFinReporte = $this->request->query["end"];
			}

			if (isset($this->request->query["ini"]) && $this->request->query["end"]) {
				$conditions["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;
				$conditions["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

			}

			$conditions["CreditsRequest.empresa_id"] = AuthComponent::user("empresa_id");
			$this->paginate = [
				'order'=> ['CreditsRequest.id'=>'DESC'],
				'limit' => 20,
				'conditions' => $conditions,
			];

			$this->CreditsRequest->recursive = 1;
			$creditsRequests = $this->paginate('CreditsRequest');
		}elseif(AuthComponent::user("role") == 5){
			if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"], [1,2,3]) ) ){
				$this->redirect(array("controller"=>"credits_requests","action"=>"index","?"=>["tab"=>1]));
			}
			$conditions["CreditsRequest.customer_id"] = AuthComponent::user("customer_id");

			switch ($this->request->query["tab"]) {
				case '1':
					$conditions["CreditsRequest.state"] = [0,1,2];
					break;
				case '2':
					$conditions["CreditsRequest.state"] = [3,5];
					break;
				case '3':
					$conditions["CreditsRequest.state"] = [7];
					break;
			}
			try {
				$this->CreditsRequest->recursive 	= 2;
				$this->Paginator->settings 			= array('order'=>$order);
				$creditsRequests 					= $this->Paginator->paginate(null, $conditions);
			} catch (Exception $e) {
				$creditsRequests = [];
			}

		}else{

			$recursive = 1;
			if(!isset($this->request->query["ini"])){
				$fechaInicioReporte = date("Y-m-d",strtotime("-1 day"));
			}else{
				$fechaInicioReporte = $this->request->query["ini"];
			}
			if(!isset($this->request->query["end"])){
				$fechaFinReporte = date("Y-m-d");
			}else{
				$fechaFinReporte = $this->request->query["end"];
			}

			if (isset($this->request->query["usoFecha"]) && $this->request->query["usoFecha"] == 1 ) {
				$conditions["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;
				$conditions["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

			}
			$usoFecha = isset($this->request->query["usoFecha"]) ? $this->request->query["usoFecha"] : 1;
			$this->set("usoFecha",$usoFecha);

			if(AuthComponent::user("role") == 3){

				try {

					$conditionsNoAdmin = $conditions;
					$conditionsNoAdmin["CreditsRequest.state"] = 0 ;

					$requestsNoAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsNoAdmin,"recursive"=>$recursive, "order" => $order]);
					$this->set("requestsNoAdmin",$requestsNoAdmin);

					$conditionsPendingStop = $conditions;
					//$conditionsPendingStop["CreditsRequest.user_id"]	= 	AuthComponent::user("id");
					$conditionsPendingStop["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsPendingStop["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

					$conditionsPendingStop["CreditsRequest.state"] = isset($this->request->query["tab1"]) && in_array( $this->decrypt($this->request->query["tab1"]), ["1","2"]) ? $this->decrypt($this->request->query["tab1"]) : [1,2] ;

					$requestPendingStop = $this->CreditsRequest->find("all",["conditions"=>$conditionsPendingStop,"recursive"=>$recursive, "order" => $order]);


					$conditionsAdmin = $conditions;
				//	$conditionsAdmin["CreditsRequest.user_id"]	= AuthComponent::user("id");
					$conditionsAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

					$conditionsAdmin["CreditsRequest.state"] 	= isset($this->request->query["tab2"]) && in_array( $this->decrypt($this->request->query["tab2"]), ["3","4"]) ? $this->decrypt($this->request->query["tab2"]) : [3,4] ;

					//var_dump($conditionsAdmin);
				//	$requestAdmin = $this->CreditsRequest->find("all",["conditions"=>array(" CreditsRequest.user_id=842 AND CreditsRequest.state IN (3,4) AND  DATE(CreditsRequest.created) BETWEEN '2021-03-11' AND '2021-03-12' "),"recursive"=>$recursive, "order" => $order]);
				$requestAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsAdmin,"recursive"=>$recursive, "order" => $order]);


					$conditionsApply = $conditions;
					//$conditionsApply["CreditsRequest.user_id"]	= AuthComponent::user("id");
					$conditionsAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsApply["CreditsRequest.state"] 	=  [5,6] ;

					//$requestApply = $this->CreditsRequest->find("all",["conditions"=>$conditionsApply,"recursive"=>$recursive, "order" ]);

					//$requestAdmin = [];
				//	$requestPendingStop = [];
				//	$requestApply = [];

				} catch (Exception $e) {
					$requestAdmin = [];
					$requestPendingStop = [];
					//$requestApply = [];
				}

				$this->set("requestAdmin",$requestAdmin);
				$this->set("requestPendingStop",$requestPendingStop);
				//$this->set("requestApply",$requestApply);


			}elseif (in_array(AuthComponent::user("role") , [1,2,3])) {

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

				if (isset($this->request->query["usoFecha"]) && $this->request->query["usoFecha"] == 1 ) {
					$conditions["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;
					$conditions["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

				}
				$usoFecha = isset($this->request->query["usoFecha"]) ? $this->request->query["usoFecha"] : 1;
				$this->set("usoFecha",$usoFecha);



				$this->set(compact("fechaInicioReporte","fechaFinReporte"));
				try {
					$conditionsNoAdmin = $conditions;
					$conditionsNoAdmin["CreditsRequest.state"] = 0 ;
					$conditionsNoAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsNoAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

					$requestsNoAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsNoAdmin,"recursive"=>$recursive, "order" => $order]);

					$conditionsPendingStop = $conditions;
					$conditionsPendingStop["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsPendingStop["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsPendingStop["OR"] = ["CreditsRequest.state" => isset($this->request->query["tab1"]) && in_array($this->decrypt($this->request->query["tab1"]), [1,2]) ? $this->decrypt($this->request->query["tab1"]) : [1,2] ];

					$requestPendingStop = $this->CreditsRequest->find("all",["conditions"=>$conditionsPendingStop,"recursive"=>$recursive, "order" => $order]);

					$conditionsAdmin = $conditions;
					$conditionsAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsAdmin["OR"] = ["CreditsRequest.state" => isset($this->request->query["tab2"]) && in_array( $this->decrypt($this->request->query["tab2"]), ["3","4"]) ? $this->decrypt($this->request->query["tab2"]) : [3,4] ];

					$requestAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsAdmin,"recursive"=>$recursive, "order" => $order]);

					$conditionsApply = $conditions;
					$conditionsApply["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsApply["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsApply["OR"] = ["CreditsRequest.state" => [5,6] ];

					//$requestApply = $this->CreditsRequest->find("all",["conditions"=>$conditionsApply,"recursive"=>$recursive, "order" => $order]); //  $recursive 'limit' =>

			} catch (Exception $e) {
					$requestsNoAdmin = [];
					$requestAdmin = [];
					$requestPendingStop = [];
					//$requestApply = [];

				}

				$this->set("requestsNoAdmin",$requestsNoAdmin);
				$this->set("requestPendingStop",$requestPendingStop);
				$this->set("requestAdmin",$requestAdmin);
				//$this->set("requestApply",$requestApply);

			}elseif ( in_array(AuthComponent::user("role"), [4,6,7]) ) {


				if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"], [1,2,3,4]) ) ){
					$this->redirect(array("controller"=>"credits_requests","action"=>"index","?"=>["tab"=>1]));
				}else{
					try {
						switch ($this->request->query["tab"]) {
							case '1':
								$states = [0,1,2];
								break;
							case '2':
								$states = [3];
								break;
							case '3':
								$states = [5];
								break;
							case '4':
								$states = [4];
								break;

							default:
								$states = [0,1,2];
								break;
						}

						if(AuthComponent::user("role") == 4){
							$shop_commerce_id = $this->CreditsRequest->ShopCommerce->find("all",["fields"=>["id"],"conditions"=>["ShopCommerce.shop_id"=>AuthComponent::user("shop_id")]]);
							if(!empty($shop_commerce_id)){
								$shop_commerce_id = Set::extract($shop_commerce_id,"{n}.ShopCommerce.id");
							}
						}else{
							$shop_commerce_id = AuthComponent::user("shop_commerce_id");
						}

						if (isset($this->request->query["txt_search"]) && !empty($this->request->query["txt_search"])) {
							$conditions["Customer.identification"] = $this->request->query["txt_search"];
							$this->set("txt_search",$this->request->query["txt_search"]);
						}

						$conditions["CreditsRequest.shop_commerce_id"] = $shop_commerce_id;
						$conditions["CreditsRequest.state"] 		   = $states;

						if($this->request->query["tab"]==2) {
							$conditions["CreditsRequest.extra"] =0;
						}

						if(!isset($this->request->query["ini"])){
							$fechaInicioReporte = date("Y-m-d");
						}else{
							$fechaInicioReporte = $this->request->query["ini"];
						}

						if(!isset($this->request->query["end"])){
							$fechaFinReporte = date("Y-m-d");
						}else{
							$fechaFinReporte = $this->request->query["end"];
						}

						if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {

							if($this->request->query["tab"]==3) {
								$conditions["DATE(CreditsRequest.date_disbursed) >=" ] = $this->request->query["ini"]; //date_disbursed
								$conditions["DATE(CreditsRequest.date_disbursed) <=" ] = $this->request->query["end"];
								$this->set("fechas",true);

							} else {

								$conditions["DATE(CreditsRequest.created) >=" ] = $this->request->query["ini"]; //date_disbursed
								$conditions["DATE(CreditsRequest.created) <=" ] = $this->request->query["end"];
								$this->set("fechas",true);
							}
						}

						$this->Paginator->settings 			= array('order'=>$order);
						$creditsRequestsCommerce 			= $this->Paginator->paginate(null, $conditions);
					} catch (Exception $e) {
						$creditsRequestsCommerce = [];
					}

					$this->CreditsRequest->recursive = -1;
					$this->CreditsRequest->CreditsLine->recursive = -1;
					$activeLine 	= $this->CreditsRequest->CreditsLine->findByState(1);

					$creditLineId = $activeLine["CreditsLine"]["id"];




					$creditLineDetail = $this->CreditsRequest->query("SELECT * FROM credits_lines_details where credit_line_id =".$creditLineId);


					$valorMini = 0;
					$Valormax = 0;
					$minMonth = 0;
					$maxMonth = 0;

					$data = json_encode($creditLineDetail);

					foreach ($creditLineDetail as $key => $value) {
						if ($valorMini == 0) {
							$valorMini = $value["credits_lines_details"]["min_value"];

						} else if ($value["credits_lines_details"]["min_value"] <= $valorMini) {
							$valorMini = $value["credits_lines_details"]["min_value"];

						}

						if ($Valormax == 0) {
							$Valormax = $value["credits_lines_details"]["max_value"];

						} else if ($value["credits_lines_details"]["max_value"] >= $valorMini) {
							$Valormax = $value["credits_lines_details"]["max_value"];

						}

						if ($minMonth == 0) {
							$minMonth = $value["credits_lines_details"]["min_month"];

						} else if ($value["credits_lines_details"]["max_value"] <= $minMonth) {
							$minMonth = $value["credits_lines_details"]["min_month"];

						}

						if ($maxMonth == 0) {
							$maxMonth = $value["credits_lines_details"]["max_month"];

						} else if ($value["credits_lines_details"]["max_month"] >= $maxMonth) {
							$maxMonth = $value["credits_lines_details"]["max_month"];

						}

						/*     if  (($valueCredit>=$value["credits_lines_details"]["min_value"] ) && ($valueCredit <= $value["credits_lines_details"]["max_value"] )) {
					$intRate=$value["credits_lines_details"]["interest_rate"];
					$intOther=$value["credits_lines_details"]["others_rate"];
					}*/

					}


                    $this->set(compact("creditsRequestsCommerce","valorMini", "Valormax", "minMonth", "maxMonth","data"));



				}
			}
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte"));

		$users = $this->CreditsRequest->User->find("list",["conditions" => ["User.role" => [3], "User.state" => 1 ]]);

		$this->set(compact('creditsRequests','requests','users'));
	}


	/*
	public function index() {

		if(AuthComponent::user("role") == 9){
			$this->redirect(["action"=>"cobranza"]);
		}

		if(AuthComponent::user("role") == 11){
			$this->redirect(["action"=>"juridico"]);
		}

 		if(AuthComponent::user("role") == 10){
   			$this->redirect(["controller"=>"customers_codes","action" => "index","?"=>["tab" => 1]]);
  		}

 		if(AuthComponent::user("role") == 12){
   			$this->redirect(["controller"=>"customers_codes","action" => "index","?"=>["tab" => 1]]);
  		}



		$conditions = $this->CreditsRequest->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$order = array('CreditsRequest.created'=>'DESC');

		if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
			$conditions["Customer.identification"] = $this->request->query['ccCustomer'];
           //echo $conditions ;
			$this->Set("ccCustomer",$this->request->query['ccCustomer.identification']);
		}

		if(isset($this->request->query['idrequest']) && !empty($this->request->query['idrequest']) ){
			$conditions["CreditsRequest.id"] = $this->request->query['idrequest'];
			$this->Set("idrequest",$this->request->query['idrequest']);
		}


		$fechaInicioReporte = date("Y-m-d");
		$fechaFinReporte = date("Y-m-d");


		if(isset($this->request->query['commerce']) && !empty($this->request->query['commerce']) ){
			$this->loadModel("Shop");
			$shopCommerce 	= $this->ShopCommerce->findByCode($this->request->query['commerce']);
			if(!empty($shopCommerce)){
				$conditions["CreditsRequest.shop_commerce_id"] = $shopCommerce["ShopCommerce"]["id"];
			}else{
				$conditions["CreditsRequest.shop_commerce_id"] = null;
			}
			$this->Set("commerce",$this->request->query['commerce']);
		}

		if(AuthComponent::user("role") == 5){
			if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"], [1,2,3]) ) ){
				$this->redirect(array("controller"=>"credits_requests","action"=>"index","?"=>["tab"=>1]));
			}
			$conditions["CreditsRequest.customer_id"] = AuthComponent::user("customer_id");

			switch ($this->request->query["tab"]) {
				case '1':
					$conditions["CreditsRequest.state"] = [0,1,2];
					break;
				case '2':
					$conditions["CreditsRequest.state"] = [3,5];
					break;
				case '3':
					$conditions["CreditsRequest.state"] = [7];
					break;
			}
			try {
				$this->CreditsRequest->recursive 	= 2;
				$this->Paginator->settings 			= array('order'=>$order);
				$creditsRequests 					= $this->Paginator->paginate(null, $conditions);
			} catch (Exception $e) {
				$creditsRequests = [];
			}

		}else{
			$recursive = 2;
			if(!isset($this->request->query["ini"])){
				$fechaInicioReporte = date("Y-m-d",strtotime("-1 day"));
			}else{
				$fechaInicioReporte = $this->request->query["ini"];
			}
			if(!isset($this->request->query["end"])){
				$fechaFinReporte = date("Y-m-d");
			}else{
				$fechaFinReporte = $this->request->query["end"];
			}

			if (isset($this->request->query["usoFecha"]) && $this->request->query["usoFecha"] == 1 ) {
				$conditions["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;
				$conditions["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

			}
			$usoFecha = isset($this->request->query["usoFecha"]) ? $this->request->query["usoFecha"] : 1;
			$this->set("usoFecha",$usoFecha);

			if(AuthComponent::user("role") == 3){

				try {



					$conditionsNoAdmin = $conditions;
					$conditionsNoAdmin["CreditsRequest.state"] = 0 ;


					$requestsNoAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsNoAdmin,"recursive"=>$recursive, "order" => $order]);
					$this->set("requestsNoAdmin",$requestsNoAdmin);

					$conditionsPendingStop = $conditions;
					//$conditionsPendingStop["CreditsRequest.user_id"]	= 	AuthComponent::user("id");
					$conditionsPendingStop["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsPendingStop["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;


					$conditionsPendingStop["CreditsRequest.state"] = isset($this->request->query["tab1"]) && in_array( $this->decrypt($this->request->query["tab1"]), ["1","2"]) ? $this->decrypt($this->request->query["tab1"]) : [1,2] ;

					$requestPendingStop = $this->CreditsRequest->find("all",["conditions"=>$conditionsPendingStop,"recursive"=>$recursive, "order" => $order]);


					$conditionsAdmin = $conditions;
				//	$conditionsAdmin["CreditsRequest.user_id"]	= AuthComponent::user("id");
					$conditionsAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

					$conditionsAdmin["CreditsRequest.state"] 	= isset($this->request->query["tab2"]) && in_array( $this->decrypt($this->request->query["tab2"]), ["3","4"]) ? $this->decrypt($this->request->query["tab2"]) : [3,4] ;

					//var_dump($conditionsAdmin);
				//	$requestAdmin = $this->CreditsRequest->find("all",["conditions"=>array(" CreditsRequest.user_id=842 AND CreditsRequest.state IN (3,4) AND  DATE(CreditsRequest.created) BETWEEN '2021-03-11' AND '2021-03-12' "),"recursive"=>$recursive, "order" => $order]);
				$requestAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsAdmin,"recursive"=>$recursive, "order" => $order]);


					$conditionsApply = $conditions;
					//$conditionsApply["CreditsRequest.user_id"]	= AuthComponent::user("id");
					$conditionsAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsApply["CreditsRequest.state"] 	=  [5,6] ;

					$requestApply = $this->CreditsRequest->find("all",["conditions"=>$conditionsApply,"recursive"=>$recursive, "order" ]);

					//$requestAdmin = [];
				//	$requestPendingStop = [];
				//	$requestApply = [];

				} catch (Exception $e) {
					$requestAdmin = [];
					$requestPendingStop = [];
					$requestApply = [];
				}

				$this->set("requestAdmin",$requestAdmin);
				$this->set("requestPendingStop",$requestPendingStop);
				$this->set("requestApply",$requestApply);


			}elseif (in_array(AuthComponent::user("role") , [1,2,3])) {

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

				if (isset($this->request->query["usoFecha"]) && $this->request->query["usoFecha"] == 1 ) {
					$conditions["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;
					$conditions["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

				}
				$usoFecha = isset($this->request->query["usoFecha"]) ? $this->request->query["usoFecha"] : 1;
				$this->set("usoFecha",$usoFecha);



				$this->set(compact("fechaInicioReporte","fechaFinReporte"));
				try {
					$conditionsNoAdmin = $conditions;
					$conditionsNoAdmin["CreditsRequest.state"] = 0 ;
					$conditionsNoAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsNoAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;

					$requestsNoAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsNoAdmin,"recursive"=>$recursive, "order" => $order]);

					$conditionsPendingStop = $conditions;
					$conditionsPendingStop["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsPendingStop["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsPendingStop["OR"] = ["CreditsRequest.state" => isset($this->request->query["tab1"]) && in_array($this->decrypt($this->request->query["tab1"]), [1,2]) ? $this->decrypt($this->request->query["tab1"]) : [1,2] ];

					$requestPendingStop = $this->CreditsRequest->find("all",["conditions"=>$conditionsPendingStop,"recursive"=>$recursive, "order" => $order]);

					$conditionsAdmin = $conditions;
					$conditionsAdmin["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsAdmin["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsAdmin["OR"] = ["CreditsRequest.state" => isset($this->request->query["tab2"]) && in_array( $this->decrypt($this->request->query["tab2"]), ["3","4"]) ? $this->decrypt($this->request->query["tab2"]) : [3,4] ];

					$requestAdmin = $this->CreditsRequest->find("all",["conditions"=>$conditionsAdmin,"recursive"=>$recursive, "order" => $order]);

					$conditionsApply = $conditions;
					$conditionsApply["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;//'2021-03-11';
					$conditionsApply["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
					$conditionsApply["OR"] = ["CreditsRequest.state" => [5,6] ];

					$requestApply = $this->CreditsRequest->find("all",["conditions"=>$conditionsApply,"recursive"=>$recursive, "order" => $order]); //  $recursive 'limit' =>

			} catch (Exception $e) {
					$requestsNoAdmin = [];
					$requestAdmin = [];
					$requestPendingStop = [];
					$requestApply = [];
				}

				$this->set("requestsNoAdmin",$requestsNoAdmin);
				$this->set("requestPendingStop",$requestPendingStop);
				$this->set("requestAdmin",$requestAdmin);
				$this->set("requestApply",$requestApply);

			}elseif ( in_array(AuthComponent::user("role"), [4,6,7]) ) {
				if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"], [1,2,3,4]) ) ){
					$this->redirect(array("controller"=>"credits_requests","action"=>"index","?"=>["tab"=>1]));
				}else{
					try {
						switch ($this->request->query["tab"]) {
							case '1':
								$states = [0,1,2];
								break;
							case '2':
								$states = [3];
								break;
							case '3':
								$states = [5];
								break;
							case '4':
								$states = [4];
								break;

							default:
								$states = [0,1,2];
								break;
						}

						if(AuthComponent::user("role") == 4){
							$shop_commerce_id = $this->CreditsRequest->ShopCommerce->find("all",["fields"=>["id"],"conditions"=>["ShopCommerce.shop_id"=>AuthComponent::user("shop_id")]]);
							if(!empty($shop_commerce_id)){
								$shop_commerce_id = Set::extract($shop_commerce_id,"{n}.ShopCommerce.id");
							}
						}else{
							$shop_commerce_id = AuthComponent::user("shop_commerce_id");
						}

						if (isset($this->request->query["txt_search"]) && !empty($this->request->query["txt_search"])) {
							$conditions["Customer.identification"] = $this->request->query["txt_search"];
							$this->set("txt_search",$this->request->query["txt_search"]);
						}

						$conditions["CreditsRequest.shop_commerce_id"] = $shop_commerce_id;
						$conditions["CreditsRequest.state"] 		   = $states;


						if(!isset($this->request->query["ini"])){
							$fechaInicioReporte = date("Y-m-d");
						}else{
							$fechaInicioReporte = $this->request->query["ini"];
						}

						if(!isset($this->request->query["end"])){
							$fechaFinReporte = date("Y-m-d");
						}else{
							$fechaFinReporte = $this->request->query["end"];
						}

						if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {

							if($this->request->query["tab"]==3) {
								$conditions["DATE(CreditsRequest.date_disbursed) >=" ] = $this->request->query["ini"]; //date_disbursed
								$conditions["DATE(CreditsRequest.date_disbursed) <=" ] = $this->request->query["end"];
								$this->set("fechas",true);

							} else {

								$conditions["DATE(CreditsRequest.created) >=" ] = $this->request->query["ini"]; //date_disbursed
								$conditions["DATE(CreditsRequest.created) <=" ] = $this->request->query["end"];
								$this->set("fechas",true);
							}
						}

						$this->Paginator->settings 			= array('order'=>$order);
						$creditsRequestsCommerce 			= $this->Paginator->paginate(null, $conditions);
					} catch (Exception $e) {
						$creditsRequestsCommerce = [];
					}

					$this->CreditsRequest->recursive = -1;
					$this->CreditsRequest->CreditsLine->recursive = -1;
					$activeLine 	= $this->CreditsRequest->CreditsLine->findByState(1);

					$creditLineId = $activeLine["CreditsLine"]["id"];




					$creditLineDetail = $this->CreditsRequest->query("SELECT * FROM credits_lines_details where credit_line_id =".$creditLineId);


					$valorMini = 0;
					$Valormax = 0;
					$minMonth = 0;
					$maxMonth = 0;

					$data = json_encode($creditLineDetail);

					foreach ($creditLineDetail as $key => $value) {
						if ($valorMini == 0) {
							$valorMini = $value["credits_lines_details"]["min_value"];

						} else if ($value["credits_lines_details"]["min_value"] <= $valorMini) {
							$valorMini = $value["credits_lines_details"]["min_value"];

						}

						if ($Valormax == 0) {
							$Valormax = $value["credits_lines_details"]["max_value"];

						} else if ($value["credits_lines_details"]["max_value"] >= $valorMini) {
							$Valormax = $value["credits_lines_details"]["max_value"];

						}

						if ($minMonth == 0) {
							$minMonth = $value["credits_lines_details"]["min_month"];

						} else if ($value["credits_lines_details"]["max_value"] <= $minMonth) {
							$minMonth = $value["credits_lines_details"]["min_month"];

						}

						if ($maxMonth == 0) {
							$maxMonth = $value["credits_lines_details"]["max_month"];

						} else if ($value["credits_lines_details"]["max_month"] >= $maxMonth) {
							$maxMonth = $value["credits_lines_details"]["max_month"];

						}

						    if  (($valueCredit>=$value["credits_lines_details"]["min_value"] ) && ($valueCredit <= $value["credits_lines_details"]["max_value"] )) {
					$intRate=$value["credits_lines_details"]["interest_rate"];
					$intOther=$value["credits_lines_details"]["others_rate"];
					}

					}


                    $this->set(compact("creditsRequestsCommerce","valorMini", "Valormax", "minMonth", "maxMonth","data"));



				}
			}
		}

		$this->set(compact("fechaInicioReporte","fechaFinReporte"));

		$users = $this->CreditsRequest->User->find("list",["conditions" => ["User.role" => [3], "User.state" => 1 ]]);

		$this->set(compact('creditsRequests','requests','users'));
	}*/

	public function index_lista(){

		$conditions = ["CreditsRequest.state !=" => 7];

		if(!isset($this->request->query["ini"])){
			$fechaInicioReporte = date("Y-m-d");
		}else{
			$fechaInicioReporte = $this->request->query["ini"];
		}

		if(!isset($this->request->query["end"])){
			$fechaFinReporte = date("Y-m-d");
		}else{
			$fechaFinReporte = $this->request->query["end"];
		}

		if (isset($this->request->query["ini"]) && $this->request->query["end"] ) {
			$conditions["DATE(CreditsRequest.created) >=" ] = $fechaInicioReporte;
			$conditions["DATE(CreditsRequest.created) <=" ] = $fechaFinReporte;
			$this->set("fechas",true);

		}
		$usoFecha = isset($this->request->query["usoFecha"]) ? $this->request->query["usoFecha"] : 1;
		$this->set("usoFecha",$usoFecha);

		$this->set(compact("fechaInicioReporte","fechaFinReporte"));

		if(isset($this->request->query['idrequest']) && !empty($this->request->query['idrequest']) ){
			$conditions["CreditsRequest.id"] = $this->request->query['idrequest'];
			$this->Set("idrequest",$this->request->query['idrequest']);
		}

		if(isset($this->request->query['ccCustomer']) && !empty($this->request->query['ccCustomer']) ){
			$conditions["Customer.identification"] = $this->request->query['ccCustomer'];
			$this->Set("ccCustomer",$this->request->query['ccCustomer']);
		}

		if(isset($this->request->query['state']) && !empty($this->request->query['state']) ){
			$conditions["CreditsRequest.state"] = $this->request->query['state'];
			$this->Set("state",$this->request->query['state']);
		}

		if(isset($this->request->query['n_obligacion']) && !empty($this->request->query['n_obligacion']) ){
			$conditions["CreditsRequest.code_pay"] = $this->request->query['n_obligacion'];
			$this->Set("n_obligacion",$this->request->query['n_obligacion']);
		}

		if(isset($this->request->query['commerce']) && !empty($this->request->query['commerce']) ){
			$this->loadModel("ShopCommerce");
			$shopCommerce 	= $this->ShopCommerce->findByCode($this->request->query['commerce']);
			if(!empty($shopCommerce)){
				$conditions["CreditsRequest.shop_commerce_id"] = $shopCommerce["ShopCommerce"]["id"];
			}else{
				$conditions["CreditsRequest.shop_commerce_id"] = null;
			}
			$this->Set("commerce",$this->request->query['commerce']);
		}

		/*$conditions = [
			'CreditsRequest.REQUEST_TYPE' => '1',
		];*/

		if (!isset($this->request->query["excel_data"])) {
			$this->paginate = [
				'order'=> ['CreditsRequest.id'=>'DESC'],
				'limit' => 20,
				'conditions' => $conditions,
			];

			$this->CreditsRequest->recursive = 1;
			$creditsRequest = $this->paginate('CreditsRequest');
		}else{
			$this->autoRender = false;
			$creditRequest = $this->CreditsRequest->find("all",["conditions"=>$conditions,"order"=>["CreditsRequest.id" => "DESC"]]);

			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

	        $spreadsheet->getProperties()->setCreator('CREDISHOP')
	            ->setLastModifiedBy('CREDISHOP')
	            ->setTitle('Solicitudes de créditos')
	            ->setSubject('Solicitudes de créditos')
	            ->setDescription('Solicitudes de créditos Zíro')
	            ->setKeywords('Solicitudes de créditos')
	            ->setCategory('Solicitudes de créditos');

	        $spreadsheet->setActiveSheetIndex(0)
				->setCellValue('A1', 'Código')
	            ->setCellValue('B1', 'Fecha de solicitud')
	            ->setCellValue('C1', 'Tipo de solicitud')
	            ->setCellValue('D1', '# de Obligación')
	            ->setCellValue('E1', 'Cliente')
	            ->setCellValue('F1', 'CC')
	            ->setCellValue('G1', 'Celular')
	            ->setCellValue('H1', 'Email')
	            ->setCellValue('I1', 'Analista')
	            ->setCellValue('J1', 'Proveedor')
	            ->setCellValue('K1', 'Valor solicitado')
	            ->setCellValue('L1', 'Valor aprobado')
	            ->setCellValue('M1', 'Valor retirado')
	            ->setCellValue('N1', 'Valor disponible')
	            ->setCellValue('O1', 'Estado')
	            ->setCellValue('P1', 'Motivo de negación')
	            ->setCellValue('Q1', 'Fecha de aprobación/Rechazo')
	            ->setCellValue('R1', 'Fecha de desembolso')
	            ->setCellValue('S1', 'Valor aprobado')
				->setCellValue('T1', 'Valor retirado');


	        if (!empty($creditRequest)) {
	            $i = 2;
	            foreach ($creditRequest as $key => $value) {
	            	$tipo = "Online";
	            	if (empty($value["Customer"]["email"])) { $tipo = "Solicitud"; }
	            	elseif ($value["CreditsRequest"]["shop_commerce_id"] == 0) { $tipo = "Empresa"; }

	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value['CreditsRequest']['id']);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value['CreditsRequest']['created']);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $tipo);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["CreditsRequest"]["code_pay"] );
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["Customer"]["name"]." ".$value["Customer"]["last_name"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["Customer"]["identification"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["Customer"]["celular"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $value["Customer"]["email"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, isset($value['User']['name']) ? $value['User']['name'] : "");
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, !empty($value["CreditsRequest"]["empresa_id"]) ? $value["Empresa"]["social_reason"] : $value["ShopCommerce"]["shop_name"]." ".$value["ShopCommerce"]["name"] );
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["CreditsRequest"]["request_value"] . " x ".$value["CreditsRequest"]["request_number"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('L' . $i, $value["CreditsRequest"]["value_approve"] . " x ".$value["CreditsRequest"]["number_approve"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('M' . $i, $value["CreditsRequest"]["value_disbursed"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('N' . $i, $value["CreditsRequest"]["value_approve"]-$value["CreditsRequest"]["value_disbursed"]);


	                switch ($value['CreditsRequest']['state']) {
						case '0':
							$estado = "Solicitud";
							break;
						case '1':
						case '2':
							$estado = "Estudio";
							break;
						case '3':
							if (!empty($value["CreditsRequest"]["empresa_id"])) {
								$estado = "Decidido empresa";
							}else{
								$estado = "Aprobado sin desembolsar";
							}

							break;
						case '4':
							if (!empty($value["CreditsRequest"]["empresa_id"])) {
								$estado = "Decidido empresa";
							}else{
								$estado = "Rechazado";
							}
							break;
						case '5':
							$estado = 'Aprobado con desembolso';
							break;
						case '7':
							$estado = "Cancelado por solicitud nueva";
							break;
					}

	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('O' . $i, $estado);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('P' . $i, $value["CreditsRequest"]["reason_reject"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('Q' . $i, $value["CreditsRequest"]["date_admin"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('R' . $i, $value["CreditsRequest"]["date_disbursed"]);
					$spreadsheet->setActiveSheetIndex(0)->setCellValue('S' . $i, $value["CreditsRequest"]["value_approve"]);
	                $spreadsheet->setActiveSheetIndex(0)->setCellValue('T' . $i, $value["CreditsRequest"]["value_disbursed"]);
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
	        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
	        $spreadsheet->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

	        $spreadsheet->getActiveSheet()->setTitle('Solicitudes de créditos');
	        $spreadsheet->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
	        //$spreadsheet->getActiveSheet()->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

	        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
	        $name = "files/solicitudes_credito_" . time() . ".xls";
	        $writer->save($name);

	        $url = Router::url("/", true) . $name;
	        $this->redirect($url);

			var_dump($creditRequest);
			die;
		}


		$activeLine 	= $this->CreditsRequest->CreditsLine->findByState(1);

		$creditLineId = $activeLine["CreditsLine"]["id"];




		$creditLineDetail = $this->CreditsRequest->query("SELECT * FROM credits_lines_details where credit_line_id =".$creditLineId);


		$valorMini = 0;
		$Valormax = 0;
		$minMonth = 0;
		$maxMonth = 0;

		$data = json_encode($creditLineDetail);

		$this->set(compact("fechaInicioReporte","fechaFinReporte"));
		$this->set('creditsRequest', $creditsRequest);

		$this->set(compact('index_lista','requests','data','valorMini','Valormax','minMonth','maxMonth'));

	}

	public function assing_user(){
		$this->autoRender= false;
		$request = $this->decrypt($this->request->data["request"]);
		$user_id = $this->request->data["user_id"];

		$request = $this->CreditsRequest->find("first",["recursive" => -1, "conditions"=> ["CreditsRequest.id"=> $request] ]);

		$request["CreditsRequest"]["state"] = 1;
		$request["CreditsRequest"]["user_id"] = $user_id;

		$this->CreditsRequest->save($request);
		$this->Session->setFlash(__('El Crédito fue asignado correctamente.'), 'flash_success');
	}

	public function juridico(){

		if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"],[1,2] ) ) ){
			$this->redirect(["controller" => "credits_requests","action"=>"juridico","?" => ["tab" => 1]]);
		}else{
			$tab = $this->request->query["tab"];
			if($tab == 2){
				if(isset($this->request->query["q"])){
					$customer = $this->request->query["q"];
				}else{
					$customer = null;
				}

				$conditions = [ "CreditsPlan.state" => 0, "Credit.credits_request_id !=" => 0, "Credit.juridico" => 1, ];

				if(!is_null($customer) && !empty($customer) ){
					$conditions["Customer.identification"] = $customer;
				}

				$this->loadModel("CreditsPlan");
				$this->CreditsPlan->update_cuotes_days();

				$this->Paginator->settings = [
					"fields" => ['CreditsPlan.id','CreditsPlan.deadline','CreditsPlan.date_debt','CreditsPlan.credit_id','days AS dias',' Customer.*'],
                    "joins"  => [
                        ['table' => 'credits','alias' => 'Credit','type' => 'INNER','conditions' => array('Credit.id = CreditsPlan.credit_id')],
                        ['table' => 'customers','alias' => 'Customer','type' => 'INNER','conditions' => array('Customer.id = Credit.customer_id')],
                    ],
                    "conditions" => $conditions,
                    "recursive"  => -1,
                    "limit" 	 => 20,
                ];
                $datos = $this->Paginator->paginate("CreditsPlan");

                if (!empty($datos)) {
                	foreach ($datos as $key => $value) {
						$datosCuotas = $this->CreditsPlan->getCuotesInformation($value["CreditsPlan"]["credit_id"],$value["CreditsPlan"]["id"], null, 1);
						$datos[$key] = array_merge($datos[$key],$datosCuotas);
					}
                }

				$datosCuotas = [];

				if(!empty($datos)){
					foreach ($datos as $key => $value) {
						$datosCuotas[ trim($value["Credit"]["credits_request_id"]."@@".$value["Customer"]["identification"]."@@".trim($value["Customer"]["name"]). " ". trim($value["Customer"]["last_name"])) ][] = $value;
					}
				}

				$this->set("datosCuotas",$datosCuotas);
			}else{

				if (date("D")=="Mon"){
				     $week_start = date("Y-m-d");
				} else {
				     $week_start = date("Y-m-d", strtotime('last Monday', time()));
				}
				$week_end = strtotime('next Sunday', time());
				$week_end = date('Y-m-d', $week_end);

				$this->loadModel("Commitment");

				$conditions  = ["Commitment.deadline" => date("Y-m-d")];
				$conditions1 = ["DATE(Commitment.deadline) >=" => $week_start, "DATE(Commitment.deadline) <=" => $week_end ];
				$conditions2 = ["DATE(Commitment.deadline) <" => date("Y-m-d"), "Commitment.state" => 0 ];

				if(isset($this->request->query["user"])){
					$conditions["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
					$conditions1["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
					$conditions2["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
				}

				$type = 1;

				$conditions["Commitment.type"]  = $type;
				$conditions1["Commitment.type"] = $type;
				$conditions2["Commitment.type"] = $type;

				$commitmentsToday 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions ]);
				$commitmentsWeek 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions1 ]);
				//$commitmentsNoAdmin = $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions2  ]);

				$users = $this->Commitment->User->findAllByRole(9);

				// we prepare our query, the cakephp way!
				$this->paginate = array(
					'conditions' => $conditions2,
					'limit' => 20,
					'order' => array('Commitment.id' => 'desc')
				);
				$this->Commitment->recursive = 3;

				// we are using the 'Commitment' model
				$commitmentsNoAdmin = $this->paginate('Commitment');

				$this->set(compact("commitmentsToday","commitmentsWeek","commitmentsNoAdmin","users"));
			}

			$this->set("tab",$tab);
		}
	}
	public function customerInformation($commitments){
		foreach ($commitments as $key => $value) {
			$commitments[$key]["CustomersPhone"]["phone_number"] = $this->phones($value["Customer"]["id"]);
			$commitments[$key]["CustomersAddress"]["address"] = $this->addresses($value["Customer"]["id"]);
		}
		return $commitments;
	}
	public function cobranza(){

		if (AuthComponent::user("role") == 11) {
			$this->redirect(["action"=>"juridico"]);
		}

		$page = !isset($this->request->query["page"]) ? 1 : $this->request->query["page"];
		$pages = 1;

		if(!isset($this->request->query["range"])){
			$iniDay = 1;
			$endDay = 15;//200

		}else{
			$days = explode(";",$this->request->query["range"]);
			if(count($days) != 2 || $days[0] < 1 || $days[0] > 200 || $days[1] < 1 || $days[1] > 200 ){
				$iniDay = 1;
				$endDay = 15;
			}else{
				$iniDay = $days[0];
				$endDay = $days[1];
			}
		}
		$this->set("iniDay",$iniDay);
		$this->set("endDay",$endDay);

		if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"],[1,2] ) ) ){
			$this->redirect(["controller" => "credits_requests","action"=>"cobranza","?" => ["tab" => 1]]);
		}else{
			$tab = $this->request->query["tab"];
			if($tab == 2){
				if(isset($this->request->query["q"])){
					$customer = $this->request->query["q"];
				}else{
					$customer = null;
				}

				if(isset($this->request->query["user"]) && !empty($this->request->query["user"])){
					$user = $this->request->query["user"];
				}else{
					$user = null;
				}

				if(isset($this->request->query["commerce"]) && !empty($this->request->query["commerce"])){
					$commerce = $this->request->query["commerce"];
					$this->set("commerce",$commerce);
				}else{
					$commerce = null;
				}

				$limit = 50;
				$start = ($page - 1) * $limit;

				$datosCuotas = $this->CreditsRequest->Credit->CreditsPlan->getQuotesCobranzas(null,$iniDay,$endDay,$customer,$user,$commerce,$start,$limit);

				if (isset($this->request->query["excel_data"])) {

					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

			        $spreadsheet->getProperties()->setCreator('CREDIMUNDO')
			            ->setLastModifiedBy('CREDIMUNDO')
			            ->setTitle('Gestión de cobranza')
			            ->setSubject('Gestión de cobranza')
			            ->setDescription('Gestión de cobranza CREDIMUNDO')
			            ->setKeywords('Gestión de cobranza')
			            ->setCategory('Gestión de cobranza');

			        $spreadsheet->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Crédito')
			            ->setCellValue('B1', 'Cliente')
			            ->setCellValue('C1', 'Cédula')
			            ->setCellValue('D1', 'Días mora')
			            ->setCellValue('E1', 'Valor cuota')
			            ->setCellValue('F1', 'Intereses')
			            ->setCellValue('G1', 'Saldo cuota')
			            ->setCellValue('H1', 'Saldo crédito')
			            ->setCellValue('I1', 'Última gestión')
			            ->setCellValue('J1', 'Teléfono Cliente')
			            ->setCellValue('K1', 'Dirección Cliente');

					if (!empty($datosCuotas)) {
			            $i = 2;
			            $creditsData = [];
			            foreach ($datosCuotas as $key => $value) {
			            	if (!in_array($value["Credit"]["credits_request_id"],$creditsData)){
			            		$creditsData[] = $value["Credit"]["credits_request_id"];
			            	}else{
			            		continue;
			            	}
			            	$customersAddress = $this->CreditsRequest->Customer->CustomersAddress->find("first",["recursive" => -1, "conditions" => ["CustomersAddress.customer_id" => $value["Customer"]["id"]]  ]);
			            	$value["CustomersAddress"] = $customersAddress["CustomersAddress"];

			            	$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["Credit"]["code_pay"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Customer"]["name"]. " " .$value["Customer"]["last_name"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["Customer"]["identification"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["0"]["Credit__dias"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, $value["Credit"]["quota_value"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"]);

			                $totalDeuda = $value["CreditsPlan"]["debt_value"]+$value["CreditsPlan"]["debt_honor"] + ($value["CreditsPlan"]["capital_value"]-$value["CreditsPlan"]["capital_payment"]) + ($value["CreditsPlan"]["interest_value"]-$value["CreditsPlan"]["interest_payment"]) + ($value["CreditsPlan"]["others_value"]-$value["CreditsPlan"]["others_payment"]);

			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $totalDeuda);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $value["Credit"]["value_pending"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["User"]["name"]. " ".is_null($value["Credit"]["admin_date"]) ? "" : " / ".date("d-m-Y H:i:A",strtotime($value["Credit"]["admin_date"])));
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('J' . $i, $value["Customer"]["phone"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('K' . $i, $value["CustomersAddress"]["address_city"]. " - ".$value["CustomersAddress"]["address"]." - ".$value["CustomersAddress"]["address_street"]);
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
			        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

			        $spreadsheet->getActiveSheet()->setTitle('Compromisos');
        			$spreadsheet->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

					$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			        $name = "files/gestion_cobranzas_" . time() . ".xlsx";
			        $writer->save($name);

			        $url = Router::url("/", true) . $name;
			        $this->redirect($url);
				}

				$total = $this->CreditsRequest->Credit->CreditsPlan->getQuotesCobranzasCount(null,$iniDay,$endDay,$customer,$user,$commerce);
				$pages = ceil( $total / $limit );

				$dataPhone = [];
				$existsCustomer = [];

				if (!empty($datosCuotas)) {
					foreach ($datosCuotas as $key => $value) {
						if (!in_array($value["Customer"]["id"], $existsCustomer)) {
							$existsCustomer[] = $value["Customer"]["id"];
							$dataPhone[$value["CreditsPlan"]["id"]] = $value["Customer"]["id"];
							$datosCuotas[$key]["capital_restante"] = $this->CreditsRequest->Credit->CreditsPlan->getCreditDeuda($value["Credit"]["id"],null,null,true);
						}else{
							unset($datosCuotas[$key]);
						}
					}
				}

				$this->loadModel("User");
				$users = $this->User->findAllByRole(9);

				$this->set("users",$users);
				$this->set("dataPhone",$dataPhone);
				$this->set("datosCuotas",$datosCuotas);

			}else{

				$query = $this->request->query;
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

		        if (isset($this->request->query["ini"]) && isset($this->request->query["end"])) {
		        	$fechas = true;
		        	$this->set("fechas",$fechas);
		        }

				$this->loadModel("Commitment");

				$conditions = [];

				$conditions["DATE(Commitment.deadline) >="] = $fechaInicioReporte;
				$conditions["DATE(Commitment.deadline) <="] = $fechaFinReporte;

				if (isset($query["user"]) && !empty($query["user"])) {
					$conditions["Commitment.user_id"] = $query["user"];
					$this->set("user",$query["user"]);
				}

				if (isset($query["commerce"]) && !empty($query["commerce"])) {
					$conditions["ShopCommerce.code"] = $query["commerce"];
					$this->set("commerce",$query["commerce"]);
				}
				if (isset($query["ccCustomer"]) && !empty($query["ccCustomer"])) {
					$conditions["Customer.identification"] = $query["ccCustomer"];
					$this->set("ccCustomer",$query["ccCustomer"]);
				}

				if (isset($query["state"]) && ($query["state"]) != "") {
					$conditions["Commitment.state"] = $query["state"];
					$this->set("state_comp",$query["state"]);
				}else{
					$conditions["Commitment.state"] = [0,2];
				}

				$type = AuthComponent::user("role") == 11 ? 1 : 0;

				$conditions["Commitment.type"]  = $type;
				$conditions["Credit.juridico"]  = 0;

				$joins = [
					["table"=>"credits_plans","alias"=>"CreditsPlan","type"=>"INNER","conditions"=>["CreditsPlan.id = Commitment.credits_plan_id"]],
					["table"=>"users","alias"=>"User","type"=>"INNER","conditions"=>["User.id = Commitment.user_id"]],
					["table"=>"credits","alias"=>"Credit","type"=>"INNER","conditions"=>["Credit.id = CreditsPlan.credit_id"]],
					["table"=>"credits_requests","alias"=>"CreditsRequest","type"=>"INNER","conditions"=>["CreditsRequest.id = Credit.credits_request_id"]],
					["table"=>"customers","alias"=>"Customer","type"=>"INNER","conditions"=>["Customer.id = CreditsRequest.customer_id"]],
					["table"=>"customers_phones","alias"=>"CustomersPhone","type"=>"INNER","conditions"=>["Customer.id = CustomersPhone.customer_id"]],
					["table"=>"customers_addresses","alias"=>"CustomersAddress","type"=>"INNER","conditions"=>["Customer.id = CustomersAddress.customer_id"]],
					["table"=>"shop_commerces","alias"=>"ShopCommerce","type"=>"INNER","conditions"=>["ShopCommerce.id = CreditsRequest.shop_commerce_id"]],
					["table"=>"shops","alias"=>"Shop","type"=>"INNER","conditions"=>["Shop.id = ShopCommerce.shop_id"]],
				];

				$options = ["conditions" => $conditions, "joins" => $joins, "group" => "Commitment.id", "recursive" => -1, "fields" => ["Commitment.*","CreditsRequest.id","CreditsRequest.code_pay","Customer.name", "Customer.last_name","Customer.identification","User.name","Credit.id","CreditsPlan.*","CustomersPhone.*","CustomersAddress.*"],"group" => ["Commitment.id"] ];

				if (!isset($query["excel_data"])) {
					$this->Paginator->settings = $options;
            		$commitments 			   = $this->Paginator->paginate($this->Commitment);
				}else{
					$commitments = $this->Commitment->find("all",$options);

					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

			        $spreadsheet->getProperties()->setCreator('CREDIMUNDO')
			            ->setLastModifiedBy('CREDIMUNDO')
			            ->setTitle('Compromisos cobranza')
			            ->setSubject('Compromisos cobranza')
			            ->setDescription('Compromisos cobranza CREDIMUNDO')
			            ->setKeywords('Compromisos cobranza')
			            ->setCategory('Compromisos cobranza');

			        $spreadsheet->setActiveSheetIndex(0)
			            ->setCellValue('A1', 'Crédito')
			            ->setCellValue('B1', 'Cliente')
			            ->setCellValue('C1', 'Cédula')
			            ->setCellValue('D1', 'Detalle Compromiso')
			            ->setCellValue('E1', 'F.Creación')
			            ->setCellValue('F1', 'F. Límite')
			            ->setCellValue('G1', 'Gestiona')
			            ->setCellValue('H1', 'Teléfono cliente')
			            ->setCellValue('I1', 'Dirección cliente');

			        if (!empty($commitments)) {
			            $i = 2;
			            foreach ($commitments as $key => $value) {
			            	$spreadsheet->setActiveSheetIndex(0)->setCellValue('A' . $i, $value["CreditsRequest"]["code_pay"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('B' . $i, $value["Customer"]["name"]. " " .$value["Customer"]["last_name"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('C' . $i, $value["Customer"]["identification"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('D' . $i, $value["Commitment"]["commitment"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('E' . $i, date("d-m-Y",strtotime($value["Commitment"]["created"])));
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('F' . $i, date("d-m-Y",strtotime($value["Commitment"]["deadline"])));
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('G' . $i, $value["User"]["name"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('H' . $i, $value["CustomersPhone"]["phone_number"]);
			                $spreadsheet->setActiveSheetIndex(0)->setCellValue('I' . $i, $value["CustomersAddress"]["address_city"]. " - ".$value["CustomersAddress"]["address"]." - ".$value["CustomersAddress"]["address_street"]);
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

			        $spreadsheet->getActiveSheet()->setTitle('Compromisos');
        			$spreadsheet->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

					$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			        $name = "files/compromisos_" . time() . ".xlsx";
			        $writer->save($name);

			        $url = Router::url("/", true) . $name;
			        $this->redirect($url);
				}

				$users = $this->Commitment->User->findAllByRole(9);

				$this->set(compact("commitments","fechaInicioReporte","fechaFinReporte","users"));
			}

			$this->set("pages",$pages);
			$this->set("page",$page);
			$this->set("tab",$tab);
		}

		$this->loadModel("ShopCommerce");
	    $commerces = $this->ShopCommerce->find("all",["conditions"=>["ShopCommerce.state"=>1],"recursive" => -1]);

	    $list = [];

	    if (!empty($commerces)) {
	        foreach ($commerces as $key => $value) {
	          $list[$value["ShopCommerce"]["code"]] = $value["ShopCommerce"]["code"]." - ".$value["ShopCommerce"]["name"]." | ".$value["ShopCommerce"]["shop_name"];
	        }
	    }

	    $this->set("list",$list);

	}

	public function cobranzatab($nom_array = null){
		if ($this->request->is('GET')) {
			$nom_array = $this->request->query["nom_array"];
			//echo "<br>".$nom_array;
			if (date("D")=="Mon"){
					$week_start = date("Y-m-d");
			} else {
					$week_start = date("Y-m-d", strtotime('last Monday', time()));
			}
			$week_end = strtotime('next Sunday', time());
			$week_end = date('Y-m-d', $week_end);

			$this->loadModel("Commitment");

			switch ($nom_array) {
				case "commitmentsWeek":
					$conditions1 = ["DATE(Commitment.deadline) >=" => $week_start, "DATE(Commitment.deadline) <=" => $week_end ];
					break;
				case "commitmentsNoAdmin":
					$conditions2 = ["DATE(Commitment.deadline) <" => date("Y-m-d"), "Commitment.state" => 0 ];
					break;
				case "commitmentsEnd":
					$conditions3 = ["Commitment.state" => 1 ];
					break;
			}

			if(isset($this->request->query["user"])){
				switch ($nom_array) {
					case "commitmentsWeek":
						$conditions1["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
						break;
					case "commitmentsNoAdmin":
						$conditions2["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
						break;
				}
			}

			$type = AuthComponent::user("role") == 11 ? 1 : 0;

			switch ($nom_array) {
				case "commitmentsWeek":
					$conditions1["Commitment.type"] = $type;
					break;
				case "commitmentsNoAdmin":
					$conditions2["Commitment.type"] = $type;
					break;
				case "commitmentsEnd":
					$conditions3["Commitment.type"] = $type;
					break;
			}

			$allQuotesJuridic = $this->CreditsRequest->Credit->CreditsPlan->find("all",["conditions" => ["Credit.juridico" => 1] ]);

			if (!empty($allQuotesJuridic)) {
				$allIds = Set::extract($allQuotesJuridic, "{n}.CreditsPlan.id"); //credidos //credit plan/ id (4 y 16) * 20
				switch ($nom_array) {
					case "commitmentsWeek":
						$conditions1["Commitment.credits_plan_id != "] = $allIds;
						break;
					case "commitmentsNoAdmin":
						$conditions2["Commitment.credits_plan_id != "] = $allIds;
						break;
					case "commitmentsEnd":
						$conditions3["Commitment.credits_plan_id != "] = $allIds;
						break;
				}
			}

			switch ($nom_array) {
				case "commitmentsWeek":
					$commitmentsWeek 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions1 ]);
					foreach ($commitmentsWeek as $key => $value) {
						$commitmentsWeek[$key]["CreditsPlan"]["crypto_id"] = $this->encrypt($value["CreditsPlan"]["id"]);
						$commitmentsWeek[$key]["CreditsPlan"]["crypto_credit_id"] = $this->encrypt($value["CreditsPlan"]["credit_id"]);
					}
					break;
				case "commitmentsNoAdmin":
					$commitmentsNoAdmin = $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions2  ]);
					foreach ($commitmentsNoAdmin as $key => $value) {
						$commitmentsNoAdmin[$key]["CreditsPlan"]["crypto_id"] = $this->encrypt($value["CreditsPlan"]["id"]);
						$commitmentsNoAdmin[$key]["CreditsPlan"]["crypto_credit_id"] = $this->encrypt($value["CreditsPlan"]["credit_id"]);
					}
					break;
				case "commitmentsEnd":
					$commitmentsEnd = $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions3  ]);
					foreach ($commitmentsEnd as $key => $value) {
						$commitmentsEnd[$key]["CreditsPlan"]["crypto_id"] = $this->encrypt($value["CreditsPlan"]["id"]);
						$commitmentsEnd[$key]["CreditsPlan"]["crypto_credit_id"] = $this->encrypt($value["CreditsPlan"]["credit_id"]);
					}
					break;
			}

			//$commitmentsWeek 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions1 ]);
			//$commitmentsNoAdmin = $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions2  ]);
			//$commitmentsEnd = $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions3  ]);

			$users = $this->Commitment->User->findAllByRole(9);

			//$this->set(compact("commitmentsToday","commitmentsWeek","commitmentsNoAdmin","users","commitmentsEnd"));

			$data['status'] = 'ok';
			switch ($nom_array) {
				case "commitmentsWeek":
					$data['result'] = $commitmentsWeek;
					break;
				case "commitmentsNoAdmin":
					$data['result'] = $commitmentsNoAdmin;
					break;
				case "commitmentsEnd":
					$data['result'] = $commitmentsEnd;
					break;
			}

			 $this->set(compact('data', 'jsonHeaders'));

			 $this->autoLayout = false;
			 $this->response->disableCache();
			 $this->response->modified('now');
			 $this->response->checkNotModified($this->request);
			 $this->render(false);

			 $this->response->body(json_encode($data));
			 $this->response->statusCode(200);
			 $this->response->type('application/json');

			 return $this->response;
		}

	}

	public function encryptphp() {
		if ($this->request->is('GET')) {
			$crypto_id = $this->request->query["id"];
			$crypto_credit_id = $this->request->query["credit_id"];

			$data['crypto_id'] = $this->encrypt($crypto_id);
			$data['crypto_credit_id'] = $this->encrypt($crypto_credit_id);

			$this->set(compact('data', 'jsonHeaders'));

			$this->autoLayout = false;
			$this->response->disableCache();
			$this->response->modified('now');
			$this->response->checkNotModified($this->request);
			$this->render(false);

			$this->response->body(json_encode($data));
			$this->response->statusCode(200);
			$this->response->type('application/json');

			return $this->response;
		}
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CreditsRequest->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CreditsRequest->recursive = 0;
		$conditions = array('CreditsRequest.' . $this->CreditsRequest->primaryKey => $id);
		$this->set('creditsRequest', $this->CreditsRequest->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CreditsRequest->create();
			if ($this->CreditsRequest->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$customers = $this->CreditsRequest->Customer->find('list');
		$creditsLines = $this->CreditsRequest->CreditsLine->find('list');
		$this->set(compact('customers', 'creditsLines'));
	}

	/*public function applyCredit(){
		$this->autoRender = false;
		$id_request = $this->decrypt($this->request->data["id_request"]);
		$this->CreditsRequest->recursive = -1;
		$this->CreditsRequest->CreditsLine->recursive = -1;
		$creditRequest  = $this->CreditsRequest->findById($id_request);
		$activeLine 	= $this->CreditsRequest->CreditsLine->findByState(1);
		$cuoteValuesData = $this->calculate_qoute($this->request->data["valueNumberQ"],$this->request->data["valueCredit"],$this->request->data["frecuency"]);
		$totalCuotes 		= $this->request->data["valueNumberQ"] / $this->request->data["frecuency"];
		$created = null;
		$fechaDataRequest = date("Y-m-d");
		if(isset($this->request->data["fecha"]) && $this->request->data["fecha"] != "0"){
			$created 			= $this->request->data["fecha"]." ".date("H:i:s");
			$dateDisbursement 	= $created;
			$fechaDataRequest 	= $this->request->data["fecha"];
		}else{
			$dateDisbursement 	= date("Y-m-d H:i:s");
		}
		$this->loadModel("Credit");
		$dias 	= $totalCuotes * 30;
		$credit = [
			"Credit" =>
				[
					"credits_line_id" => $creditRequest["CreditsRequest"]["credits_line_id"],
					"value_request"   => $this->request->data["valueCredit"],
					"value_aprooved"  => $creditRequest["CreditsRequest"]["value_approve"],
					"number_fee"	  => $this->request->data["valueNumberQ"],
					"deadline"		  => date("Y-m-d",strtotime($fechaDataRequest."+".$dias. " days")),
					"interes_rate"	  => $activeLine["CreditsLine"]["interest_rate"],
					"others_rate"	  => $activeLine["CreditsLine"]["others_rate"],
					"debt_rate"	  	  => $activeLine["CreditsLine"]["debt_rate"],
					"quota_value"     => $cuoteValuesData["cuote"],
					"value_pending"   => $this->request->data["valueCredit"],
					"customer_id"	  => $creditRequest["CreditsRequest"]["customer_id"],
					"type"			  		=> $this->request->data["frecuency"],
					"credits_request_id" 	=> $id_request,
				]
		];
		$number = 0;
		if (!is_null($created)) {
			$credit["Credit"]["created"] = $created;
		}
		$this->Credit->create();
		if($this->Credit->save($credit)){
			$creditId = $this->Credit->id;
			$this->loadModel("Shop");
			$priceValue			= $this->request->data["valueCredit"];
			$totalCapitalDeuda 	= $priceValue;
			$j = 0;
			for ($i=1; $i <= $this->request->data["valueNumberQ"]; $i++) {
				$intereses 		= round($priceValue*($cuoteValuesData["intRate"]/ $this->request->data["frecuency"]));
				$interesesOtro 	= round($priceValue*($cuoteValuesData["intOther"]/ $this->request->data["frecuency"]));
				$capitalC       = $cuoteValuesData["cuote"] - $intereses - $interesesOtro;
				$priceValue     	-= $capitalC;
				$totalCapitalDeuda	-= $capitalC;
				$this->Credit->CreditsPlan->create();
				if ($this->request->data["frecuency"] == 1){
					$fecha = date("Y-m-d",strtotime($fechaDataRequest."+$i month"));
					$fechaIni = date("Y-m-d",strtotime($fecha."-1 month"));
				}else{
					$days 	= $i*15;
					$fecha 	= date("Y-m-d",strtotime($fechaDataRequest."+$days days"));
					$fechaIni 	= date("Y-m-d",strtotime($fecha."-15 days"));
				}
		$creditPlan = [
					"CreditsPlan" => [
						"credit_id" 		=> $creditId,
						"capital_value" 	=> round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ?  $ultimoCap : floatval($capitalC),
						"interest_value" 	=> floatval($intereses),
						"others_value"      => floatval($interesesOtro),
						"deadline"			=> $fecha,
						"dateini"			=> $fechaIni,
						"value_pending"	    => round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ? 0 : floatval(round($totalCapitalDeuda)),
						"state" 			=> 0,
						"number"			=> $i
					]
				];
				$this->Credit->CreditsPlan->save($creditPlan);
				 $ultimoCap = round($totalCapitalDeuda);
			}
			$creditRequest["CreditsRequest"]["state"] 			= 5;
			$creditRequest["CreditsRequest"]["date_disbursed"] 	= $dateDisbursement;
			$creditRequest["CreditsRequest"]["user_disbursed"] 	= AuthComponent::user("id");
			$creditRequest["CreditsRequest"]["value_disbursed"] = $this->request->data["valueCredit"];
			$creditRequest["CreditsRequest"]["credit_id"] 		= $creditId;
			$this->CreditsRequest->save($creditRequest);
			$dateLimit = date("Y-m-d",strtotime($fechaDataRequest."+".$totalCuotes." month") );
			$datosLimit = [
				"CreditLimit" => [
					"value" 	 			=> $this->request->data["valueCredit"],
					"state" 	 			=> 6,
					"reason"	 			=> "Desembolso de cupo",
					"type_movement" 		=> 2,
					"credits_request_id" 	=> $creditRequest["CreditsRequest"]["id"],
					"user_id"			 	=> AuthComponent::user("id"),
					"deadline"			 	=> $dateLimit,
					"customer_id"			=> $creditRequest["CreditsRequest"]["customer_id"],
					"credit_id"				=> $creditId
				]
			];
			$this->CreditsRequest->CreditLimit->create();
			$this->CreditsRequest->CreditLimit->save($datosLimit);
			$this->loadModel("Disbursement");
			$dataDisbursement = array(
				"Disbursement" => [
					"value" => $this->request->data["valueCredit"],
					"credit_id" => $creditId,
					"shop_commerce_id" => $creditRequest["CreditsRequest"]["shop_commerce_id"],
				]
			);
			$this->Disbursement->create();
			$this->Disbursement->save($dataDisbursement);
			$this->CreditsRequest->CreditLimit->updateAll(
				["CreditLimit.state" => 6],
				[
					"CreditLimit.state"  => [1,3,4,5],
					"CreditLimit.customer_id" => $creditRequest["CreditsRequest"]["customer_id"]
				]
			);
			if($this->request->data["valueCredit"] < $creditRequest["CreditsRequest"]["value_approve"]){
				$totalPreAprovved = $creditRequest["CreditsRequest"]["value_approve"] - $this->request->data["valueCredit"];
				$datosLimit = [
					"CreditLimit" => [
						"value" 	 			=> $totalPreAprovved,
						"state" 	 			=> 5,
						"reason"	 			=> "Preaprobado por restante de solicitud",
						"type_movement" 		=> 1,
						"user_id"			 	=> AuthComponent::user("id"),
						"deadline"			 	=> date("Y-m-d",strtotime("+1 month")),
						"customer_id"			=> $creditRequest["CreditsRequest"]["customer_id"],
					]
				];
				$this->CreditsRequest->CreditLimit->create();
				$this->CreditsRequest->CreditLimit->save($datosLimit);
			}
			$this->sendMailCredit($creditId);
			$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
		}
	}*/


	public function request_credit_extra(){
		$this->autoRender 	= false;
		$actualStudy 		= $this->CreditsRequest->findByCustomerIdAndState(AuthComponent::user("customer_id"),[0,1,2]);

		$this->loadModel("Customer");
		$conditions = [
			"customer_id" => AuthComponent::user("customer_id"),
			"state" => 4,
			"extra" => 1,
			"DATE(date_admin) >="=>date("Y-m-d",strtotime("-30 day")),
			"DATE(date_admin) <="=>date("Y-m-d"),
		];

		$actualNoTrue = $this->CreditsRequest->find("first",["conditions"=>$conditions,"recursive"=>-1]);

		if (!empty($actualStudy)) {
			$this->Session->setFlash(__('Ya tienes otra solicitud en proceso no es posible solicitar este aumento.'), 'flash_error');
		}elseif (!empty($actualNoTrue)) {
			$this->Session->setFlash(__('Ya tienes otra solicitud negada en los últimos 30 días, por tal razón no será posible solicitar el aumento.'), 'flash_error');
		}
		else{
			$this->loadModel("CreditsLine");
		    $creditLineId = $this->CreditsLine->findByState(1);
		    $data = [
		      "CreditsRequest" => [
		        "customer_id" => AuthComponent::user("customer_id"),
		        "request_value" => $this->request->data["valueCredit"],
		        "request_number" => $this->request->data["numberCuote"],
		        "credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
		        "shop_commerce_id" => $this->request->data["shop_commerce_extra"],
		        "request_type" => $this->request->data["frecuency"],
		        "extra" => 1
		      ]
		    ];
		    $this->CreditsRequest->create();
		    $this->CreditsRequest->save($data);

			// enviar correo al equipo de ziro
			$this->loadModel("Customer");
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
			$customerInfo =  [
				"customerNombre" => $customer["Customer"]["name"] . ' ' . $customer["Customer"]["last_name"],
				"customerIdentificacion" => $customer["Customer"]["identification"],
				"customerEmail" => $customer["Customer"]["email"],
				"customerTelefono" => $customer["Customer"]["celular"],
			];
			//correos a notificar
			$correos = [
				'victoria@somosziro.com',
				'john@somosziro.com',
				'juancacreativo@somosziro.com',
				'efi@somosziro.com',
				'yordy@somosziro.com',
				'laurens@somosziro.com',
			];

			//opciones para enviar el correo
			$options = [
				"subject"   => "Alerta nueva solicitud aumento de cupo",
				"to"        => $correos,
				"vars"      => $customerInfo,
				"template"  => "new_request_increase_quota",
			];
			//enviar email a equipo ziro de nuevo cliente
			$this->sendMail($options);

		    $this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');

		}
	    $this->redirect(["controller"=>"pages","action"=>"dashboardcliente"]);
	}

	public function applyCredit(){
		$this->autoRender = false;
		$id_request = $this->decrypt($this->request->data["id_request"]);
		//se ajustan cuotas para poder operar
		$frecuencia=$this->request->data["frecuency"]== 2 ? 2 : 1;

		$this->CreditsRequest->recursive = -1;
		$this->CreditsRequest->CreditsLine->recursive = -1;
		$creditRequest  = $this->CreditsRequest->findById($id_request);

		$activeLine 	= $this->CreditsRequest->CreditsLine->findByState(1);

        $creditLineId = $activeLine["CreditsLine"]["id"];

		$debtrate = $this->CreditsRequest->CreditsLine->field("debt_rate",["id"=>$creditLineId]);


        $creditLineDetail = $this->CreditsRequest->query("SELECT * FROM credits_lines_details where credit_line_id =".$creditLineId);

		//el sistema divide por la frecuencia entonces como 45 y 60 dias es una cuota lo igualamos a uno
		$cuoteValuesData = $this->calculate_qoute($this->request->data["valueNumberQ"],$this->request->data["valueCredit"],$frecuencia);

		$valueCredit     = $this->request->data["valueCredit"];


		$frecuenty = $this->request->data["valueNumberQ"];

		foreach ($creditLineDetail as $key => $value) {
			if((($valueCredit >= $value["credits_lines_details"]["min_value"] ) && ($frecuenty/$frecuencia)==$value["credits_lines_details"]["month"]) && ($valueCredit <= $value["credits_lines_details"]["max_value"] )) {
			   $intRate = $value["credits_lines_details"]["interest_rate"];
			   $intOther = $value["credits_lines_details"]["others_rate"];
			   $debtrate = $value["credits_lines_details"]["debt_rate"];
			}
		}


		$totalCuotes 		= $this->request->data["valueNumberQ"] / $frecuencia;
		$created = null;
		$fechaDataRequest = date("Y-m-d");

		if(isset($this->request->data["fecha"]) && $this->request->data["fecha"] != "0"){
			$created 			= $this->request->data["fecha"]." ".date("H:i:s");
			$dateDisbursement 	= $created;
			$fechaDataRequest 	= $this->request->data["fecha"];
		}else{
			$dateDisbursement 	= date("Y-m-d H:i:s");
		}

		$this->loadModel("Credit");
		if ($this->request->data["frecuency"] == 1){
			$dias 	= $totalCuotes * 30;

		} elseif ($this->request->data["frecuency"] == 3){
			$dias 	= 45;

		} elseif ($this->request->data["frecuency"] == 4){
			$dias 	= 60;

		} else{
			$days 	= $totalCuotes*15;
		}

		$credit = [
			"Credit" =>
				[
					"code_pay" => $creditRequest["CreditsRequest"]["code_pay"],
					"credits_line_id" => $creditRequest["CreditsRequest"]["credits_line_id"],
					"value_request"   => $this->request->data["valueCredit"],
					"value_aprooved"  => $creditRequest["CreditsRequest"]["value_approve"],
					"number_fee"	  => $this->request->data["valueNumberQ"],
					"deadline"		  => date("Y-m-d",strtotime($fechaDataRequest."+".$dias. " days")),
					"interes_rate"	  => $intRate,
					"others_rate"	  => $intOther,
					"debt_rate"	  	  => $debtrate,
					"quota_value"     => $cuoteValuesData["cuote"],
					"value_pending"   => $this->request->data["valueCredit"],
					"customer_id"	  => $creditRequest["CreditsRequest"]["customer_id"],
					"type"			  		=> $this->request->data["frecuency"],
					"credits_request_id" 	=> $id_request,
				]
		];
		$number = 0;

		if (!is_null($created)) {
			$credit["Credit"]["created"] = $created;
		}


		$this->Credit->create();

		if($this->Credit->save($credit)){

			$creditId = $this->Credit->id;
			$this->log($creditId,"debug");
			$this->loadModel("Shop");
			$priceValue			= $this->request->data["valueCredit"];
			$totalCapitalDeuda 	= $priceValue;
			$j = 0;


			for ($i=1; $i <= $this->request->data["valueNumberQ"]; $i++) {

				$intereses 		= round($priceValue*($cuoteValuesData["intRate"]/ $frecuencia));
				$interesesOtro 	= round($priceValue*($cuoteValuesData["intOther"]/ $frecuencia));
				$capitalC       = $cuoteValuesData["cuote"] - $intereses - $interesesOtro;
				$priceValue     	-= $capitalC;
				$totalCapitalDeuda	-= $capitalC;

				$this->Credit->CreditsPlan->create();

				if ($this->request->data["frecuency"] == 1){
					$fecha = date("Y-m-d",strtotime($fechaDataRequest."+$i month"));
					$fechaIni = date("Y-m-d",strtotime($fecha."-1 month"));

				} elseif ($this->request->data["frecuency"] == 3){
					$days 	= 46;
					$fecha = date("Y-m-d",strtotime($fechaDataRequest."+$days days"));
					$fechaIni = date("Y-m-d",strtotime($fecha."-1 days"));

				} elseif ($this->request->data["frecuency"] == 4){
					$days 	= 61;
					$fecha = date("Y-m-d",strtotime($fechaDataRequest."+$days days"));
					$fechaIni = date("Y-m-d",strtotime($fecha."-1 days"));

				} else{
					$days 	= $i*15;
					$fecha 	= date("Y-m-d",strtotime($fechaDataRequest."+$days days"));
					$fechaIni 	= date("Y-m-d",strtotime($fecha."-15 days"));
				}

				$creditPlan = [
					"CreditsPlan" => [
						"credit_id" 		=> $creditId,
						"capital_value" 	=> round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ?  ($ultimoCap==0?floatval($capitalC):$ultimoCap) : floatval($capitalC),
						"interest_value" 	=> floatval($intereses),
						"others_value"      => floatval($interesesOtro),
						"deadline"			=> $fecha,
						"dateini"			=> $fechaIni,
						"value_pending"	    => round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ? 0 : floatval(round($totalCapitalDeuda)),
						"state" 			=> 0,
						"number"			=> $i,
						"capital_value_proy" 	=> round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ?  ($ultimoCap==0?floatval($capitalC):$ultimoCap) : floatval($capitalC),
					]
				];

				$this->Credit->CreditsPlan->save($creditPlan);

				$ultimoCap = round($totalCapitalDeuda);
			}

			$creditRequest["CreditsRequest"]["state"] 			= 5;
			$creditRequest["CreditsRequest"]["date_disbursed"] 	= $dateDisbursement;
			$creditRequest["CreditsRequest"]["user_disbursed"] 	= AuthComponent::user("id");
			$creditRequest["CreditsRequest"]["value_disbursed"] = $this->request->data["valueCredit"];
			$creditRequest["CreditsRequest"]["credit_id"] 		= $creditId;

			if (isset($this->request->data["platform"]) && ($creditRequest["CreditsRequest"]["value_approve"] < $creditRequest["CreditsRequest"]["value_disbursed"]	) ) {
				$creditRequest["CreditsRequest"]["value_approve"] = $creditRequest["CreditsRequest"]["value_disbursed"];
				//Acá le dice que si viene de crediventas y el valor final es mayor al aprobado se actualiza el valor aprobado por la formula del 12+10000
			}

			$this->CreditsRequest->save($creditRequest);


			$dateLimit = date("Y-m-d",strtotime($fechaDataRequest."+".$totalCuotes." month") );

			$datosLimit = [
				"CreditLimit" => [
					"value" 	 			=> $this->request->data["valueCredit"],
					"state" 	 			=> 6,
					"reason"	 			=> "Desembolso de cupo",
					"type_movement" 		=> 2,
					"credits_request_id" 	=> $creditRequest["CreditsRequest"]["id"],
					"user_id"			 	=> AuthComponent::user("id"),
					"deadline"			 	=> $dateLimit,
					"customer_id"			=> $creditRequest["CreditsRequest"]["customer_id"],
					"credit_id"				=> $creditId
				]
			];

			$this->CreditsRequest->CreditLimit->create();
			$this->CreditsRequest->CreditLimit->save($datosLimit);

			$this->loadModel("Disbursement");

			$dataDisbursement = array(
				"Disbursement" => [
					"value" => $this->request->data["valueCredit"],
					"credit_id" => $creditRequest["CreditsRequest"]["credit_id"],
					"shop_commerce_id" => $creditRequest["CreditsRequest"]["shop_commerce_id"],
				]
			);

			$this->log($dataDisbursement,"debug");

			$this->Disbursement->create();
			$this->Disbursement->save($dataDisbursement);

			if ($creditRequest["CreditsRequest"]["transfer"] == 1) {
				$valueReference = $this->totalQuote(true,$creditRequest["CreditsRequest"]["customer_id"]);

				$this->CreditsRequest->CreditLimit->updateAll(
					["CreditLimit.state" => 6],
					[
						"CreditLimit.state"  => [1,3,4,5],
						"CreditLimit.customer_id" => $creditRequest["CreditsRequest"]["customer_id"]
					]
				);
			}else{
				$this->CreditsRequest->CreditLimit->updateAll(
					["CreditLimit.state" => 6],
					[
						"CreditLimit.state"  => [1,3,4,5],
						"CreditLimit.customer_id" => $creditRequest["CreditsRequest"]["customer_id"]
					]
				);
				$valueReference = $creditRequest["CreditsRequest"]["value_approve"];
			}



			if($this->request->data["valueCredit"] < $valueReference){

				$totalPreAprovved = $valueReference - $this->request->data["valueCredit"];
				$datosLimit = [
					"CreditLimit" => [
						"value" 	 			=> $totalPreAprovved, //$this->totalQuote(true,$creditRequest["CreditsRequest"]["customer_id"]),
						"state" 	 			=> 5,
						"reason"	 			=> "Preaprobado por restante de solicitud",
						"type_movement" 		=> 1,
						"user_id"			 	=> AuthComponent::user("id"),
						"deadline"			 	=> date("Y-m-d",strtotime("+1 month")),
						"customer_id"			=> $creditRequest["CreditsRequest"]["customer_id"],
					]
				];
				$this->CreditsRequest->CreditLimit->create();
				$this->CreditsRequest->CreditLimit->save($datosLimit);
			}

			$this->sendMailCredit($creditId);

			//enviar whatsapp metodos de pago
			$this->loadModel("Credits");
			$credits=$this->Credits->find("count",["conditions"=>["Credits.customer_id" => $creditRequest["CreditsRequest"]["customer_id"] ]]);
			$this->loadModel("Customer");
			$customer = $this->Customer->findById($creditRequest["CreditsRequest"]["customer_id"]);
			if($credits==1) {
				$templateParams= [
					ucwords($customer['Customer']['name']),
				];
				$phone=$customer['CustomersPhone'][0]['phone_number'];
				$templateId='8971116339597065';
				$templateMsj="Ey! {{1}} ☺️ Estamos demasiado felices por tenerte en la familia *zíro* 🥳✨ y nos emociona que tomaras la decisión de hacer crecer tu negocio 🤜💥🤛 Te queremos dejar un video para que conozcas tooodos nuestros medios de pago y que puedas seguir disfrutando de tu beneficio con *zíro* 👉 https://s.kbe.ai/s/DL222\n\nSíguenos en nuestras redes sociales 🤩 entérate de todas las promos y beneficios que adicionalmente traemos para ti 🎉\n\n👤 Facebook: https://www.facebook.com/somosziro\n📸 Instagram: https://www.instagram.com/somosziro/\n📹 Youtube: https://youtube.com/@somosziro\n\nwww.somosziro.com";
				$this->sendWhatsapp($templateParams,$phone,$templateId,$templateMsj);
			}
			$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');

		}

	}

	public function sendPagaresNotSend() {
		$this->autoRender = false;

		$this->loadModel('CreditsRequest');
		$conditions = array(
			'OR' => array(
				'CreditsRequest.pagare_inicial !=' => '',
				'CreditsRequest.pagare_inicial IS NOT NULL',
				'CreditsRequest.pagare_final !=' => '',
				'CreditsRequest.pagare_final IS NOT NULL',
			),
			'CreditsRequest.date_disbursed >=' => '2023-04-29 00:00:00',
			'CreditsRequest.date_disbursed <=' => date('Y-m-d 23:59:59')
		);

		$results = $this->CreditsRequest->find('all', array('conditions' => $conditions));
		debug($results);
		die();

		foreach ($results as $result) {
			if(!empty($result['Credit'])) {
				$this->sendMailCredit($result['CreditsRequest']['credit_id']);
			}
		}


		debug('termine');
		die();
	}


	public function sendMailCredit($creditId,$ajustado=null){
		$this->loadModel("Credit");
		$credit 		= $this->CreditsRequest->Credit->findById($creditId);

		/**************************Pagare Final **********************/

		$this->loadModel("Signature");
		$full_text  = $this->Signature->field("full_text",["id"=>1]);

		$this->CreditsRequest->recursive = 2;
		$request 		= $this->CreditsRequest->findById($credit["CreditsRequest"]["id"]);

		$requestCopy    = $request;
		$requestCopy["CreditsRequest"]["value_approve"] = $request["CreditsRequest"]["value_disbursed"];
		$full_text  	= $this->replaceVarsText($full_text,$requestCopy);
		$creditInfo 	= $credit;
		$creditRequest 	= $request;
		$quotes 		= $this->Credit->CreditsPlan->getCuotesInformation($request["CreditsRequest"]["credit_id"]);

		$document_file_up    = $request["Customer"]["document_file_up"];
		$document_file_down  = $request["Customer"]["document_file_down"];
		$image_file  		 = $request["Customer"]["image_file"];
		$url_files  		 = $request["Customer"]["url_files"];
		$nombre  		 = $request["Customer"]["name"] .  $request["Customer"]["last_name"];
		$cedula  		 = $request["Customer"]["identification"];

		$idFileFinal = uniqid($this->encrypt($request["CreditsRequest"]["id"]));
		$this->loadModel("CustomersCode");
		$codigo = $this->CustomersCode->field("code",[
			"customer_id"=>$request["Customer"]["id"], "credits_request_id" => $request["CreditsRequest"]["id"],
			"type_code" => 2, "state" => 1 ]
		);
		$options = array(
	        'template' => 'final_pagare',
	        'ruta' => APP . 'webroot' . DS . 'files' . DS . 'pagares' . DS . 'finals'. DS . $idFileFinal . ".pdf",
	        'vars' => compact("full_text","creditInfo","creditRequest","quotes","nombre","cedula","document_file_up","document_file_down","image_file","url_files","codigo"),
	    );
	    $this->generatePdf($options);
	    $file = 'files' . DS . 'pagares' . DS . 'finals'. DS . $idFileFinal . ".pdf";
	    $request["CreditsRequest"]["pagare_final"] = $idFileFinal . ".pdf";
	    $this->CreditsRequest->save($request["CreditsRequest"]);

		/**************************FinPagare Final **********************/

		$shopCommerce 	= $this->CreditsRequest->ShopCommerce->findById($credit["CreditsRequest"]["shop_commerce_id"]);
		if(!empty($credit["Customer"]["email"])){
			$subject=is_null($ajustado) ? 'Crédito aprobado - Plan de pago' : 'Ajuste de tú Crédito con nuevas condiciones - Plan de pago';
			$options = [
				"subject" 	=> $subject,
				"to"   		=> [$credit["Customer"]["email"],'laurens@somosziro.com'],
				"vars" 	    => ["credit" => $credit,"shop_commerce" => $shopCommerce],
				"template"	=> "credit_approve_final",
				"file" 		=> $file
			];
			$this->sendMail($options);
		}
	}



	public function created(){
		$this->autoRender = false;
		if ($this->request->is('post')) {

			$existsCredit = $this->CreditsRequest->findAllByCustomerIdAndState(AuthComponent::user("customer_id"),[0,1]);
			$code 		  = $this->validateCodeCommerce();

			$this->loadModel("ShopCommerce");
			$shop_commerce_id = $this->ShopCommerce->field("id",["code" => $code]);
			$this->loadModel("CreditsLine");
			$creditLineId = $this->CreditsLine->findByState(1);
			$data = [
				"CreditsRequest" => [
					"customer_id" => AuthComponent::user("customer_id"),
					"request_value" => $this->request->data["priceValue"],
					"request_number" => $this->request->data["couteValue"],
					"credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
					"shop_commerce_id" => $shop_commerce_id,
					"request_type" => $this->request->data["frecuency"]
				]
			];
			$this->CreditsRequest->create();
			if ($this->CreditsRequest->save($data)) {
				$this->loadModel("User");
				$this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 6]]);
        		$this->overwrite_session_user(AuthComponent::user('id'));
				$this->CreditsRequest->CreditLimit->updateAll(
					[
						"state" => 2,
						"reason" => "'Cancelado por solicitud nueva de cupo'",
					],
					[
						"CreditLimit.customer_id" => AuthComponent::user("customer_id"),
						"CreditLimit.state" => [1,3,5]
					]
				);
				$this->Session->write("CODE_COMMERCE",null);
				$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
			}
		}
	}


	public function voucher($id)
	{
		$this->layout = false;
		$this->CreditsRequest->recursive = 2;
		$creditRequest = $this->CreditsRequest->findById($this->decrypt($id));
		$total 		   = 0;

		foreach ($creditRequest["Credit"]["CreditsPlan"] as $key => $value) {
			$total+=$value["capital_value"]+$value["interest_value"]+$value["others_value"];
		}

		$this->set(compact("creditRequest","total"));
	}

	public function receipt_payment($id)
	{
		$this->layout = false;
		$this->CreditsRequest->recursive = 2;
		$creditRequest = $this->CreditsRequest->findById($this->decrypt($id));
		$this->set(compact("creditRequest"));
	}

    public function credit_detail($id,$layout = null)
	{
		$this->CreditsRequest->recursive = 2;

		$this->CreditsRequest->unBindModel(
			["belongsTo" => ["CreditsLine"] ]
		);
		$this->CreditsRequest->Credit->CreditsPlan->setCuotasValue();
		$creditInfo 	= $this->CreditsRequest->Credit->findById($this->decrypt($id));


		$this->CreditsRequest->Credit->CreditsPlan->update_cuotes_days();

		$quotes 		= $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($this->decrypt($id));

		$creditRequest 	= $this->CreditsRequest->findById($creditInfo["Credit"]["credits_request_id"]);

		$user 			= $this->CreditsRequest->Credit->Customer->User->findByCustomerId($creditRequest["CreditsRequest"]["customer_id"]);

        $quotes = $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

        $totalNoPayment = $this->CreditsRequest->Credit->CreditsPlan->find("count",["conditions"=>["CreditsPlan.state" => 0, "CreditsPlan.credit_id" => $creditInfo["Credit"]["id"] ]]);
        //actualizr contriller

        $deudaTotal = $creditInfo["Credit"]["value_request"];
        $firstDate = "";
        $DateLast = "";
        $swich = 0;
        $pago = 0;
        $pagoA = 0;
        $dateLastPay = null;
        $dateUltPago = null;
        $deudaF = 0;
        $cuotaacumulada = 0;
        $cuenta = $creditInfo["Credit"]["number_fee"] - 1;

        $idLast 	= null;
        foreach ($quotes as $key => $value) {
            if ($cuenta > 0) {
                $pagoA = ($value["CreditsPlan"]["state"]);
                if ($pagoA == 1) {
                    $cuotaacumulada = $cuotaacumulada + $value["CreditsPlan"]["capital_value"];
                }
            }
            if ($value["CreditsPlan"]["credit_old"] == 10) {
        		$idLast = $value["CreditsPlan"]["id"];
        		continue;
        	}
            $cuenta--;
        }

        $lastQuote  = [];

        foreach ($quotes as $key => $value) {

            $capital = $value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"];
            $interes = $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"];
            $others = $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"];

            //Calculo Interes corriente
            if ($firstDate == "") {
                $firstDate = $creditInfo["CreditsRequest"]["date_disbursed"];

                //  $DateLast = $value["CreditsPlan"]["deadline"];
            } else {
                $firstDate = $DateLast;

            }

            $secondDate = $value["CreditsPlan"]["deadline"]; //$value["CreditsPlan"]["deadline"];
            // $dateUltPago = $value["CreditsPlan"]["date_payment"];
            $DateLast = $secondDate;

            $fecha1 = new DateTime($firstDate);
            $fecha2 = new DateTime($secondDate);
            $resultado = $fecha1->diff($fecha2);
            $days = $resultado->format('%a');

            if ($swich == 0) {
                $swich = 1;
                $days = $days + 1;
            }

            if ($days >= 31) {
                $days = 30;
            }

            if ($creditInfo["Credit"]["type"] == 1 && $days < 30) {
            	$days = 30;
            }

			if ($creditInfo["Credit"]["type"] == 3 && $days < 45) {
            	$days = 45;
            }

			if ($creditInfo["Credit"]["type"] == 4 && $days < 60) {
            	$days = 60;
            }

            if ($creditInfo["Credit"]["type"] != 1 && $days < 15) {
            	$days = 15;
            }

            if ($firstDate != $creditInfo["CreditsRequest"]["date_disbursed"]) {

                $interesesT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
                //Fin Interes corriente
                //otros intereses
                $interesesOT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

                //capital
                $CapitalN = $value["CreditsPlan"]["capital_value"];

            } else {

                $interesesT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
                //Fin Interes corriente

                //otros intereses
                $interesesOT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

                //capital
                $CapitalN = $value["CreditsPlan"]["capital_value"];

            }

            if ( $value["CreditsPlan"]["interest_value"] <= $value["CreditsPlan"]["interest_payment"] ) {
                $interesesT = $value["CreditsPlan"]["interest_payment"];
                $value["CreditsPlan"]["interest_value"] = $value["CreditsPlan"]["interest_payment"];
            }

            if ( $value["CreditsPlan"]["others_value"] <= $value["CreditsPlan"]["others_payment"] ) {
                $interesesOT = $value["CreditsPlan"]["others_payment"];
                $value["CreditsPlan"]["others_value"] = $value["CreditsPlan"]["others_payment"];
            }

            $pago = ($value["CreditsPlan"]["state"]);

            if ($pago == 1) {

                if ($dateUltPago == null) {
                    // echo $cuotaacumulada;
                    $dateUltPago = $value["CreditsPlan"]["date_payment"];
                    //$cuotaacumulada = $cuotaacumulad  +  $value["CreditsPlan"]["capital_value"];
                    $deudaF = $cuotaacumulada + $creditInfo["Credit"]["value_pending"];

                } else if ($dateUltPago < $value["CreditsPlan"]["deadline"]) {

                    $dateUltPago = $value["CreditsPlan"]["date_payment"];
                    $deudaF = $value["CreditsPlan"]["capital_value"] + $creditInfo["Credit"]["value_pending"];

                }

            }

            $this->loadModel("CreditsPlan");
            $this->CreditsPlan->updateAll(
                [
                	"CreditsPlan.capital_value" => (
                		($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and
                		$value["CreditsPlan"]["state"] == 0) ? ROUND($CapitalN) : ROUND($value["CreditsPlan"]["capital_value"]
                	),
                    "CreditsPlan.interest_value" => (
                    	($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and
                    	$value["CreditsPlan"]["state"] == 0) ? ROUND($interesesT) : ROUND($value["CreditsPlan"]["interest_value"]
                    ),
                    "others_value" => (
                    	($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and
                    	$value["CreditsPlan"]["state"] == 0) ? ROUND($interesesOT) : ROUND($value["CreditsPlan"]["others_value"]
                    )
                ],
                ["CreditsPlan.id" => $value["CreditsPlan"]["id"]]
            );
            $lastQuote = $value;
        }

        $quotes = $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

        $totalCredit = $this->CreditsRequest->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);

        $totalCap = 0;
        $plan_id = 0;
        $valorUltQ = 0;

        $quotes = $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

        $totalCredit = $this->CreditsRequest->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);

        for ($i = 0; $i < sizeof($quotes); $i++) {

        	$whereData = "";

            $pay = $this->CreditsRequest->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quotes[$i]["CreditsPlan"]["id"] . " ' ".$whereData);
            $quotes[$i]["CreditsPlan"] += ["TotalAbo" => $pay[0][0]["PaymentA"]];
        }
		//FIN


		$this->set(compact("creditRequest","layout","creditInfo","quotes","user","totalCredit"));
	}


	public function consult_central(){
		$this->layout = false;
		$request = $this->CreditsRequest->findById($this->decrypt($this->request->data["request"]));
		$identification = $request["Customer"]["identification"];

		$detalleCentral = $this->connect($identification,"consultadetallescorevector");

		$vars = [];

		if(!is_null($detalleCentral) && $detalleCentral->estado == 1){

			$resumen 		= $this->connect($identification,"consultaresumenscore");

			if(!empty($detalleCentral->score)){
				$vars["RIESGO_SCORE_CREDITICIO"] = str_replace("RIESGO ", "", $detalleCentral->score[0]->RANGO);
			}else{
				$vars["RIESGO_SCORE_CREDITICIO"] = "BAJO";
			}

			if(empty($detalleCentral->obligaciones)){
				$vars["OBLIGACIONES_EN_MORA"] 		= "0 días";
				$vars["OBLIGACIONES_CANCELADAS"] 	= "0";
				$vars["CAPACIDAD_DE_ENDEUDAMIENTO"] = "86% a 100%";
			}else{
				$vars["OBLIGACIONES_EN_MORA"] 		= $this->getObligacionesMora($detalleCentral->obligaciones);
				$vars["OBLIGACIONES_CANCELADAS"] 	= $this->getObligacionesCanceladas($detalleCentral->obligaciones);
				$vars["CAPACIDAD_DE_ENDEUDAMIENTO"] = $this->getCapacidadPago($detalleCentral->obligaciones,$request["Customer"]["monthly_income"]);
			}

			$vars["OCUPACION"] 		  = $request["Customer"]["occupation"];
			$vars["TIPO_DE_CONTRATO"] = $request["Customer"]["type_contract"];

			if(!empty($resumen) && $resumen->estado == 1){
				if(empty($resumen->datos)){
					$vars["HABITO_DE_PAGO"] = "Al día";
				}else{
					$vars["HABITO_DE_PAGO"] = $this->getHabitoPago($resumen->datos);
				}
			}
		}

		if(!empty($vars)){
			$ponderados 	= Configure::read("PONDERADO");
			$varsProcredito	= Configure::read("PROCREDITO");

			$total 			= 0;
			$varsSelected 	= [];
			foreach ($vars as $key => $value) {

				$selectedValue = $varsProcredito[$key][$value];
				$ponderadoValue = $ponderados[$key];
				$total+= ( $selectedValue*$ponderadoValue );
				$varsSelected[$key] = [$value];
			}

			$request["CreditsRequest"]["total_score"] 	= $total;
			$request["CreditsRequest"]["vars_score"]  	= json_encode($varsSelected);
			$request["CreditsRequest"]["response_score"]  = json_encode(["consultadetallescorevector"=>$detalleCentral,"consultaresumenscore" => $resumen]);

			if($request["CreditsRequest"]["customer_id"] >= 0){
				$this->CreditsRequest->save($request["CreditsRequest"]);
			}

			$this->set(compact("varsSelected","total","request"));
		}

	}

	public function view_central(){
		$this->layout = false;
		$request = $this->CreditsRequest->findById($this->decrypt($this->request->data["request"]));

		$total = $request["CreditsRequest"]["total_score"];
		$varsSelected = (array) json_decode($request["CreditsRequest"]["vars_score"]);
		$this->set(compact("varsSelected","total","request"));
		$this->render("consult_central");
	}

	private function getCapacidadPago($obligaciones,$presupuesto){
		$totalDeuda = 0;
		foreach ($obligaciones as $key => $value) {
			$totalDeuda+=$value->SALDO;
		}

		if($totalDeuda == 0){
			$resp = "86% a 100%";
		}else{
			$result = 1 - ($totalDeuda / $presupuesto);
			$result = round($result,2);

			if($result <= 0){
				$resp = "0-49%";
			}elseif($result > 0 && $result <= 0.49){
				$resp = "0-49%";
			}elseif($result > 0.49 && $result <= 0.69){
				$resp = "50%-69%";
			}elseif($result > 0.69 && $result < 0.85){
				$resp = "70%-85%";
			}else{
				$resp = "86% a 100%";
			}
		}
		return $resp;
	}

	private function getHabitoPago($resumenDatos){

		$resp = "Al día";

		foreach ($resumenDatos as $key => $value) {
			if($value->ID_TIPO_GARANTE == 1){
				if($value->CANTIDAD_MORA_MAYOR > 0){
					$resp = "Mora 90";
				}elseif ($value->CANTIDAD_MORA_90 > 0) {
					$resp = "Mora 90";
				}elseif ($value->CANTIDAD_MORA_60 > 0) {
					$resp = "Mora 60";
				}elseif ($value->CANTIDAD_MORA_30 > 0) {
					$resp = "Mora 30";
				}else{
					$resp = "Al día";
				}
				break;
			}
		}

		return $resp;

	}

	private function getObligacionesMora($obligaciones){
		$totalDias = 0;

		foreach ($obligaciones as $key => $value) {
			if($value->ESTADO == "MORA" && $value->DIAS_MORA > $totalDias){
				$totalDias = $value->DIAS_MORA;
			}
		}

		if($totalDias == 0){
			$resp = "0 días";
		}elseif ($totalDias > 0 && $totalDias <= 30) {
			$resp = "30 días";
		}elseif ($totalDias > 31 && $totalDias <= 60) {
			$resp = "60 días";
		}elseif ($totalDias > 61 && $totalDias <= 90) {
			$resp = "90 días";
		}else{
			$resp = "Más de 90 días";
		}

		return $resp;

	}

	private function getObligacionesCanceladas($obligaciones){
		$totalSaldo = 0;

		foreach ($obligaciones as $key => $value) {
			if($value->ESTADO == "SALDADA" ){
				$totalSaldo++;
			}
		}

		if($totalSaldo > 4){
			$resp = "4";
		}else{
			$resp = strval($totalSaldo);
		}
		return $resp;

	}

	public function simulate() {
		$this->layout = false;
		$this->CreditsRequest->recursive = 2;
		$request 		= $this->CreditsRequest->findById($this->decrypt($this->request->data["request"]));
		$vars 	 		= Configure::read("PROCREDITO");
		$ponderados 	= Configure::read("PONDERADO");
		$total 			= 0;
		$varsSelected 	= [];

		if (empty($request["CreditsRequest"]["empresa_id"])) {
			$this->set(compact("varsSelected","total","request"));
		}else{
			$this->set(compact("request"));
			$this->render("empresa");
		}


	}

	private function getPosition($arr,$selected){
		$pos = 0;
		foreach ($arr as $key => $value) {
			if($selected == $pos){
				return [$key => $value];
			}else{
				$pos++;
			}
		}
	}

	public function reject($request_id = null){
		$this->layout = false;

		if($this->request->is("post")){
			$this->autoRender = false;
			$this->request->data["CreditsRequest"]["id"] = $this->decrypt($this->request->data["CreditsRequest"]["id"]);
			$this->request->data["CreditsRequest"]["state"] = 4;
			$this->request->data["CreditsRequest"]["date_admin"] = date("Y-m-d H:i:s");

			if ($this->CreditsRequest->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$this->set("request_id",$request_id);
	}

	public function empresas(){
		$this->autoRender = false;
		if ($this->request->is("post")) {
			$this->loadModel("Document");
			$this->loadModel("CreditsRequestsComment");

			$data = $this->request->data;
			if (isset($data["CreditsRequestsComment"]) && !empty($data["CreditsRequestsComment"])) {
				$this->CreditsRequestsComment->create();
				$this->CreditsRequestsComment->save($data["CreditsRequestsComment"]);
			}
			$this->CreditsRequest->save($data["CreditsRequest"]);

			if (isset($data["Document"]) && !empty($data["Document"])) {
				foreach ($data["Document"] as $key => $value) {
					if (empty($value["file"]["name"])) {
                        continue;
                    }

                    $value["credits_request_id"] = $data["CreditsRequest"]["id"];
                    $value["user_id"]       = AuthComponent::user("id");
                    $value["state"]         = 1;
                    $value["type"]          = 2;
                    $value["state_request"] = $data["CreditsRequest"]["state"];
                    $this->Document->create();
                    $this->Document->save($value);
                }
			}
		}
		$this->redirect(["action"=>"index"]);
	}

	public function approve($request_id = null){
		$this->layout = false;
		$request = $this->CreditsRequest->findById($this->decrypt($request_id));
		if($this->request->is("post")){
			$this->autoRender = false;


			$this->request->data["CreditsRequest"]["id"] = $this->decrypt($this->request->data["CreditsRequest"]["id"]);
			$this->request->data["CreditsRequest"]["number_approve"] = $request["CreditsRequest"]["request_number"];
			$this->request->data["CreditsRequest"]["state"] = 3;
			$this->request->data["CreditsRequest"]["date_admin"] = date("Y-m-d H:i:s");

			$datosLimit = [
				"CreditLimit" => [
					"value" 	 			=> $this->request->data["CreditsRequest"]["value_approve"],
					"state" 	 			=> 1,
					"reason"	 			=> "Aprobación de cupo",
					"type_movement" 		=> 1,
					"credits_request_id" 	=> $this->request->data["CreditsRequest"]["id"],
					"user_id"			 	=> AuthComponent::user("id"),
					"deadline"			 	=> date("Y-m-d",strtotime("+1 month")),
					"customer_id"			=> $request["CreditsRequest"]["customer_id"]
				]
			];

			if ($request["CreditsRequest"]["extra"] != 1) {
				$this->CreditsRequest->CreditLimit->updateAll(
					["CreditLimit.active" => 0],
					["CreditLimit.state"  => [1,3,4,5],
					"CreditLimit.customer_id" => $request["CreditsRequest"]["customer_id"]
					]
				);
			}


			if ($this->CreditsRequest->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->CreditsRequest->CreditLimit->create();
				$this->CreditsRequest->CreditLimit->save($datosLimit);

				$creditId 			= $this->Credit->id;

				$this->loadModel("ShopsDebt");
				$this->loadModel("Shop");

				// if(!empty($request["CreditsRequest"]["response_score"])){

				// 	$dataDebtShop = array(
				// 		"ShopsDebt" => [
				// 			"user_id" => AuthComponent::user("id"),
				// 			"shop_commerce_id" => $request["CreditsRequest"]["shop_commerce_id"],
				// 			"credit_id" => null,
				// 			"type"	=> 1,
				// 			"value" => 8000,
				// 			"reason" => "Consulta en central de riesgo",
				// 			"state" => 0,
				// 		]
				// 	);

				// 	$this->ShopsDebt->create();
				// 	$this->ShopsDebt->save($dataDebtShop);
				// }

				if (!is_null($request["Customer"]["email"]) && !empty($request["Customer"]["email"]) ) {
					$options = [
						"subject" 	=> "Tu Solicitud de Crédito ha sido APROBADA",
						"to"   		=> $request["Customer"]["email"],
						"vars" 	    => [],
						"template"	=> "credit_approve",
					];
					$this->sendMail($options);
				}

			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		$this->set("request_id",$request_id);
		$this->set("request",$request);

	}


	public function validate_limits(){
		$this->loadModel("ShopCommerce");
		$this->autoRender 	= false;
		$identification 	= $this->request->data["identification"];
		$customer 			= $this->CreditsRequest->Customer->findByIdentification($identification);
		$total 				= 0;

		if (!empty($customer)) {
			$total 	= $this->totalQuote(true,$customer["Customer"]["id"]);
			$commID = $this->ShopCommerce->field("id",["code" => '73221084',"state" => 1]);
			$data  	= [
						"total" 		=> $total,
						"customer_id" 	=> $this->encrypt($customer["Customer"]["id"]),
						"customer_name" => $customer["Customer"]["name"]." ".$customer["Customer"]["last_name"],
						"customer_cc" 	=> $customer["Customer"]["identification"],
						"type"			=> empty($customer["Customer"]["email"]) ? 0 : 1,
						"numberq"		=> 4,
						"commerce"		=> $commID
					];
		}else{
			$data  = ["total" => $total ];
		}
		return json_encode($data);
	}


	public function search_customer(){
		$this->layout = false;
		$cc = $this->request->data["cc"];
		$customer = $this->CreditsRequest->Customer->findByIdentification($cc);

		$totalProcesoOtros 	= 0;
		$totalProcesoTienda = 0;

		$totalRechazoTienda = 0;
		$totalRechazoOtros  = 0;

		$totalAprobadoSinDesembolsoTienda = 0;
		$totalAprobadoSinDesembolsoOtros  = 0;

		$totalActivosOtros = 0;
		$totalActivosTieda = 0;

		$creditsTiendaAproveNoDas = [];
		$totalPreAprovvedTienda = 0;
		$totalPreaprovved = 0;
		$juridico = 0;
		$totalCupoAprobado=0;

		if(!empty($customer)){

			if(AuthComponent::user("role") == 4){
				$shop_commerce_id = $this->CreditsRequest->ShopCommerce->find("all",["fields"=>["id"],"conditions"=>["ShopCommerce.shop_id"=>AuthComponent::user("shop_id")]]);
				if(!empty($shop_commerce_id)){
					$shop_commerce_id = Set::extract($shop_commerce_id,"{n}.ShopCommerce.id");
				}
				if(AuthComponent::user("role") == 4){
					$shopCommerces = $this->CreditsRequest->ShopCommerce->find("list",["fields"=>["id","name"],"conditions"=>["ShopCommerce.shop_id"=>AuthComponent::user("shop_id")]]);
					$this->set(compact("shopCommerces"));
				}
			}else{
				$shop_commerce_id = [AuthComponent::user("shop_commerce_id")];
			}


			foreach ($customer["Credit"] as $key => $value) {
				if ($value["juridico"] == 1) {
					$juridico++;
				}
			}

			foreach ($customer["CreditsRequest"] as $key => $value) {

				if (in_array($value["state"], [0,1,2]) &&
					in_array($value["shop_commerce_id"], $shop_commerce_id)
					&& $value["extra"] =0 ) {
					$totalProcesoTienda++;
				}
				if (in_array($value["state"], [0,1,2]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) && $value["extra"] =0) {
					$totalProcesoOtros++;
				}

				if (in_array($value["state"], [4]) && in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalRechazoTienda++;
				}
				if (in_array($value["state"], [4]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalRechazoOtros++;
				}

				if (in_array($value["state"], [3]) && in_array($value["shop_commerce_id"], $shop_commerce_id) && $value["extra"] =0) {
					$totalAprobadoSinDesembolsoTienda++;
					$creditsTiendaAproveNoDas[] = $value["id"];
				}
				if (in_array($value["state"], [3]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) && $value["extra"] =0) {
					$totalAprobadoSinDesembolsoOtros++;
				}

				if (in_array($value["state"], [5,6]) && in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalActivosTieda++;
				}
				if (in_array($value["state"], [5,6]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalActivosOtros++;
				}

			}


			foreach ($customer["CreditLimit"] as $key => $value) {
				if(in_array($value["state"], [1]) && $value["active"] == 1 && in_array($value["credits_request_id"], $creditsTiendaAproveNoDas) ){
					$totalPreAprovvedTienda++;
				}

				//sumar el cupo aprobado
				if ($value["reason"]=='Aprobación de cupo') {
					$totalCupoAprobado+= $value['value'];
				}
			}

			$calculoCustomer = $this->totalQuote(true,$customer["Customer"]["id"],true,1);
			$totalPreaprovved=$calculoCustomer[0];
			$mora=$calculoCustomer[1];
			$creditsCliente = $this->CreditsRequest->Credit->find("all",["conditions" => ["Credit.state" => 0,"Credit.customer_id" => $customer["Customer"]["id"],"Credit.credits_request_id !=" => 0 ]]);

			$this->set("creditsCliente",$creditsCliente);

		}


		$this->set(compact("totalProcesoTienda","totalProcesoOtros","totalRechazoTienda","totalRechazoOtros","totalProcesoOtros","totalAprobadoSinDesembolsoTienda","totalAprobadoSinDesembolsoOtros","totalActivosTieda","totalActivosOtros","totalPreaprovved","customer","totalPreAprovvedTienda","juridico","totalCupoAprobado","mora"));

	}

	public function pagare()
	{
		$this->loadModel("Signature");
		$text  = $this->Signature->field("initial",["id"=>1]);
		// $text  = $this->Signature->field("full_text",["id"=>1]);

		$this->pdfConfig            = array(
                        'download'          => true,
                        'paper'             => 'A4',
                        // 'options'           => ['outline' => true,],
                        'filename'          => "prueba".'.pdf',
                        'orientation'       => 'Potrait'

        );
        // $this->set("text",$text);
	}

	public function replaceVarsText($text,$request){
		$this->loadModel("CustomersCode");

		$codigo = $this->CustomersCode->field("code",[
			"customer_id"=>$request["Customer"]["id"], "credits_request_id" => $request["CreditsRequest"]["id"],
			"type_code" => 2, "state" => 1 ]
		);

		if (is_null($codigo) || $codigo == false) {
			$codigo = $this->CustomersCode->field("code",[
				"customer_id"=>$request["Customer"]["id"], "credits_request_id" => $request["CreditsRequest"]["id"],
				"type_code" => 2 ]
			);
		}

		$this->loadModel("Customer");
		$customer=$this->Customer->find("first",["conditions"=>["Customer.id"=>$request["Customer"]["id"]]]);
		$celular= $customer["CustomersPhone"][0]["phone_number"];
		$direccion= $customer["CustomersAddress"][0]["address"];
		$fechaActual=Date("Y-m-d");
		return str_replace(
			["@nombre@","@#credito@","@cedula@","@monto_letras@","@monto_numeros@","@ip@","@fecha@",'@codigo@','@email@','@celular@','@direccion@','@fechaActual@'],
			[
				strtoupper($request["Customer"]["name"])." ".strtoupper($request["Customer"]["last_name"]),
				$request["CreditsRequest"]["code_pay"],
				$request["Customer"]["identification"],
				strtoupper(CifrasEnLetras::convertirNumeroEnLetras($request["CreditsRequest"]["value_approve"])),
				number_format($request["CreditsRequest"]["value_approve"]),
				$_SERVER['REMOTE_ADDR'],
				is_null($request["CreditsRequest"]["credit_id"]) ? "" : $request["Credit"]["created"],
				is_null($request["CreditsRequest"]["credit_id"]) ? $codigo : $codigo,
				$request["Customer"]["email"],
				$celular,
				$direccion,
				$fechaActual
			], $text
		);
	}

	public function sendCodesCredit(){
		header('Access-Control-Allow-Origin: *');
	  	$this->response->header('Access-Control-Allow-Origin', '*');
		$this->autoRender = false;
		$id = $this->decrypt($this->request->data["request"]);

		$this->loadModel("Signature");
		$initial_text  = $this->Signature->field("initial",["id"=>1]);
		$idFileInitial = uniqid($this->request->data["request"]);
		$id 		   = $this->decrypt($this->request->data["request"]);
		$request 	   = $this->CreditsRequest->findById($id);

		$requestCopy["CreditsRequest"]["value_approve"] = $this->request->data["valorCredito"];
		//generar codigo al credito de 13 digitos
		if (!isset($request["CreditsRequest"]["code_pay"]) || empty($request["CreditsRequest"]["code_pay"])) {
			$request["CreditsRequest"]["code_pay"]=$this->generarCodigoCredito();
		}

		$requestCopy   = $request;
		$initial_text  = $this->replaceVarsText($initial_text,$requestCopy);

		$options = array(
	        'template' => 'initial_pagare',
	        'ruta' => APP . 'webroot' . DS . 'files' . DS . 'pagares' . DS . 'initals'. DS . $idFileInitial . ".pdf",
	        'vars' => compact("initial_text"),
	    );
	    $this->generatePdf($options);

	    $request["CreditsRequest"]["pagare_inicial"] = $idFileInitial . ".pdf";


		//generar codigo de credito
	    $this->CreditsRequest->save($request["CreditsRequest"]);
		$creditCodes = $this->getCodesCustomer($this->CreditsRequest->field("customer_id",["id"=>$id]),$id);
		// $codeEmail = $this->encrypt($creditCodes["codeEmail"]);
		$codePhone = $this->encrypt($creditCodes["codePhone"]);
		$codeEmail = "";

	    if (!is_null($request["Customer"]["email"])) {
	    	$options = [
				"subject" 	=> "Pagaré inicial Zíro",
				"to"   		=> $request["Customer"]["email"],
				"vars" 	    => [],
				"template"	=> "initial_pagare",
				"file" 		=> 'files' . DS . 'pagares' . DS . 'initals'. DS . $idFileInitial . ".pdf"
			];

			$this->sendMail($options);

			//enviar codigo al cliente
			$options = [
				"subject" 	=> "¡Ey!, Corre y autoriza tu crédito en Zíro",
				"to"   		=> $request["Customer"]["email"],
				"vars" 	    => [
					'dataRequest'=>$request,
					'data'=>$this->request->data,
					'code' => $creditCodes["codePhone"]
				],
				"template"	=> "code_generated",
			];
			$this->sendMail($options);
	    }


		return json_encode(compact("codeEmail","codePhone"));
	}

	public function generarCodigoCredito() {
        $caracteres_permitidos = '0123456789012';
        $longitud = 13;
        $code_pay= substr(str_shuffle($caracteres_permitidos), 0, $longitud);
        $this->loadModel("CreditsRequest");
        $existeCodigo= $this->CreditsRequest->field("id", ["code_pay" => $code_pay]);
        $flagCodePay=false;
        if (!$existeCodigo) {
            $flagCodePay=true;
        }
        while (!$flagCodePay) {
            $code_pay= substr(str_shuffle($caracteres_permitidos), 0, $longitud);
            $existeCodigo= $this->CreditsRequest->field("id", ["code_pay" => $code_pay]);
            if (!$existeCodigo) {
                $flagCodePay=true;
            }
        }
        return $code_pay;
    }


	public function validateCode(){
		$this->autoRender = false;
		$this->loadModel("CustomerCodes");

		$id = $this->decrypt($this->request->data["request"]);
		// $creditCodes = $this->getCodesCustomer($this->CreditsRequest->field("customer_id",["id"=>$id]),$id);

		if($this->request->data["codeMail"] != $this->decrypt($this->request->data["codeMailRequest"]) || $this->request->data["codePhone"] != $this->decrypt($this->request->data["codePhoneRequest"]) ){
	        return "1";
	    }else{
	    	$this->loadModel("CustomersCode");
        	$this->CustomersCode->recursive = -1;

        	// $validTimeEmail = $this->CustomersCode->findByCodeAndCustomerIdAndTypeCodeAndStateAndCreditsRequestId($this->request->data["codeMail"],$this->CreditsRequest->field("customer_id",["id"=>$id]),1,0,$id);

        	$validTimeEmail = true;

        	$validTimePhone = $this->CustomersCode->findByCodeAndCustomerIdAndTypeCodeAndStateAndCreditsRequestId($this->request->data["codePhone"],$this->CreditsRequest->field("customer_id",["id"=>$id]),2,0,$id);

        	if(empty($validTimeEmail) || empty($validTimePhone)){
	          return "2";
	        }else{
	        	$validTimeEmail["CustomersCode"]["state"] = 1;
		        $validTimePhone["CustomersCode"]["state"] = 1;

		        // $this->CustomersCode->save($validTimeEmail);
		        $this->CustomersCode->save($validTimePhone);
		        return "3";
	        }
	    }
	}

	public function create_request_approved(){
		$this->loadModel("CreditsLine");
		$this->autoRender 	= false;
		$valor 				= $this->request->data["value"];
		$creditLineId 		= $this->CreditsLine->findByState(1);
		$lastRequest	    = $this->CreditsRequest->find("first",["recursive" => 1, "conditions" => ["CreditsRequest.customer_id"=>$this->decrypt($this->request->data["customer"]), "CreditsRequest.state" => [3,5,6] ], "order" => ["CreditsRequest.id" => "DESC" ] ]);

		$dateLimit = date("Y-m-d",strtotime("+1 month"));

		$dataRequest = array(
			"CreditsRequest" => [
				"id"			   => null,
				"customer_id" 	   => $this->decrypt($this->request->data["customer"]),
				"request_value"    => $valor,
				"request_number"   => 4,
				"credits_line_id"  => $creditLineId["CreditsLine"]["id"],
				"shop_commerce_id" => $this->request->data["commerce"],
				"user_id" 		   => $lastRequest["CreditsRequest"]["user_id"],
				"date_admin"	   => date("Y-m-d H:i:s"),
				"total_score"	   => $lastRequest["CreditsRequest"]["total_score"],
				"vars_score"	   => $lastRequest["CreditsRequest"]["vars_score"],
				"number_approve"   => 4,
				"value_approve"    => $valor,
				"state"			   => 3,
				"transfer" 		   => isset($this->request->data["transfer"]) ? 1 : 0,
			]
		);

		$requestDatas = $this->CreditsRequest->findAllByStateAndCustomerId(3,$this->decrypt($this->request->data["customer"]) );

		if(!empty($requestDatas)){
			foreach ($requestDatas as $key => $value) {
				$value["CreditsRequest"]["state"] = 7;
				$this->CreditsRequest->save($value["CreditsRequest"]);
			}
		}

		$this->CreditsRequest->create();
		if($this->CreditsRequest->save($dataRequest)){
			$creditRequestId = $this->CreditsRequest->id;
			$dataComment = [
				"CreditsRequestsComment" => [
					"type" 					=> "Se aprueba por preaprobado",
					"user_id" 				=> $lastRequest["CreditsRequest"]["user_id"],
					"credits_request_id" 	=> $creditRequestId,
					"comment" 				=> "Se aprueba por preaprobado"
				]
			];
			$this->CreditsRequest->CreditsRequestsComment->create();
			$this->CreditsRequest->CreditsRequestsComment->save($dataComment);

			if (!isset($this->request->data["transfer"])) {
				$datosLimit = [
					"CreditLimit" => [
						"value" 	 			=> $valor,
						"state" 	 			=> 5,
						"reason"	 			=> "Preaprobado de cupo",
						"type_movement" 		=> 1,
						"credits_request_id" 	=> $creditRequestId,
						"user_id"			 	=> AuthComponent::user("id"),
						"deadline"			 	=> $dateLimit,
						"customer_id"			=> $dataRequest["CreditsRequest"]["customer_id"]
					]
				];

				$this->CreditsRequest->CreditLimit->updateAll(
					["CreditLimit.active" => 0],
					["CreditLimit.state"  => [1,3,4,5],
					 "CreditLimit.customer_id" => $dataRequest["CreditsRequest"]["customer_id"]
					 ]
				);

				$this->CreditsRequest->CreditLimit->create();
				$this->CreditsRequest->CreditLimit->save($datosLimit);

				$datosLimit["CreditLimit"]["type_movement"] = 2;
				$datosLimit["CreditLimit"]["state"] = 8;
				$this->CreditsRequest->CreditLimit->create();
				$this->CreditsRequest->CreditLimit->save($datosLimit);
			}

			// if (!empty($lastRequest["Customer"]["email"]) ) {
			// 	$options = [
			// 		"subject" 	=> "Tu Solicitud de Crédito ha sido APROBADA",
			// 		"to"   		=> $lastRequest["Customer"]["email"],
			// 		"vars" 	    => [],
			// 		"template"	=> "credit_approve",
			// 	];
			// 	$this->sendMail($options);
			// }

			return $this->encrypt($creditRequestId);
		}
		return 0;
	}

	public function revertjuridico(){
		$this->autoRender = false;

		$id 	= $this->request->data["id"];
		$CustomerID  = $this->request->data["customer_id"];

	//	return json_encode(compact("id"));
		$this->CreditsRequest->Credit->CreditsPlan->revertJuridico($id,$CustomerID);

		$this->CreditsRequest->Credit->updateAll(
			["Credit.juridico" => 0 ],
			["Credit.customer_id" => $CustomerID]
		);


	}

}
