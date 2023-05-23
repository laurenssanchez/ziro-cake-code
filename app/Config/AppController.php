<?php

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
App::uses('CakePdf', 'CakePdf.Pdf');


class AppController extends Controller {
	public $helpers 	= 	array('Time','Utilidades','Minify.Minify');
	public $components = array(
		'Auth' => [
			'authenticate' => array(
	            'Form' => array(
	                'fields' => array('username' => 'email')
	            )
			)
		],
		'Session','Cookie','Paginator');


	public function beforeFilter(){
	  header('Access-Control-Allow-Origin: *');
	  $this->response->header('Access-Control-Allow-Origin', '*');
		  $this->configAuth();
		  $this->validateCustomer();
		  $this->validateCredishop();
		  $this->validateShopCom();
		  $this->validateAdministrative();
		  $this->validateJuridicoQuotes();
	}


  public function validateJuridicoQuotes(){
  	$this->loadModel("CreditsPlan");
  	$this->CreditsPlan->validateJuridicoQuotes();
  }

  public function getDaysMoraCalculo($quote){
  	$MyFechaQuota = new DateTime(date("Y-m-d",strtotime($quote["deadline"])));

	$MyFechaPago  = new DateTime(date("Y-m-d",strtotime($quote["date_payment"])));

	$MyfechaActual =  new DateTime(date("Y-m-d"));

	$FechaComparar = $quote["state"] == 0 ? $MyfechaActual:$MyFechaPago;

	$dias = 0;

	if ($MyFechaQuota <= $FechaComparar) {

		$deadline = $MyFechaQuota;

		$nowDate =  $FechaComparar;//new DateTime(date("Y-m-d"));
		$difference = $deadline->diff($nowDate);
		$days = $difference->days;
        $dias = $days;
	}else{
		$dias = 0;
	}
	return $dias;
  }

  public function getSaldosByCredit($credits)
  {
	  $this->loadModel("Credit");
	  $totalByCredit = [];
	  foreach ($credits as $key => $value) {

		  $commerceData = $this->Credit->CreditsRequest->ShopCommerce->findById($this->Credit->CreditsRequest->field("shop_commerce_id", ["id" => $value["Credit"]["credits_request_id"]]));

		  $totalByCredit[$this->encrypt($value["Credit"]["id"])] = [
			  "values" => [
				  "min_value" => $this->Credit->CreditsPlan->getMinValue($value["Credit"]["id"]),
				  "total" => $this->Credit->CreditsPlan->getCreditDeuda($value["Credit"]["id"],null,null,true),
			  ], "fecha" => date("Y-m-d", strtotime($value["Credit"]["created"])), "numero" => $value["Credit"]["code_pay"], "commerce" => $commerceData["Shop"]["social_reason"] . " - " . $commerceData["ShopCommerce"]["name"],
		  ];
	  }
	  return $totalByCredit;
  }




  	public function generatePdf($options = array()){
        $CakePdf = new CakePdf(["encoding" => "UTF-8"]);
        $CakePdf->template($options['template'], 'default');
        $CakePdf->viewVars($options['vars']);
        $CakePdf->write($options['ruta']);
    }

  public function connect($documento,$method){
    $wsdl   = 'https://procredito.fenalcoantioquia.com/modulo_web_services/ws_consultas/consultas.php?wsdl';
    try {
        $client = new SoapClient($wsdl);
        $params = [
          "tipo_doc" => "1",
          "numero_documento" => strval($documento),
          "codigo" => "560011",
          "usuario" => "JURIBE46",
          "clave" => "Mcredito*2019",
          "idconsulta" => strval(time())
        ];
        $response = $client->__soapCall($method,$params);
        $response = json_decode($response);
    } catch (Exception $e) {
      $response = null;
    }

    return $response;

  }

