<?php
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
        $this->Auth->allow('create_from_crediventas','validate_limits','create_request_approved','validateCode','applyCredit','sendCodesCredit');
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



public function create_from_crediventas(){
		$this->autoRender = false;
		$data = $this->request->data;

		$this->loadModel("Customer");
		$this->loadModel("ShopCommerce");

		$existsCommerce = $this->ShopCommerce->field("id",["code" => '73221084',"state" => 1]);

		if(!$existsCommerce){
          return -2;
      	}else{
      		$customer = $this->Customer->find("first",["conditions" => ["identification"=>$this->request->data["Customer"]["identification"]],"recursive" => -1 ]);

      		if(!empty($customer)){
	            $this->loadModel("CreditsRequest");
	            $actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState($customer["Customer"]["id"],$existsCommerce,[0,1,2]);

	            if(!empty($actualStudy)){
	              return -1;
	            }
	        }
	        if(empty($customer)){
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
	              return 1;
	            }
	          }else{
	            return -3;
	          }

      	}


		return json_encode($customer);
		die;

		return json_encode($this->request->data);
		die;
	}

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

	public function assing_user(){
		$this->autoRender= false;
		$request = $this->decrypt($this->request->data["request"]);
		$user_id = $this->request->data["user_id"];

		$request = $this->CreditsRequest->find("first",["recursive" => -1, "conditions"=> ["CreditsRequest.id"=> $request] ]);

		$request["CreditsRequest"]["state"] = 1;
		$request["CreditsRequest"]["user_id"] = $user_id;

		$this->CreditsRequest->save($request);
		$this->Session->setFlash(__('El crédito fue asignado correctamente.'), 'flash_success');
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
				$datos = $this->CreditsRequest->Credit->CreditsPlan->getQuotesJuridico(null,$customer);
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

				$type = AuthComponent::user("role") == 11 ? 1 : 0;

				$conditions["Commitment.type"]  = $type;
				$conditions1["Commitment.type"] = $type;
				$conditions2["Commitment.type"] = $type;

				$commitmentsToday 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions ]);
				$commitmentsWeek 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions1 ]);
				$commitmentsNoAdmin = $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions2  ]);

				$users = $this->Commitment->User->findAllByRole(9);

				$this->set(compact("commitmentsToday","commitmentsWeek","commitmentsNoAdmin","users"));
			}

			$this->set("tab",$tab);
		}
	}

	public function cobranza(){

		if (AuthComponent::user("role") == 11) {
			$this->redirect(["action"=>"juridico"]);
		}

		$page = !isset($this->request->query["page"]) ? 1 : $this->request->query["page"];
		$pages = 1;

		if(!isset($this->request->query["range"])){
			$iniDay = 1;
			$endDay = 15;//120

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

				$limit = 50;
				$start = ($page - 1) * $limit;

				$datosCuotas = $this->CreditsRequest->Credit->CreditsPlan->getQuotesCobranzas(null,$iniDay,$endDay,$customer,$start,$limit);

				$total = $this->CreditsRequest->Credit->CreditsPlan->getQuotesCobranzasCount(null,$iniDay,$endDay,$customer);
				$pages = ceil( $total / $limit );

				$dataPhone = [];

				if (!empty($datosCuotas)) {
					foreach ($datosCuotas as $key => $value) {
						$dataPhone[$value["CreditsPlan"]["id"]] = $value["Credit"]["customer_id"];
					}
				}

				$this->set("dataPhone",$dataPhone);
				$this->set("datosCuotas",$datosCuotas);

			}else{

				if (date("D")=="Mon"){
				     $week_start = date("Y-m-d");
				} else {
				     $week_start = date("Y-m-d", strtotime('last Monday', time()));
				}
				$week_end = strtotime('next Sunday', time());
				$week_end = date('Y-m-d', $week_end);

				//echo '<br>' .$week_start;
				//echo '<br>' .$week_end;

				$this->loadModel("Commitment");

				$conditions  = ["Commitment.deadline" => date("Y-m-d"),"Commitment.state" => [0,2] ];
				$conditions1 = ["DATE(Commitment.deadline) >=" => $week_start, "DATE(Commitment.deadline) <=" => $week_end ];
				$conditions2 = ["DATE(Commitment.deadline) <" => date("Y-m-d"), "Commitment.state" => 0 ];
				$conditions3 = ["Commitment.state" => 1 ];

				if(isset($this->request->query["user"])){
					$conditions["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
					$conditions1["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
					$conditions2["Commitment.user_id"] = $this->decrypt($this->request->query["user"]);
				}

				$type = AuthComponent::user("role") == 11 ? 1 : 0;

				$conditions["Commitment.type"]  = $type;
				$conditions1["Commitment.type"] = $type;
				$conditions2["Commitment.type"] = $type;
				$conditions3["Commitment.type"] = $type;

				$allQuotesJuridic = $this->CreditsRequest->Credit->CreditsPlan->find("all",["conditions" => ["Credit.juridico" => 1] ]);

				if (!empty($allQuotesJuridic)) {
					$allIds = Set::extract($allQuotesJuridic, "{n}.CreditsPlan.id"); //credidos //credit plan/ id (4 y 16) * 20
					$conditions["Commitment.credits_plan_id != "] = $allIds;
					$conditions1["Commitment.credits_plan_id != "] = $allIds;
					$conditions2["Commitment.credits_plan_id != "] = $allIds;
					$conditions3["Commitment.credits_plan_id != "] = $allIds;
				}

				/*$this->Commitment->recursive 	= 3;
				//$this->Paginator->settings 			= array('order'=>$order);
				$commitmentsToday 					= $this->Paginator->paginate(null, $conditions);
				*/
				$commitmentsToday 	= $this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions ]);
				$commitmentsWeek 	= [];//$this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions1 ]);
				$commitmentsNoAdmin = [];//$this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions2  ]);
				$commitmentsEnd = [];//$this->Commitment->find("all",["recursive" => 3, "conditions" => $conditions3  ]);

				$users = $this->Commitment->User->findAllByRole(9);

				$this->set(compact("commitmentsToday","commitmentsWeek","commitmentsNoAdmin","users","commitmentsEnd"));
			}

			$this->set("pages",$pages);
			$this->set("page",$page);
			$this->set("tab",$tab);
		}

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

		if (!empty($actualStudy)) {
			$this->Session->setFlash(__('Ya tienes otra solicitud en proceso no es posible solicitar este aumento'), 'flash_error');
		}else{
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
		    $this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');

		}
	    $this->redirect(["controller"=>"pages","action"=>"dashboardcliente"]);
	}

	public function applyCredit(){
		$this->autoRender = false;
		$id_request = $this->decrypt($this->request->data["id_request"]);

		$this->CreditsRequest->recursive = -1;
		$this->CreditsRequest->CreditsLine->recursive = -1;
		$creditRequest  = $this->CreditsRequest->findById($id_request);
		$activeLine 	= $this->CreditsRequest->CreditsLine->findByState(1);

        $creditLineId = $activeLine["CreditsLine"]["id"];


        $creditLineDetail = $this->CreditsRequest->query("SELECT * FROM credits_lines_details where credit_line_id =".$creditLineId);

	//	$this->Session->setFlash(__('Solicitud creada correctamente' . json_encode($creditLineDetail)), 'flash_success');

		$cuoteValuesData = $this->calculate_qoute($this->request->data["valueNumberQ"],$this->request->data["valueCredit"],$this->request->data["frecuency"]);

		$valueCredit     = $this->request->data["valueCredit"];


		$frecuenty = $this->request->data["valueNumberQ"];

		foreach ($creditLineDetail as $key => $value) {
			if((($valueCredit >= $value["credits_lines_details"]["min_value"] ) && $frecuenty==$value["credits_lines_details"]["month"]) && ($valueCredit <= $value["credits_lines_details"]["max_value"] )) {
			   $intRate = $value["credits_lines_details"]["interest_rate"];
			   $intOther = $value["credits_lines_details"]["others_rate"];

			}
		}

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
						"capital_value" 	=> round($totalCapitalDeuda) < 0 || round($totalCapitalDeuda) < 2000 ?  ($ultimoCap==0?floatval($capitalC):$ultimoCap) : floatval($capitalC),
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

	}

	private function sendMailCredit($creditId){
		$credit 		= $this->CreditsRequest->Credit->findById($creditId);
		$shopCommerce 	= $this->CreditsRequest->ShopCommerce->findById($credit["CreditsRequest"]["shop_commerce_id"]);
		if(!empty($credit["Customer"]["email"])){
			$options = [
				"subject" 	=> "Crédito aprobado - Plan de pagos",
				"to"   		=> $credit["Customer"]["email"],
				"vars" 	    => ["credit" => $credit,"shop_commerce" => $shopCommerce],
				"template"	=> "credit_approve_final",
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
		$creditInfo 	= $this->CreditsRequest->Credit->findById($this->decrypt($id));

		$quotes 		= $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($this->decrypt($id));

		$creditRequest 	= $this->CreditsRequest->findById($creditInfo["Credit"]["credits_request_id"]);

		$totalCredit  = $this->CreditsRequest->Credit->CreditsPlan->getTotalDeudaCredit($this->decrypt($id));

		$user 			= $this->CreditsRequest->Credit->Customer->User->findByCustomerId($creditRequest["CreditsRequest"]["customer_id"]);

		$this->set("totalCredit",$totalCredit);


		//INICION

	//$creditRequest = $this->Credit->CreditsRequest->findById($this->decrypt($creditId));
      //  $creditInfo = $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);

        $quotes = $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

        $totalCredit = $this->CreditsRequest->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);

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
        foreach ($quotes as $key => $value) {
            if ($cuenta > 0) {
                $pagoA = ($value["CreditsPlan"]["state"]);
                if ($pagoA == 1) {
                    $cuotaacumulada = $cuotaacumulada + $value["CreditsPlan"]["capital_value"];
                }
            }
            $cuenta--;
        }

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

            if ($days == 31) {
                $days = 30;
            }

            if ($firstDate != $creditInfo["CreditsRequest"]["date_disbursed"]) {

                $interesesT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
                //Fin Interes corriente

                //otros intereses
                $interesesOT = (($dateUltPago < $secondDate)) ? ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days : ((($deudaF * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

                //capital
                $CapitalN = $creditInfo["Credit"]["quota_value"] - $interesesOT - $interesesT;

            } else {

                $interesesT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["interes_rate"]) / 100) / 30) * $days;
                //Fin Interes corriente

                //otros intereses
                $interesesOT = ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

                //capital
                $CapitalN = $creditInfo["Credit"]["quota_value"] - $interesesOT - $interesesT;

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
                ["CreditsPlan.capital_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($CapitalN) : ROUND($value["CreditsPlan"]["capital_value"]),
                    "CreditsPlan.interest_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesT) : ROUND($value["CreditsPlan"]["interest_value"]),
                    "others_value" => (($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0 and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesOT) : ROUND($value["CreditsPlan"]["others_value"])],
                ["CreditsPlan.id" => $value["CreditsPlan"]["id"]]
            );

        }

		$creditRequest 	= $this->CreditsRequest->findById($creditInfo["Credit"]["credits_request_id"]);
		$creditInfo 	= $this->CreditsRequest->Credit->findById($this->decrypt($id));
        //$creditRequest = $this->Credit->CreditsRequest->findById($this->decrypt($creditId));
       // $creditInfo = $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);

        $quotes = $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

        $totalCredit = $this->CreditsRequest->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);

        $totalCap = 0;
        $plan_id = 0;
        $valorUltQ = 0;

        $totalCap = $this->CreditsRequest->Credit->CreditsPlan->find("first", ["conditions" => ["credit_id" => $value["CreditsPlan"]["credit_id"]], "fields" => ["SUM(capital_value) as total"]]);

        if (!empty($totalCap)) {

            $totalCap = $totalCap["0"]["total"];

            if ($creditInfo["CreditsRequest"]["value_disbursed"] > $totalCap) {

                $diferenciaQ = $totalCap;
                //$creditInfo["CreditsRequest"]["value_disbursed"] -
                $plan_id = $this->CreditsRequest->Credit->CreditsPlan->field("CreditsPlan.id", ["credit_id" => $value["CreditsPlan"]["credit_id"]], ["id" => "DESC"]);
                //"order" =>["id"=>"DESC"]
                $valorUltQ = $this->CreditsRequest->Credit->CreditsPlan->field("CreditsPlan.capital_value", ["credit_id" => $value["CreditsPlan"]["credit_id"]], ["id" => "DESC"]);

                $diferenciaQ = $diferenciaQ - $valorUltQ;

                $this->loadModel("CreditsPlan");
                $this->CreditsPlan->updateAll(
                    [
                        "CreditsPlan.capital_value" => $creditInfo["CreditsRequest"]["value_disbursed"] - $diferenciaQ,
                    ],
                    ["CreditsPlan.id" => $plan_id]
                );

            } //add

        }

		$creditRequest 	= $this->CreditsRequest->findById($creditInfo["Credit"]["credits_request_id"]);
		$creditInfo 	= $this->CreditsRequest->Credit->findById($this->decrypt($id));

        $quotes = $this->CreditsRequest->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);

        $totalCredit = $this->CreditsRequest->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);

        for ($i = 0; $i < sizeof($quotes); $i++) {

            $pay = $this->CreditsRequest->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quotes[$i]["CreditsPlan"]["id"] . " ' ");
            $quotes[$i]["CreditsPlan"] += ["TotalAbo" => $pay[0][0]["PaymentA"]];
        }
		//FIN


		$this->set(compact("creditRequest","layout","creditInfo","quotes","user"));
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

		// foreach ($vars as $key => $value) {
		// 	$totalValue = count($value)-1;
		// 	$copyValue  = array_values($value);
		// 	$randomValue = rand(0,$totalValue);
		// 	$selectedVar = $this->getPosition($value,$randomValue);
		// 	$selectedValue = $copyValue[$randomValue];
		// 	$ponderadoValue = $ponderados[$key];

		// 	$total+= ( $selectedValue*$ponderadoValue );
		// 	$varsSelected[$key] = $selectedVar;
		// }
		$this->set(compact("varsSelected","total","request"));
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

	public function approve($request_id = null){
		$this->layout = false;
		$request = $this->CreditsRequest->findById($this->decrypt($request_id));

		if($this->request->is("post")){
			$this->autoRender = false;
			$this->request->data["CreditsRequest"]["id"] = $this->decrypt($this->request->data["CreditsRequest"]["id"]);

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

			foreach ($customer["CreditsRequest"] as $key => $value) {
				if (in_array($value["state"], [0,1,2]) && in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalProcesoTienda++;
				}
				if (in_array($value["state"], [0,1,2]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalProcesoOtros++;
				}

				if (in_array($value["state"], [4]) && in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalRechazoTienda++;
				}
				if (in_array($value["state"], [4]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalRechazoOtros++;
				}

				if (in_array($value["state"], [3]) && in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
					$totalAprobadoSinDesembolsoTienda++;
					$creditsTiendaAproveNoDas[] = $value["id"];
				}
				if (in_array($value["state"], [3]) && !in_array($value["shop_commerce_id"], $shop_commerce_id) ) {
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
			}

			$totalPreaprovved = $this->totalQuote(true,$customer["Customer"]["id"]);

			$creditsCliente = $this->CreditsRequest->Credit->find("all",["conditions" => ["Credit.state" => 0,"Credit.customer_id" => $customer["Customer"]["id"],"Credit.credits_request_id !=" => 0 ]]);

			$this->set("creditsCliente",$creditsCliente);

		}

		$this->set(compact("totalProcesoTienda","totalProcesoOtros","totalRechazoTienda","totalRechazoOtros","totalProcesoOtros","totalAprobadoSinDesembolsoTienda","totalAprobadoSinDesembolsoOtros","totalActivosTieda","totalActivosOtros","totalPreaprovved","customer","totalPreAprovvedTienda"));

	}

	public function sendCodesCredit(){
		$this->autoRender = false;
		$id = $this->decrypt($this->request->data["request"]);
		$creditRequest= $this->CreditsRequest->findById($id);
		$numeroCredito=$creditRequest['creditRequest']['code_pay'];
		$creditCodes = $this->getCodesCustomer($this->CreditsRequest->field("customer_id",["id"=>$id]),$numeroCredito);
		$codeEmail = $this->encrypt($creditCodes["codeEmail"]);
		$codePhone = $this->encrypt($creditCodes["codePhone"]);
		return json_encode(compact("codeEmail","codePhone"));
	}

	public function validateCode(){
		$this->autoRender = false;
		$this->loadModel("CustomerCodes");

		$id = $this->decrypt($this->request->data["request"]);
		$creditRequest= $this->CreditsRequest->findById($id);
		$numeroCredito=$creditRequest['creditRequest']['code_pay'];
		$creditCodes = $this->getCodesCustomer($this->CreditsRequest->field("customer_id",["id"=>$id]),$numeroCredito);

		if($this->request->data["codeMail"] != $this->decrypt($this->request->data["codeMailRequest"]) || $this->request->data["codePhone"] != $this->decrypt($this->request->data["codePhoneRequest"]) ){
	        return "1";
	    }else{
	    	$this->loadModel("CustomersCode");
        	$this->CustomersCode->recursive = -1;

        	$validTimeEmail = $this->CustomersCode->findByCodeAndCustomerIdAndTypeCodeAndStateAndCreditsRequestId($this->request->data["codeMail"],$this->CreditsRequest->field("customer_id",["id"=>$id]),1,0,$id);

        	$validTimePhone = $this->CustomersCode->findByCodeAndCustomerIdAndTypeCodeAndStateAndCreditsRequestId($this->request->data["codePhone"],$this->CreditsRequest->field("customer_id",["id"=>$id]),2,0,$id);

        	if(empty($validTimeEmail) || empty($validTimePhone)){
	          return "2";
	        }else{
	        	$validTimeEmail["CustomersCode"]["state"] = 1;
		        $validTimePhone["CustomersCode"]["state"] = 1;

		        $this->CustomersCode->save($validTimeEmail);
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

		$this->CreditsRequest->create();

		$requestDatas = $this->CreditsRequest->findAllByStateAndCustomerId(3,$this->decrypt($this->request->data["customer"]) );

		if(!empty($requestDatas)){
			foreach ($requestDatas as $key => $value) {
				$value["CreditsRequest"]["state"] = 7;
				$this->CreditsRequest->save($value["CreditsRequest"]);
			}
		}

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

			if (!empty($lastRequest["Customer"]["email"]) ) {
				$options = [
					"subject" 	=> "Tu Solicitud de Crédito ha sido APROBADA",
					"to"   		=> $lastRequest["Customer"]["email"],
					"vars" 	    => [],
					"template"	=> "credit_approve",
				];
				$this->sendMail($options);
			}

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




	}

}