  public function validateAdministrative(){
  	if(AuthComponent::user("id")){
  		if(in_array(AuthComponent::user("role"), [1,2,3])  ){

  			if($this->request->action != "logout" && !$this->request->is("ajax") && $this->request->action != "login" && AuthComponent::user("validate") == 0 ){
  				$this->Session->setFlash(__('Se deben validar los códigos para poder iniciar sesión.'), 'flash_error');
  				$this->redirect(["controller"=>"users","action" => "login"]);
  			}

  		}
  	}
  }

  	public function getCommercesByShop($shop_id = null,$ids = null){

  		$this->loadModel("ShopCommerce");

  		if(AuthComponent::user("role") == 4){
	  		$shop_id 	= AuthComponent::user("shop_id");
	  	}

	  	$commerces 	= $this->ShopCommerce->find("all",["fields"=>["id"],"recursive"=>-1,"conditions"=>["ShopCommerce.shop_id"=>$shop_id] ]);

  		if(!is_null($ids) && !empty($commerces)){
  			$commerces 	= Set::extract($commerces,"{n}.ShopCommerce.id");
  		}

  		return $commerces;
  	}

  	public function validateCredishop(){
  		if(!AuthComponent::user("id")){
  			return false;
  		}
  		if( in_array(AuthComponent::user("role"), [1,2]) ){
  			$this->loadModel("ShopCommerce");
  			$shops = $this->ShopCommerce->find("list",["fields"=>["id","id"]]);
  			$this->getDeuda($shops);
  		}
  	}

  	public function validateShopCom($return = null){

      if(AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7){
        $saldosCommercios   = [];
        $this->loadModel("ShopCommerce");
        if(AuthComponent::user("role") == 4){

          $shop_id    = AuthComponent::user("shop_id");
          $conditions = ["ShopCommerce.shop_id"=>$shop_id];
          $commerces  = $this->ShopCommerce->find("all",["fields"=>["id"],"recursive"=>-1, "conditions"=> $conditions ]);


          if(!empty($commerces)){
            $commerces      = Set::extract($commerces,"{n}.ShopCommerce.id");
          	$this->getDeuda($commerces,$return);
            foreach ($commerces as $key => $value) {
              $saldo = $this->getSaldos($value,$return);
              if($saldo != 0){
                $saldosCommercios[$value] = ["saldo" => $saldo,"name"=> $this->ShopCommerce->field("name",["id"=>$value]) ];
              }
            }
          }
        }elseif (AuthComponent::user("role") == 7) {
          $this->getDeuda([AuthComponent::user("shop_commerce_id")],$return);
          $saldo = $this->getSaldos(AuthComponent::user("shop_commerce_id"),$return);
          if($saldo != 0){
            $saldosCommercios[AuthComponent::user("shop_commerce_id")] = ["saldo" => $saldo,"name"=> $this->ShopCommerce->field("name",["id"=>AuthComponent::user("shop_commerce_id")]) ];
          }
        }
        if(!is_null($return)){
          return $saldosCommercios;
        }
        $this->set("saldosCommercios",$saldosCommercios);
      }

    }

    public function getDeuda($commerce_id, $return = null){

  		$this->loadModel("Payment");


  		//$payments 		= $this->Payment->field("SUM(values)",["Payment.shop_commerce_id"=>$commerce_id,"state_credishop"=>"0","juridic" => 0]);

		 // echo $commerce_id ;



		 $resp = "";
		 foreach ($commerce_id as $valor) {
			 if (empty($resp)){
				$resp +=$valor;
			 }else{
				$resp = $resp . "," . $valor;
			 }

		 }

		    $payments =  $this->Payment->query("SELECT sum(VALUE) sValues from payments where
       state_credishop=0 AND value>=0 AND receipt_id is not null AND juridic=0 AND shop_commerce_id in (". $resp . ")");



       if (!empty($payments)) {
          $payments  =  $payments[0][0]["sValues"];
	   }else{
		$payments  = 0;
	   }

		if($payments>0){
  			$this->set("debt_credishop",$payments);
  		}

    }


  	public function getSaldos($commerce_id, $return = null){

  		$this->loadModel("ShopsDebt");
  		$this->loadModel("Disbursement");

  		$debts 			= $this->ShopsDebt->field("SUM(value)",["ShopsDebt.shop_commerce_id"=>$commerce_id,"state"=>"0"]);
  		$disbursments 	= $this->Disbursement->find("all",["fields" => ["Disbursement.value","Credit.id"],"conditions" => ["Disbursement.shop_commerce_id"=>$commerce_id,"Disbursement.state"=>"1",'Credit.id !='=>null],"recursive" => 1 ] );

  		if (!empty($disbursments)) {
  			$disbursments = Set::extract($disbursments,"{n}.Disbursement.value");
  			$disbursments = array_sum($disbursments);
  		}

  		$response 		= 0;

		$debts 			= empty($debts) ? 0 : $debts;
		$disbursments 	= empty($disbursments) ? 0 : $disbursments;
  		$response 		= $disbursments-$debts;

  		if(!is_null($return) ){
  			$response = compact("debts","disbursments","response");
  		}

  		return $response;

  	}

  	private function validateCustomer(){
  		if(AuthComponent::user("role") == 5){
  			$this->loadModel("Customer");
  			$this->Customer->recursive = -1;
  			$actionsCustomers = ["register_step_one","register_step_two","register_step_three","register_step_four","home","dashboardcliente"];
  			if($this->request->action != "logout" && !$this->request->is("ajax")){
  				try {
  					if(in_array($this->request->action, $actionsCustomers )){

              if($this->request->action == "home" && AuthComponent::user("customer_new_request") == 5){
                $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 1]]);
                $this->overwrite_session_user(AuthComponent::user('id'));
                $this->redirect(array("controller"=>"pages","action"=>"register_step_one"));
              }

			  			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
			  			if(empty($customer["Customer"]["email"]) || AuthComponent::user("customer_new_request") == 1){
			  				if ($this->request->action == "register_step_one" ) {
			  					return true;
			  				}
			  				$this->redirect(array("controller"=>"pages","action"=>"register_step_one"));
			  			}elseif(empty($customer["Customer"]["date_birth"]) || AuthComponent::user("customer_new_request") == 2){
			  				if ($this->request->action == "register_step_two" ) {
			  					return true;
			  				}
			  				$this->redirect(array("controller"=>"pages","action"=>"register_step_two"));
			  			}elseif($customer["Customer"]["data_full"] == 0 || AuthComponent::user("customer_new_request") == 3){
			  				if($this->request->action == "register_step_three" ){
			  					return true;
			  				}
			  				$this->redirect(array("controller"=>"pages","action"=>"register_step_three"));
			  			}
			  			elseif(AuthComponent::user("customer_complete") == 0 || AuthComponent::user("customer_new_request") == 4){
			  				if($this->request->action == "register_step_four" ){
			  					return true;
			  				}
			  				$this->redirect(array("controller"=>"pages","action"=>"register_step_four"));
			  			}elseif(AuthComponent::user("customer_complete") == 1 || AuthComponent::user("customer_new_request") == 6){
			  				if($this->request->action == "home" ){
			  					return true;
			  				}
                if($this->request->action == "dashboardcliente" ){
                  $this->totalQuote();
                  return true;
                }
			  				$this->redirect(array("controller"=>"pages","action"=>"dashboardcliente"));
			  			}
  					}

	  			} catch (Exception $e) {
  					$this->redirect(["controller"=>"users","action"=>"profile"]);
  				}
  			}
  			$this->totalQuote();
  		}
  	}

	  public function totalQuote($return = null, $client_id = null){
  		$this->loadModel("CreditLimit");
  		$this->loadModel("CreditsPlan");

  		if(is_null($client_id)){
  			$client_id = AuthComponent::user("customer_id");
  		}

  		$dataLimit = $this->CreditLimit->find("all",["conditions"=>["customer_id" => $client_id],"recursive"=>-1]);

  		$total = 0;

  		if(!empty($dataLimit)){
  			foreach ($dataLimit as $key => $value) {
  				if(in_array($value["CreditLimit"]["state"], [1,3,5]) && $value["CreditLimit"]["active"] == 1 ){
  					$total+=$value["CreditLimit"]["value"];
  				}
  			}
  		}

		  $credits = $this->CreditsPlan->Credit->find("list",["fields"=>["id","id"],"conditions"=>["Credit.customer_id" => $client_id, "Credit.credits_request_id <>" => 0]]);

  		if (!empty($credits)) {
        $this->CreditsPlan->update_cuotes_days();
        $this->CreditsPlan->update_credits_days();
  			foreach ($credits as $key => $value) {
  				$this->CreditsPlan->getCuotesInformation($value);
  			}

  			$credits = $this->CreditsPlan->Credit->find("list",["fields"=>["id","quote_days"],"conditions"=>["Credit.customer_id" => $client_id,"Credit.debt"=>1, "Credit.quote_days >"=>10, "Credit.credits_request_id <>" => 0]]);

  			if ( count($credits) >= 1 || (!is_null($credits) && !empty($credits)) ) {
  				$total = 0;
  			}
  		}

		  // getCuotesInformation

  		if ($total < 0) {
  			$total = 0;
  		}

  		if(!is_null($return)){
  			return $total;
  		}

  		$this->set("totalCustomerQuote",$total);

	}

	/*
  	public function totalQuote($return = null, $client_id = null){
  		$this->loadModel("CreditLimit");

  		if(is_null($client_id)){
  			$client_id = AuthComponent::user("customer_id");
  		}

  		$dataLimit = $this->CreditLimit->find("all",["conditions"=>["customer_id" => $client_id],"recursive"=>-1]);

  		$total = 0;

  		if(!empty($dataLimit)){
  			foreach ($dataLimit as $key => $value) {
  				if(in_array($value["CreditLimit"]["state"], [1,3,5]) && $value["CreditLimit"]["active"] == 1 ){
  					$total+=$value["CreditLimit"]["value"];
  				}
  				// if(in_array($value["CreditLimit"]["state"], [0,3,4,6]) && $value["CreditLimit"]["active"] == 1 ){
  				// 	$total-=$value["CreditLimit"]["value"];
  				// }
  			}
  		}

  		if ($total < 0) {
  			$total = 0;
  		}

  		if(!is_null($return)){
  			return $total;
  		}

  		$this->set("totalCustomerQuote",$total);


  	}*/

	private function configAuth(){
  		$this->Cookie->time 			= '30 Days';  // or '1 hour'
	    $this->Cookie->key 				= '}Y|PgP)"Y0<H$s6MeK?2H<x/;(ZIHou?^/2<]ZJz;&U(%-%+(D333.skgS+{Wsr';
	    $this->Cookie->httpOnly 		= true;
	    $this->Auth->loginRedirect 		= array('controller'=>'credits_requests','action'=>'index');
	    $this->Auth->redirectUrl 		= array('controller'=>'credits_requests','action'=>'index');
	    $this->Auth->logoutRedirect 	= array('action' => 'login', 'controller' => 'users');
	    $this->Auth->authError 			= 'Tu no estas habilitado para esto.';
  	}

  	protected function getCodesCustomer($customer_id = null,$credits_request_id = null, $sesion_id = null, $email = null, $phone = null){
  		if(!AuthComponent::user("id") && is_null($sesion_id) && !isset($this->request->data["onlineRequests"]) ){
  			$this->redirect(array("controller"=>"pages","action"=>"home"));
  		}

  		if(is_null($customer_id) && is_null($sesion_id)){
  			$customer_id = AuthComponent::user("customer_id");
  		}

  		$this->loadModel("CustomersCode");
  		$this->CustomersCode->closeCodes();

      if (!is_null($credits_request_id)) {
        $codeEmail = "";
      }else{
  		  $codeEmail = $this->getOrGenerate(1,$customer_id,$credits_request_id,$sesion_id,$email,$phone);
      }
  		$codePhone = $this->getOrGenerate(2,$customer_id,$credits_request_id,$sesion_id,$email,$phone);

  		return compact("codeEmail","codePhone");

  	}

  	public function validateCodeCommerce(){
  		$this->loadModel("Customer");
  		$this->loadModel("User");
	    $code = $this->Customer->field("code",["id"=>AuthComponent::user("customer_id")]);
	    if(is_null($code)){
	      $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 1]]);
          $this->overwrite_session_user(AuthComponent::user('id'));
	      $this->Session->setFlash(__('Error, el código de proveedor es necesario'), 'flash_error');
	      $this->redirect(array("controller"=>"pages","action"=>"register_step_one"));
	    }else{
	      return $code;
	    }
	}

  	public function getOrGenerate($type,$customer,$credits_request_id = null, $sesion_id = null, $email = null, $phone = null){
  		$this->loadModel("CustomersCode");
  		$this->loadModel("Customer");
		$this->CustomersCode->recursive = -1;

		$minutes = 30;

		if (!is_null($sesion_id)) {
			$code = $this->CustomersCode->findBySesIdAndTypeCodeAndState($sesion_id,$type,0);
		}else{
			if (is_null($credits_request_id)) {
				$code = $this->CustomersCode->findByCustomerIdAndTypeCodeAndState($customer,$type,0);
			}else{
				$code = $this->CustomersCode->findByCustomerIdAndTypeCodeAndStateAndCreditsRequestId($customer,$type,0,$credits_request_id);
				$minutes = 10;
			}
		}

		if(!empty($code)){

			if (!is_null($sesion_id)) {

				if($type == 1){
					$nameUser  = "";
					$emailUser = $email;
					$options = [
						"subject" 	=> "Código de verificación Zíro",
						"to"   		=> $emailUser,
						"vars" 	    => ["codigo"=>$code["CustomersCode"]["code"],"name_user" => $nameUser],
						"template"	=> "code_generated",
					];
					$this->sendMail($options);
				}else{
					$this->sendMessageTxt($phone,$code["CustomersCode"]["code"]);
				}

			}

			return $code["CustomersCode"]["code"];

		}else{
			$codeNew  		  = $this->CustomersCode->generate();
			$dataSaveCode   = ["CustomersCode" => ["code" => $codeNew, "customer_id" => $customer,"type_code" => $type,"deadline" => strtotime("+".$minutes." minutes"),"credits_request_id" => $credits_request_id, "ses_id" => $sesion_id] ];
			$this->CustomersCode->create();
			if($type == 1 && $this->CustomersCode->save($dataSaveCode)){

				if (!is_null($sesion_id)) {
					$nameUser  = "";
					$emailUser = $email;
				}else{
       				$nameUser  = $this->Customer->field("name",["id"=>$customer]);
       				$emailUser = $this->Customer->field("email",["id"=>$customer]);
				}

				$options = [
					"subject" 	=> "Código de verificación Zíro",
					"to"   		=> $emailUser,
					"vars" 	    => ["codigo"=>$codeNew,"name_user" => $nameUser],
					"template"	=> "code_generated",
				];
				$this->sendMail($options);
			}elseif($type == 2 &&  $this->CustomersCode->save($dataSaveCode)){
				if (!is_null($sesion_id)) {
					$phoneNumber  = $phone;
				}else{
					$customerData = $this->Customer->CustomersPhone->findByCustomerId($customer);
					$phoneNumber  = $customerData["CustomersPhone"]["phone_number"];
				}

				$this->sendMessageTxt($phoneNumber,$codeNew,null,$credits_request_id);
			}
			return $codeNew;
		}
	}

	public function sendMessageTxt($phone, $code, $text = null,$credits_request_id = null ){
		echo 'config';
		die();
		$HttpSocketToken 	= new HttpSocket([
		   'ssl_verify_host' 		=> false,
		   'ssl_verify_peer' 		=> false,
		   'verify_peer_name' => false,
		   // 'ssl_cafile' => 'C:\wamp64\bin\php\php5.6.40\extras\cacert.pem'
		]);

		$HttpSocketMessage 	= new HttpSocket([
		   'ssl_verify_host' => false,
		   "ssl_verify_peer" => false,
		   'verify_peer_name' => false,
		   // 'ssl_cafile' => 'C:\wamp64\bin\php\php5.6.40\extras\cacert.pem'
		]);

		$request = ["header" => [
			"Content-Type" => 'application/json'
		]];


		try {
			$responseToken = $HttpSocketToken->post('http://api.cellvoz.co/v2/auth/login',json_encode(["account"=>"00486117622","password"=>"Credi911"]),$request);
			$responseToken = json_decode($responseToken->body);

			if(isset($responseToken->token)){
				$token 			 = $responseToken->token;

				$request["header"]["api-key"] 		= "d8f336983af9d4baa49cdafe002f694421fcb6db";
				$request["header"]["Authorization"] = "Bearer ".$token;

				if (!is_null($text)) {
					$responseMessage = $HttpSocketMessage->post('http://api.cellvoz.co/v2/sms/single',json_encode(["number"=> "57$phone", "message" => $text ]),$request);
				}else{

          if (!is_null($credits_request_id)) {
            $this->loadModel("CreditsRequest");
            $dataRequest = $this->CreditsRequest->findById($credits_request_id);
            $message     =  "¡Ey, ya puedes retirar en Zíro! Tu código es: ".$code." Sr(a) ".$dataRequest["Customer"]["name"]." las Condiciones del crédito son: Obligacion ".$credits_request_id.", V. Retirado:  $".number_format($this->request->data["valorCredito"])." Nro Cuotas: ".$this->request->data["cuotasCredito"]." Valor cuota $".trim($this->request->data["cuotaCredito"]);
          }else{
            $message     = "¡Ey, bienvenido al mundo de Zíro! Tu código de verificación es: $code ";
          }
          $message = str_replace(["á","é","í","ó","ú","Á","É","Í","Ó","Ú"],["a","e","i","o","u","A","E","I","O","U"],$message);
					$responseMessage = $HttpSocketMessage->post('http://api.cellvoz.co/v2/sms/single',json_encode(["number"=> "57$phone", "message" => $message ]),$request);
					$this->log(json_encode($responseMessage),"debug");
					$this->log($message,"debug");
				}

			}

		} catch (Exception $e) {
			$this->log($e->getMessage(),"debug");
		}

	}

	public function sendMessageAll($msgs){
		$HttpSocketToken 	= new HttpSocket([
		   'ssl_verify_host' 		=> false,
		   'ssl_verify_peer' 		=> false,
		   'verify_peer_name' => false,
		   // 'ssl_cafile' => 'C:\wamp64\bin\php\php5.6.40\extras\cacert.pem'
		]);

		$HttpSocketMessage 	= new HttpSocket([
		   'ssl_verify_host' => false,
		   "ssl_verify_peer" => false,
		   'verify_peer_name' => false,
		   // 'ssl_cafile' => 'C:\wamp64\bin\php\php5.6.40\extras\cacert.pem'
		]);

		$request = ["header" => [
			"Content-Type" => 'application/json'
		]];

		try {
			$responseToken = $HttpSocketToken->post('https://api.cellvoz.co/v2/auth/login',json_encode(["account"=>"00486117622","password"=>"Credi911"]),$request);
			$responseToken = json_decode($responseToken->body);

			if(isset($responseToken->token)){
				$token 			 = $responseToken->token;

				$request["header"]["api-key"] 		= "d8f336983af9d4baa49cdafe002f694421fcb6db";
				$request["header"]["Authorization"] = "Bearer ".$token;

				$datosSend = json_encode(["name"=> "Envio masivo ".date("Ymd"), "messages" => $msgs ]);

				var_dump($datosSend);

				$responseMessage = $HttpSocketMessage->post('https://api.cellvoz.co/v2/sms/multiple',$datosSend,$request);

				var_dump($responseMessage);

			}

		} catch (Exception $e) {
			$this->log($e->getMessage(),"debug");
			var_dump($e->getMessage());
		}

	}

  	public function object_to_array($data) {

	  	if (is_array($data) || is_object($data))
	  	{
	      	$result = array();
	      	foreach ($data as $key => $value)
	      	{
	          	$result[$key] = $this->object_to_array($value);
	      	}
	      	return $result;
	  	}
	  	return $data;
	}

	public function getRealIP()
	{
	    if (isset($_SERVER["HTTP_CLIENT_IP"])){
	        return $_SERVER["HTTP_CLIENT_IP"];
	    }
	    elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	        return $_SERVER["HTTP_X_FORWARDED_FOR"];
	    }
	    elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
	        return $_SERVER["HTTP_X_FORWARDED"];
	    }
	    elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
	        return $_SERVER["HTTP_FORWARDED_FOR"];
	    }
	    elseif (isset($_SERVER["HTTP_FORWARDED"])){
	        return $_SERVER["HTTP_FORWARDED"];
	    }
	    else{
	        return $_SERVER["REMOTE_ADDR"];
	    }
	}

	public function delete($id){
		$action 					= 	$this->uses[0];
		$id 						= 	$this->decrypt($id);
		$this->loadModel($action);
		$this->$action->recursive 	= 	-1;
		$item 						=   $this->$action->findById($id);

		if(empty($item)){
		   $this->Session->setFlash(__('El cambio de estado no fue realizado, el elemento seleccionado no existe.'), 'flash_error');
		}else{

			$item[$action]["state"]		=   $item[$action]["state"] == 1 ? 0 : 1;
			$this->$action->id 			=	$id;
			unset($item[$action]["file"]);
			unset($item[$action]["password"]);
			unset($item[$action]["email"]);
			if($this->$action->save($item)){
				$this->Session->setFlash(__('Cambio de estado realizado correctamente'), 'flash_success');
			}else{
				$this->Session->setFlash(__('El cambio de estado no fue realizado'), 'flash_error');
			}
		}
		$this->redirect(array('action' => 'index',"controller" => $this->request->params["controller"]));
	}

	/*public function calculate_qoute($numberCuote,$valueCredit,$type = 1){
		$this->loadModel("CreditsLine");
		$this->CreditsLine->recursive = -1;
		$creditLine = $this->CreditsLine->findByState(1);

		$intRate 		= ( $creditLine["CreditsLine"]["interest_rate"]  ) / 100;
		$intOther 		= ( $creditLine["CreditsLine"]["others_rate"] ) / 100;

		$totalRate 		= ($intRate+$intOther) / $type;

		if ($numberCuote == 0) {
			$cuote = 0;
		}else{

			$numberCuote 	= intval($numberCuote);
			$valueCredit 	= floatval($valueCredit);

			try {
				$cuote 			= round( $valueCredit * ( ( pow(1+$totalRate,$numberCuote) *  $totalRate ) / ( pow(1+$totalRate,$numberCuote) - 1 ) ), 2 ) ;

			} catch (Exception $e) {
				$cuote 			= 0;
			}

		}

    	$cuote    		= round($cuote);

		return compact("intRate","intOther","cuote");

	}*/

	public function calculate_qoute($numberCuote, $valueCredit, $type = 1)
    {


        $intRate = 1.88;
        $intOther = 9.5;

        $this->loadModel("CreditsLine");
        $this->CreditsLine->recursive = -1;
        $creditLine = $this->CreditsLine->findByState(1);
        $creditLineId = $creditLine["CreditsLine"]["id"];


        $creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = ". $creditLineId);

		$frecuenty = ($numberCuote) / $type;
		//echo "<br>" . $frecuenty ;
		foreach ($creditLineDetail as $key => $value) {
			if((($valueCredit >= $value["credits_lines_details"]["min_value"] ) && $frecuenty==$value["credits_lines_details"]["month"]) && ($valueCredit <= $value["credits_lines_details"]["max_value"] )) {
			   $intRate = $value["credits_lines_details"]["interest_rate"];
			   $intOther = $value["credits_lines_details"]["others_rate"];

			}
		}


        $intRate = ($intRate) / 100;
        $intOther = ($intOther) / 100;



        //$intRate         = ( $creditLine["CreditsLine"]["interest_rate"]  ) / 100;
        //$intOther         = ( $creditLine["CreditsLine"]["others_rate"] ) / 100;

        $totalRate = ($intRate + $intOther) / $type;

        if ($numberCuote == 0) {
            $cuote = 0;
        } else {

            $numberCuote = intval($numberCuote);
            $valueCredit = floatval($valueCredit);

            try {
                $cuote = round($valueCredit * ((pow(1 + $totalRate, $numberCuote) * $totalRate) / (pow(1 + $totalRate, $numberCuote) - 1)), 2);

            } catch (Exception $e) {
                $cuote = 0;
            }

        }

        $cuote = round($cuote);

        return compact("intRate", "intOther", "cuote" , "creditLine" );

    }

	public function encrypt($value=null){
      if(!$value){return false;}
      $text = $value;
      $skey = "$%&/()=?*-+/1jf8";
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
      return trim($this->safe_b64encode($crypttext));
    }

    public function decrypt($value=null){
      if(!$value){return false;}
      $skey = "$%&/()=?*-+/1jf8";
      $crypttext = $this->safe_b64decode($value);
      $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
      $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
      $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
      return trim($decrypttext);
    }

    private  function safe_b64encode($string) {
      $data = base64_encode($string);
      $data = str_replace(array('+','/','='),array('-','_',''),$data);
      return $data;
    }

    private function safe_b64decode($string) {
      $data = str_replace(array('-','_'),array('+','/'),$string);
      $mod4 = strlen($data) % 4;
      if ($mod4) {
       $data .= substr('====', $mod4);
     }
     return base64_decode($data);
   }

   	private function getEmailtype($template){
   		$config = "default";

   		switch ($template) {

   			case 'code_generated':
   			case 'new_user_admin':
   				$config = "contacto";
   				break;

   			case 'credit_approve':
   			case 'credit_approve_final':
   			case 'new_user_sucursal':
   			case 'remember_password':
   				$config = "sac";
   				break;

   		}
   		return $config;
   	}

   public function sendMail($options = array(), $brand = null, $config = null){

        $from = Configure::read('Email.contact_mail');

        try {

            $email = new CakeEmail("default");

            if (isset($options['file'])) {
                $email->template($options['template'], 'default')
                    ->config($this->getEmailtype($options['template']))
                    ->emailFormat('html')
                    ->subject($options['subject'])
                    ->to($options['to'])
                    ->attachments($options['file'])
                    ->viewVars($options['vars']);
                    if(isset($options["cc"])){
                        $email->cc($options["cc"]);
                    }
                return $email->send();
            } else {
                $email->template($options['template'], 'default')
                    ->config($this->getEmailtype($options['template']))
                    ->emailFormat('html')
                    ->subject($options['subject'])
                    ->to($options['to'])
                    ->viewVars($options['vars']);
                    if(isset($options["cc"])){
                        $email->cc($options["cc"]);
                    }
                return $email->send();
            }


        } catch(Exception $e){
            $this->log($e->getMessage(),"debug");
            return false;
        }
        return false;
    }

    public function overwrite_session_user($user_id){
    	$this->loadModel($user_id);
		  $user 				= $this->User->findById($user_id);
        $this->Session->write('Auth.User', $user['User'], true);
	}



}
