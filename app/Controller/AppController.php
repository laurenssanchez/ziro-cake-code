<?php

require_once ROOT.'/app/Vendor/CifrasEnLetras.php';
require_once ROOT.'/app/Vendor/twilio/sdk/src/Twilio/autoload.php';

use Twilio\Rest\Client;

App::uses('Controller', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
App::uses('CakePdf', 'CakePdf.Pdf');


class AppController extends Controller
{
	public $helpers 	= 	array('Time', 'Utilidades', 'Minify.Minify');
	public $components = array(
		'Auth' => [
			'authenticate' => array(
				'Form' => array(
					'fields' => array('username' => 'email')
				)
			)
		],
		'Session', 'Cookie', 'Paginator'
	);


	public function beforeFilter()
	{
		header('Access-Control-Allow-Origin: *');
		$this->response->header('Access-Control-Allow-Origin', '*');
		$this->configAuth();
		$this->validateCustomer();
		$this->validateCredishop();
		$this->validateShopCom();
		$this->validateAdministrative();
		$this->validateJuridicoQuotes();
	}


	public function validateJuridicoQuotes()
	{
		$this->loadModel("CreditsPlan");
		$this->CreditsPlan->validateJuridicoQuotes();
	}

	public function getDaysMoraCalculo($quote)
	{
		$MyFechaQuota = new DateTime(date("Y-m-d", strtotime($quote["deadline"])));

		$MyFechaPago  = new DateTime(date("Y-m-d", strtotime($quote["date_payment"])));

		$MyfechaActual =  new DateTime(date("Y-m-d"));

		$FechaComparar = $quote["state"] == 0 ? $MyfechaActual : $MyFechaPago;

		$dias = 0;

		if ($MyFechaQuota <= $FechaComparar) {

			$deadline = $MyFechaQuota;

			$nowDate =  $FechaComparar; //new DateTime(date("Y-m-d"));
			$difference = $deadline->diff($nowDate);
			$days = $difference->days;
			$dias = $days;
		} else {
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
					"total" => $this->Credit->CreditsPlan->getCreditDeuda($value["Credit"]["id"], null, null, true),
				], "fecha" => date("Y-m-d", strtotime($value["Credit"]["created"])), "numero" => $value["Credit"]["code_pay"], "commerce" => $commerceData["Shop"]["social_reason"] . " - " . $commerceData["ShopCommerce"]["name"],
			];
		}
		return $totalByCredit;
	}




	public function generatePdf($options = array())
	{
		$CakePdf = new CakePdf(["encoding" => "UTF-8"]);
		$CakePdf->template($options['template'], 'default');
		$CakePdf->viewVars($options['vars']);
		$CakePdf->write($options['ruta']);
	}

	public function connect($documento, $method)
	{
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
			$response = $client->__soapCall($method, $params);
			$response = json_decode($response);
		} catch (Exception $e) {
			$response = null;
		}

		return $response;
	}

	public function validateAdministrative()
	{
		if (AuthComponent::user("id")) {
			if (in_array(AuthComponent::user("role"), [1, 2, 3])) {

				if ($this->request->action != "logout" && !$this->request->is("ajax") && $this->request->action != "login" && AuthComponent::user("validate") == 0) {
					$this->Session->setFlash(__('Se deben validar los códigos para poder iniciar sesión.'), 'flash_error');
					$this->redirect(["controller" => "users", "action" => "login"]);
				}
			}
		}
	}

	public function getCommercesByShop($shop_id = null, $ids = null)
	{

		$this->loadModel("ShopCommerce");

		if (AuthComponent::user("role") == 4) {
			$shop_id 	= AuthComponent::user("shop_id");
		}

		$commerces 	= $this->ShopCommerce->find("all", ["fields" => ["id"], "recursive" => -1, "conditions" => ["ShopCommerce.shop_id" => $shop_id]]);

		if (!is_null($ids) && !empty($commerces)) {
			$commerces 	= Set::extract($commerces, "{n}.ShopCommerce.id");
		}

		return $commerces;
	}

	public function validateCredishop()
	{
		if (!AuthComponent::user("id")) {
			return false;
		}
		if (in_array(AuthComponent::user("role"), [1, 2])) {
			$this->loadModel("ShopCommerce");
			$shops = $this->ShopCommerce->find("list", ["fields" => ["id", "id"]]);
			$this->getDeuda($shops);
		}
	}

	public function validateShopCom($return = null)
	{

		if (AuthComponent::user("role") == 4 || AuthComponent::user("role") == 7) {
			$saldosCommercios   = [];
			$this->loadModel("ShopCommerce");
			if (AuthComponent::user("role") == 4) {

				$shop_id    = AuthComponent::user("shop_id");
				$conditions = ["ShopCommerce.shop_id" => $shop_id];
				$commerces  = $this->ShopCommerce->find("all", ["fields" => ["id"], "recursive" => -1, "conditions" => $conditions]);


				if (!empty($commerces)) {
					$commerces      = Set::extract($commerces, "{n}.ShopCommerce.id");
					$this->getDeuda($commerces, $return);
					foreach ($commerces as $key => $value) {
						$saldo = $this->getSaldos($value, $return);
						if ($saldo != 0) {
							$saldosCommercios[$value] = ["saldo" => $saldo, "name" => $this->ShopCommerce->field("name", ["id" => $value])];
						}
					}
				}
			} elseif (AuthComponent::user("role") == 7) {
				$this->getDeuda([AuthComponent::user("shop_commerce_id")], $return);
				$saldo = $this->getSaldos(AuthComponent::user("shop_commerce_id"), $return);
				if ($saldo != 0) {
					$saldosCommercios[AuthComponent::user("shop_commerce_id")] = ["saldo" => $saldo, "name" => $this->ShopCommerce->field("name", ["id" => AuthComponent::user("shop_commerce_id")])];
				}
			}
			if (!is_null($return)) {
				return $saldosCommercios;
			}
			$this->set("saldosCommercios", $saldosCommercios);
		}
	}

	public function getDeuda($commerce_id, $return = null)
	{

		$this->loadModel("Payment");


		//$payments 		= $this->Payment->field("SUM(values)",["Payment.shop_commerce_id"=>$commerce_id,"state_credishop"=>"0","juridic" => 0]);

		// echo $commerce_id ;



		$resp = "";
		foreach ($commerce_id as $valor) {
			if (empty($resp)) {
				$resp += $valor;
			} else {
				$resp = $resp . "," . $valor;
			}
		}

		$payments =  $this->Payment->query("SELECT sum(VALUE) sValues from payments where
       state_credishop=0 AND value>=0 AND receipt_id is not null AND juridic=0 AND shop_commerce_id in (" . $resp . ")");



		if (!empty($payments)) {
			$payments  =  $payments[0][0]["sValues"];
		} else {
			$payments  = 0;
		}

		if ($payments > 0) {
			$this->set("debt_credishop", $payments);
		}
	}


	public function getSaldos($commerce_id, $return = null)
	{

		$this->loadModel("ShopsDebt");
		$this->loadModel("Disbursement");

		$debts 			= $this->ShopsDebt->field("SUM(value)", ["ShopsDebt.shop_commerce_id" => $commerce_id, "state" => "0"]);
		$disbursments 	= $this->Disbursement->find("all", ["fields" => ["Disbursement.value", "Credit.id"], "conditions" => ["Disbursement.shop_commerce_id" => $commerce_id, "Disbursement.state" => "1", 'Credit.id !=' => null], "recursive" => 1]);

		if (!empty($disbursments)) {
			$disbursments = Set::extract($disbursments, "{n}.Disbursement.value");
			$disbursments = array_sum($disbursments);
		}

		$response 		= 0;

		$debts 			= empty($debts) ? 0 : $debts;
		$disbursments 	= empty($disbursments) ? 0 : $disbursments;
		$response 		= $disbursments - $debts;

		if (!is_null($return)) {
			$response = compact("debts", "disbursments", "response");
		}

		return $response;
	}

	private function validateCustomer()
	{
		if (AuthComponent::user("role") == 5) {
			$this->loadModel("Customer");
			$this->Customer->recursive = -1;
			$actionsCustomers = ["register_step_one", "register_step_two", "register_step_three", "register_step_four", "home", "dashboardcliente"];
			if ($this->request->action != "logout" && !$this->request->is("ajax")) {
				try {
					if (in_array($this->request->action, $actionsCustomers)) {

						if ($this->request->action == "home" && AuthComponent::user("customer_new_request") == 5) {
							$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 1]]);
							$this->overwrite_session_user(AuthComponent::user('id'));
							$this->redirect(array("controller" => "pages", "action" => "register_step_one"));
						}

						$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
						if (empty($customer["Customer"]["email"]) || AuthComponent::user("customer_new_request") == 1) {
							if ($this->request->action == "register_step_one") {
								return true;
							}
							$this->redirect(array("controller" => "pages", "action" => "register_step_one"));
						}/*elseif(empty($customer["Customer"]["date_birth"]) || AuthComponent::user("customer_new_request") == 2){
			  				if ($this->request->action == "register_step_two" ) {
			  					return true;
			  				}
			  				$this->redirect(array("controller"=>"pages","action"=>"register_step_two"));
			  			}*/ elseif ($customer["Customer"]["data_full"] == 0 || AuthComponent::user("customer_new_request") == 3) {
							if ($this->request->action == "register_step_three") {
								return true;
							}
							$this->redirect(array("controller" => "pages", "action" => "register_step_three"));
						} elseif (AuthComponent::user("customer_complete") == 0 || AuthComponent::user("customer_new_request") == 4) {
							if ($this->request->action == "register_step_four") {
								return true;
							}
							$this->redirect(array("controller" => "pages", "action" => "register_step_four"));
						} elseif (AuthComponent::user("customer_complete") == 1 || AuthComponent::user("customer_new_request") == 6) {
							if ($this->request->action == "home") {
								return true;
							}
							if ($this->request->action == "dashboardcliente") {
								$this->totalQuote();
								return true;
							}
							$this->redirect(array("controller" => "pages", "action" => "dashboardcliente"));
						}
					}
				} catch (Exception $e) {
					$this->redirect(["controller" => "users", "action" => "profile"]);
				}
			}
			$this->totalQuote();
		}
	}

	public function totalQuote($return = null, $client_id = null, $array=false,$tipo=1)
	{
		$this->loadModel("CreditLimit");
		$this->loadModel("CreditsPlan");
		$this->loadModel("Payment");


		if (is_null($client_id)) {
			$client_id = AuthComponent::user("customer_id");
		}

		$dataLimit = $this->CreditLimit->find("all", ["conditions" => ["customer_id" => $client_id], "recursive" => -1]);
		$mora='false';
		$total = 0;

		if (!empty($dataLimit)) {
			foreach ($dataLimit as $key => $value) {
				if ($value["CreditLimit"]["reason"]=='Aprobación de cupo') {
					$total += $value["CreditLimit"]["value"];
				}
				// if ($value["CreditLimit"]["reason"]=='Desembolso de cupo') {
				// 	$total -= $value["CreditLimit"]["value"];
				// }
			}
		}
		$creditsPendientes = $this->CreditsPlan->Credit->find("list", [
			"fields" => ["id", "id"],
			"conditions" => [
				"Credit.customer_id" => $client_id,
				"Credit.credits_request_id <>" => 0,
				"Credit.state" => 0,
			]
		]);


		if (!is_null($creditsPendientes)) {
			// debug($creditsPendientes);
			// die();
			//buscar los credits plans
			$creditsPlans = $this->CreditsPlan->find("all", [
				"conditions" => [
					"credit_id" => array_values($creditsPendientes)
				],
				"recursive" => -1
			]);

			if (!is_null($creditsPlans)) {
				foreach ($creditsPlans as $plan) {
					if ($plan["CreditsPlan"]["state"]!=1) {
						//buscar si tiene pagos
						$payments = $this->Payment->find("all", [
							"conditions" => [
								"credits_plan_id" => $plan["CreditsPlan"]["id"]
							],
							"recursive" => -1
						]);
						//restar el total de cuota
						$total -= $plan["CreditsPlan"]["capital_value"];

						//sumar abonos
						if(!empty($payments)) {
							foreach ($payments as $payment) {
								$total += $payment["Payment"]["value"];
							}
						}
						// if ($plan["CreditsPlan"]["value_pending"]==0) {
						// 	$total -= $plan["CreditsPlan"]["capital_value"];
						// } else {
						// 	$total -= $plan["CreditsPlan"]["value_pending"];
						// }
					}
				}
			}
		}


		$this->loadModel("Credit");
		$credits = $this->Credit->find("all", ["fields", "conditions" => ["Credit.customer_id" => $client_id, "Credit.credits_request_id <>" => 0]]);

		if (!empty($credits)) {
			$this->CreditsPlan->update_cuotes_days();
			$this->CreditsPlan->update_credits_days();
			foreach ($credits as $key => $valueCredit) {
				$this->CreditsPlan->getCuotesInformation($valueCredit["Credit"]['id']);
			}

			$credits = $this->CreditsPlan->Credit->find("list", ["fields" => ["id", "quote_days"], "conditions" => [
				"Credit.customer_id" => $client_id,
				"Credit.debt" => 1,
				"Credit.quote_days >" => 0,
				"Credit.credits_request_id <>" => 0
			]]);

			if (count($credits) >= 1 || (!is_null($credits) && !empty($credits))) {
				$mora='true';
				if($tipo==1) {
					$total = 0;
				}
			}
		}

		// getCuotesInformation

		// if ($total < 0) {
		// 	$total = 0;
		// }

		if (!is_null($return)) {
			if ($array) {
				return [
					$total,
					$mora
				];
			} else {
				return $total;
			}
		}

		$this->set("totalCustomerQuote", $total);
	}

	/*
  	public function totalQuote($return = null, $client_id null){
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

	private function configAuth()
	{
		$this->Cookie->time 			= '30 Days';  // or '1 hour'
		$this->Cookie->key 				= '}Y|PgP)"Y0<H$s6MeK?2H<x/;(ZIHou?^/2<]ZJz;&U(%-%+(D333.skgS+{Wsr';
		$this->Cookie->httpOnly 		= true;
		$this->Auth->loginRedirect 		= array('controller' => 'credits_requests', 'action' => 'index');
		$this->Auth->redirectUrl 		= array('controller' => 'credits_requests', 'action' => 'index');
		$this->Auth->logoutRedirect 	= array('action' => 'login', 'controller' => 'users');
		$this->Auth->authError 			= 'Tu no estas habilitado para esto.';
	}

	protected function getCodesCustomer($customer_id = null, $credits_request_id = null, $sesion_id = null, $email = null, $phone = null)
	{
		if (!AuthComponent::user("id") && is_null($sesion_id) && !isset($this->request->data["onlineRequests"])) {
			$this->redirect(array("controller" => "pages", "action" => "home"));
		}

		if (is_null($customer_id) && is_null($sesion_id)) {
			$customer_id = AuthComponent::user("customer_id");
		}

		$this->loadModel("CustomersCode");
		$this->CustomersCode->closeCodes();

		if (!is_null($credits_request_id)) {
			$codeEmail = "";
		} else {
			$codeEmail = $this->getOrGenerate(1, $customer_id, $credits_request_id, $sesion_id, $email, $phone);
		}
		$codePhone = $this->getOrGenerate(2, $customer_id, $credits_request_id, $sesion_id, $email, $phone);

		return compact("codeEmail", "codePhone");
	}

	public function validateCodeCommerce()
	{
		$this->loadModel("Customer");
		$this->loadModel("User");
		$code = $this->Customer->field("code", ["id" => AuthComponent::user("customer_id")]);
		if (is_null($code)) {
			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 1]]);
			$this->overwrite_session_user(AuthComponent::user('id'));
			$this->Session->setFlash(__('Error, el código de proveedor es necesario'), 'flash_error');
			$this->redirect(array("controller" => "pages", "action" => "register_step_one"));
		} else {
			return $code;
		}
	}

	public function getOrGenerate($type, $customer, $credits_request_id = null, $sesion_id = null, $email = null, $phone = null)
	{

		$this->loadModel("CustomersCode");
		$this->loadModel("Customer");
		$this->CustomersCode->recursive = -1;
		$minutes = 30;

		if (!is_null($sesion_id)) {
			$code = $this->CustomersCode->findBySesIdAndTypeCodeAndState($sesion_id, $type, 0);
		} else {
			if (is_null($credits_request_id)) {
				$code = $this->CustomersCode->findByCustomerIdAndTypeCodeAndState($customer, $type, 0);
			} else {
				$code = $this->CustomersCode->findByCustomerIdAndTypeCodeAndStateAndCreditsRequestId($customer, $type, 0, $credits_request_id);
				$minutes = 10;
			}
		}

		if (!empty($code)) {

				if ($type == 1) {
					$nameUser  = "";
					$emailUser = $email;
					$options = [
						"subject" 	=> "Código de verificación Zíro",
						"to"   		=> $emailUser,
						"vars" 	    => ["codigo" => $code["CustomersCode"]["code"], "name_user" => $nameUser],
						"template"	=> "code_generated",
					];
					$this->sendMail($options);
				} else {

					if (!is_null($phone)) {
						$phoneNumber  = $phone;
					} else {
						$customerData = $this->Customer->CustomersPhone->findAllByCustomerId($customer);
						$phoneNumber  = [];
						foreach ($customerData as  $value) {
							$phoneNumber[]  = $value["CustomersPhone"]["phone_number"];
						}
					}

					$code= $code["CustomersCode"]["code"];

					if (is_array($phoneNumber)) {
						foreach ($phoneNumber as  $phoneData) {
							$this->sendMessageTxt($phoneData, $code, null, $credits_request_id);
						}
					} else {
						$this->sendMessageTxt($phoneNumber, $code, null, $credits_request_id);
					}
				}


			return $code;
		} else {
			$codeNew  		  = $this->CustomersCode->generate();
			$dataSaveCode   = ["CustomersCode" => ["code" => $codeNew, "customer_id" => $customer, "type_code" => $type, "deadline" => strtotime("+" . $minutes . " minutes"), "credits_request_id" => $credits_request_id, "ses_id" => $sesion_id]];
			$this->CustomersCode->create();
			if ($type == 1 && $this->CustomersCode->save($dataSaveCode)) {

				if (!is_null($sesion_id)) {
					$nameUser  = "";
					$emailUser = $email;
				} else {
					$nameUser  = $this->Customer->field("name", ["id" => $customer]);
					$emailUser = $this->Customer->field("email", ["id" => $customer]);
				}

				$options = [
					"subject" 	=> "Código de verificación Zíro",
					"to"   		=> $emailUser,
					"vars" 	    => ["codigo" => $codeNew, "name_user" => $nameUser],
					"template"	=> "code_generated",
				];
				$this->sendMail($options);
			} elseif ($type == 2 &&  $this->CustomersCode->save($dataSaveCode)) {

				if (!is_null($sesion_id)) {
					$phoneNumber  = $phone;
				} else {
					$customerData = $this->Customer->CustomersPhone->findAllByCustomerId($customer);
					$phoneNumber  = [];
					foreach ($customerData as  $value) {
						$phoneNumber[]  = $value["CustomersPhone"]["phone_number"];
					}
				}

				if (is_array($phoneNumber)) {
					foreach ($phoneNumber as $key => $phoneData) {
						$this->sendMessageTxt($phoneData, $codeNew, null, $credits_request_id);
					}
				} else {
					$this->sendMessageTxt($phoneNumber, $codeNew, null, $credits_request_id);
				}
			}
			return $codeNew;
		}
	}

	public function sendMessageTxt($phone, $code, $text = null, $credits_request_id = null)
	{
		$data['sms_settings']['cellvoz_account'] = '00486765881';
		$data['sms_settings']['cellvoz_api_key'] = '364a6fc7dd823121a24604b262f2d610bed025a7';
		$data['sms_settings']['cellvoz_password'] = 'Ziro1234*';
		if (!is_null($text)) {
			$data['sms_body'] = $text;
		} else {
			if (!is_null($credits_request_id)) {
				$templateParams=[];

				$this->loadModel("CreditsRequest");
				$dataRequest = $this->CreditsRequest->findById($credits_request_id);

				$templateParams= [
					ucfirst($dataRequest['Customer']['name']),
					$code,
					number_format($this->request->data["valorCredito"]),
					$this->request->data["cuotaCredito"],
					number_format($this->request->data["cuotasCredito"]),
					$dataRequest['CreditsRequest']['code_pay'],
				];
				$telefono = $phone;
				//si es de pruebas envio codigos con cellvoz si no con twillio
				$templateMsj="Ey!, Sr(a) {{1}}! Tu código es: {{2}}corre y autoriza tu crédito en Ziro. Por favor verifica las condiciones de tu crédito!  Valor solicitado: {{3}}, Valor de tu cuota: {{4}},  Número de cuotas: {{5}},   Número de obligación: {{6}}";
				$templateId="5791332267643443";

				$this->sendWhatsapp($templateParams,$telefono,$templateId,$templateMsj);
			} else {
				$message     = "¡Ey, bienvenido al mundo de Zíro! Tu codigo de verificacion es: $code ";
			}
		}
	}

	public function sendWhatsapp($templateParams,$phone,$templateId,$templateMsj) {
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
			$token 			 = 'b946d0a5151446f58a6793155a43a2fd';
			$request["header"]["Authorization"] = "Bearer " . $token;
			$responseMessage = $HttpSocketMessage->post('https://chat.keybeapi.com/message/proactivity',
			json_encode([
				"templateParams" => $templateParams,
				"templateId" => $templateId,
				"userHost" => "+573142051091",
				"companyUUID" => "28s9b1ldlx84l3",
				"appUUID" => "28s9b1ldlx84m0",
				"templateMessage" => $templateMsj,
				"userGuest" => "+57".$phone
			]),
			$request);

			return $responseMessage;
		} catch (Exception $e) {
			$this->log($e->getMessage(), "debug");
		}
	}


	public function sendMessageTxtBack($phone, $code, $text = null, $credits_request_id = null)
	{
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
			$responseToken = $HttpSocketToken->post('https://api.cellvoz.co/v2/auth/login', json_encode(["account" => "00486117622", "password" => "Credi911"]), $request);
			$responseToken = json_decode($responseToken->body);

			if (isset($responseToken->token)) {
				$token 			 = $responseToken->token;

				$request["header"]["api-key"] 		= "d8f336983af9d4baa49cdafe002f694421fcb6db";
				$request["header"]["Authorization"] = "Bearer " . $token;

				if (!is_null($text)) {
					$responseMessage = $HttpSocketMessage->post('https://api.cellvoz.co/v2/sms/single', json_encode(["number" => "57$phone", "message" => $text]), $request);
				} else {

					if (!is_null($credits_request_id)) {
						$this->loadModel("CreditsRequest");
						$dataRequest = $this->CreditsRequest->findById($credits_request_id);

						$message="Ey!, Sr(a) ".ucfirst($dataRequest['Customer']['name'])."! Tu código es: ".$code." corre y autoriza tu crédito en Ziro. Por favor verifica las condiciones de tu crédito! Valor solicitado: $".number_format($this->request->data["valorCredito"]).". Valor de tu cuota: $".$this->request->data["cuotaCredito"].". Número de cuotas: ". $this->request->data["cuotasCredito"].". Número de obligación:".$dataRequest['CreditsRequest']['code_pay'];

						// $message     =  "¡Ey!, Corre y autoriza tu crédito en Zíro: " . $code . ". Sr(a) " . $dataRequest["Customer"]["name"] . " las condiciones del crédito son: Obligacion " . $dataRequest['CreditsRequest']['code_pay'] . ", V. Solicitado:  $" . number_format($this->request->data["valorCredito"]) . " Nro Cuotas: " . $this->request->data["cuotasCredito"] . " Valor cuota $" . trim($this->request->data["cuotaCredito"]);

					} else {
						$message     = "¡Ey, bienvenido al mundo de Zíro! Tu codigo de verificacion es: $code ";
					}
					$message = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $message);
					$responseMessage = $HttpSocketMessage->post('https://api.cellvoz.co/v2/sms/single', json_encode(["number" => "57$phone", "message" => $message]), $request);
					$this->log(json_encode($responseMessage), "debug");
					$this->log($message, "debug");
				}
			}
		} catch (Exception $e) {
			$this->log($e->getMessage(), "debug");
		}
	}

	public function sendMessageAll($msgs)
	{
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
			$responseToken = $HttpSocketToken->post('https://api.cellvoz.co/v2/auth/login', json_encode(["account" => "00486117622", "password" => "Credi911"]), $request);
			$responseToken = json_decode($responseToken->body);

			if (isset($responseToken->token)) {
				$token 			 = $responseToken->token;

				$request["header"]["api-key"] 		= "d8f336983af9d4baa49cdafe002f694421fcb6db";
				$request["header"]["Authorization"] = "Bearer " . $token;

				$datosSend = json_encode(["name" => "Envio masivo " . date("Ymd"), "messages" => $msgs]);

				var_dump($datosSend);

				$responseMessage = $HttpSocketMessage->post('https://api.cellvoz.co/v2/sms/multiple', $datosSend, $request);

				var_dump($responseMessage);
			}
		} catch (Exception $e) {
			$this->log($e->getMessage(), "debug");
			var_dump($e->getMessage());
		}
	}

	public function object_to_array($data)
	{

		if (is_array($data) || is_object($data)) {
			$result = array();
			foreach ($data as $key => $value) {
				$result[$key] = $this->object_to_array($value);
			}
			return $result;
		}
		return $data;
	}

	public function getRealIP()
	{
		if (isset($_SERVER["HTTP_CLIENT_IP"])) {
			return $_SERVER["HTTP_CLIENT_IP"];
		} elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
			return $_SERVER["HTTP_X_FORWARDED"];
		} elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
			return $_SERVER["HTTP_FORWARDED_FOR"];
		} elseif (isset($_SERVER["HTTP_FORWARDED"])) {
			return $_SERVER["HTTP_FORWARDED"];
		} else {
			return $_SERVER["REMOTE_ADDR"];
		}
	}

	public function delete($id)
	{
		$action 					= 	$this->uses[0];
		$id 						= 	$this->decrypt($id);
		$this->loadModel($action);
		$this->$action->recursive 	= 	-1;
		$item 						=   $this->$action->findById($id);

		if (empty($item)) {
			$this->Session->setFlash(__('El cambio de estado no fue realizado, el elemento seleccionado no existe.'), 'flash_error');
		} else {

			$item[$action]["state"]		=   $item[$action]["state"] == 1 ? 0 : 1;
			$this->$action->id 			=	$id;
			unset($item[$action]["file"]);
			unset($item[$action]["password"]);
			unset($item[$action]["email"]);
			if ($this->$action->save($item)) {
				$this->Session->setFlash(__('Cambio de estado realizado correctamente'), 'flash_success');
			} else {
				$this->Session->setFlash(__('El cambio de estado no fue realizado'), 'flash_error');
			}
		}
		$this->redirect(array('action' => 'index', "controller" => $this->request->params["controller"]));
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

		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);
		$type= $type==2 ? 2 : 1;

		$frecuenty = ($numberCuote) / $type;
		//echo "<br>" . $frecuenty ;
		foreach ($creditLineDetail as $key => $value) {
			if ((($valueCredit >= $value["credits_lines_details"]["min_value"]) && $frecuenty == $value["credits_lines_details"]["month"]) && ($valueCredit <= $value["credits_lines_details"]["max_value"])) {
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

		return compact("intRate", "intOther", "cuote", "creditLine");
	}


	public function encrypt($value = null)
	{
		if (!$value) {
			return false;
		}
		$text = $value;
		$skey = "$%&/()=?*-+/1jf8";
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $skey, $text, MCRYPT_MODE_ECB, $iv);
		return trim($this->safe_b64encode($crypttext));
	}

	public function decrypt($value = null)
	{
		if (!$value) {
			return false;
		}
		$skey = "$%&/()=?*-+/1jf8";
		$crypttext = $this->safe_b64decode($value);
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $skey, $crypttext, MCRYPT_MODE_ECB, $iv);
		return trim($decrypttext);
	}

	private  function safe_b64encode($string)
	{
		$data = base64_encode($string);
		$data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
		return $data;
	}

	private function safe_b64decode($string)
	{
		$data = str_replace(array('-', '_'), array('+', '/'), $string);
		$mod4 = strlen($data) % 4;
		if ($mod4) {
			$data .= substr('====', $mod4);
		}
		return base64_decode($data);
	}

	private function getEmailtype($template)
	{
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

	public function sendMail($options = array(), $brand = null, $config = null)
	{
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
				if (isset($options["cc"])) {
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
				if (isset($options["cc"])) {
					$email->cc($options["cc"]);
				}
				return $email->send();
			}
		} catch (Exception $e) {
			$this->log($e->getMessage(), "debug");
			return false;
		}
		return false;
	}

	public function overwrite_session_user($user_id)
	{
		$this->loadModel($user_id);
		$user 				= $this->User->findById($user_id);
		$this->Session->write('Auth.User', $user['User'], true);
		$this->Session->write('Session.timeout', '86400');
	}



	public function enviaSmsTwillio($data, $telefono, $debug) {


		$mensaje = $data['sms_body'];
		// $mensaje = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $mensaje);
		$api_mensaje_texto = $mensaje;

		$indicativos = array("300", "301", "302", "303", "304", "305", "310", "311", "312", "313", "314", "315", "316", "317", "318", "319", "320", "321", "322", "323", "350", "351");
		$indicativo_cliente = substr($telefono, 0, 3);

		if (strlen($telefono) == 10) {
			if (in_array($indicativo_cliente, $indicativos)) {

				// Your Account SID and Auth Token from twilio.com/console
				$account_sid = 'AC157dd5e3c67d3d69b291c8682adb7fe6';
				$auth_token = 'a95ffc8407eab5cf3ad526f1bde2c44b';
				// In production, these should be environment variables. E.g.:
				// $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

				// A Twilio number you own with SMS capabilities
				$twilio_number = "+16802196016";
				$api_numero_telefono = '57' . $telefono;


				$client = new Client($account_sid, $auth_token);
				$message =$client->messages->create(
					// Where to send a text message (your cell phone?)
					'+'.$api_numero_telefono,
					array(
						'from' => $twilio_number,
						'body' => $api_mensaje_texto
					)
				);

				/********************************************************************/
				if ($message->status == 'queued') {
					$er = "Mensaje Enviado";
				} else {
					// $er = "Mensaje No Enviado";
  					$data['sms_settings']['cellvoz_account'] = '00486765881';
        			$data['sms_settings']['cellvoz_api_key'] = '364a6fc7dd823121a24604b262f2d610bed025a7';
        			$data['sms_settings']['cellvoz_password'] = 'Ziro1234*';
					$this->enviaSmsCellvoz($data, $api_numero_telefono, false);
				}
			} else {
				$er = "El indicativo del telefono celular no esta permitido";
			}
		} else {
			$er = "La longitud del telefono celular no concuerda con un numero valido";
		}

		if ($debug == true) {
			echo $er;
		}

		return $er;
	}



	public function enviaSmsCellvoz($data, $telefono, $debug)
	{
		$mensaje = $data['sms_body'];
		$mensaje = str_replace(["á", "é", "í", "ó", "ú", "Á", "É", "Í", "Ó", "Ú"], ["a", "e", "i", "o", "u", "A", "E", "I", "O", "U"], $mensaje);

		$indicativos = array("300", "301", "302", "303", "304", "305", "310", "311", "312", "313", "314", "315", "316", "317", "318", "319", "320", "321", "322", "323", "350", "351");
		$indicativo_cliente = substr($telefono, 0, 3);

		if (strlen($telefono) == 10) {
			if (in_array($indicativo_cliente, $indicativos)) {

				$account = '00486765881';
				$api_key = '364a6fc7dd823121a24604b262f2d610bed025a7';
				$password = 'Ziro1234*';
				$api_numero_telefono = '57' . $telefono;
				$api_mensaje_texto = $mensaje;
				/***********************************************************************/

				$fullurl = "https://api.cellvoz.com/v2/auth/login";

				$headers = array(
					"Content-Type: application/json",
				);

				$post_params = array(
					"api_key" => $api_key,
					"account" => $account,
					"password" => $password,
					"Content-Type: application/json"
				);
				$post_params = json_encode($post_params);

				//$fp = fopen(dirname(__FILE__).'/errorLogin.txt', 'w');

				$curl = curl_init($fullurl);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				//curl_setopt($curl, CURLOPT_STDERR, $fp);

				$response = curl_exec($curl);

				$respuesta_final_autentificacion = json_decode($response, true);
				$token_autentificacion = $respuesta_final_autentificacion['token'];

				/********************************************************************/
				$fullurl = "https://api.cellvoz.com/v2/sms/single";

				$headers = array(
					"api-key: " . $api_key,
					"Authorization: Bearer " . $token_autentificacion,
					"Content-Type: application/json",
				);

				$post_params = array(
					"number" => $api_numero_telefono,
					"message" => $api_mensaje_texto,
					"Content-type: application/json",
				);
				$post_params = json_encode($post_params);

				//$fp = fopen(dirname(__FILE__).'/errorSend.txt', 'w');

				$curl = curl_init($fullurl);
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_params);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_VERBOSE, 1);
				//curl_setopt($curl, CURLOPT_STDERR, $fp);

				$response = curl_exec($curl);
				$respuesta_final_envio = json_decode($response, true);
				// $mensaje_enviado = $respuesta_final_envio['success'];
				// /********************************************************************/
				// debug($respuesta_final_envio);
				// die();
				// if ($mensaje_enviado == 1) {
				// 	$er = "Mensaje Enviado";
				// } else {
				// 	$er = "Mensaje No Enviado";
				// }
			} else {
				$er = "El indicativo del telefono celular no esta permitido";
			}
		} else {
			$er = "La longitud del telefono celular no concuerda con un numero valido";
		}

		if ($debug == true) {
			echo '<pre>';
			print_r($respuesta_final_autentificacion);
			echo '</pre>';
			echo '<pre>';
			print_r($respuesta_final_envio);
			echo '</pre>';
			echo $er;
		}
	}

	function prepararParaSql($cadena, $password=false)
	{

		$cadena = str_replace ("'", "", $cadena);

		if (!$password) {
			$cadena = str_replace("*", "", $cadena);
		}

		$cadena = preg_replace ("/\b%\b/i", "", $cadena);
		$cadena = str_replace ("'", "", $cadena);
		// $cadena = str_replace ("#", "", $cadena);
		$cadena = str_replace ("\\", "", $cadena);
		$cadena = str_replace ("--", "", $cadena);
		$cadena = preg_replace("/\bmysql\b/i","",$cadena);
		$cadena = preg_replace("/\bmysqli\b/i","",$cadena);
		$cadena = preg_replace("/\bmssql\b/i","",$cadena);
		$cadena = preg_replace("/\bquery\b/i","",$cadena);
		$cadena = preg_replace("/\binsert\b/i","",$cadena);
		$cadena = preg_replace("/\binto\b/i","",$cadena);
		$cadena = preg_replace("/\bupdate\b/i","",$cadena);
		$cadena = preg_replace("/\bdelete\b/i","",$cadena);
		$cadena = preg_replace("/\bselect\b/i","",$cadena);
		$cadena = preg_replace("/\bcharacter\b/i","",$cadena);
		$cadena = preg_replace("/\bmemb_info\b/i","",$cadena);
		$cadena = preg_replace("/\bin\b/i","",$cadena);
		$cadena = preg_replace("/\bor\b/i","",$cadena);
		$cadena = str_replace (";", "", $cadena);
		$cadena = str_replace ("=", "", $cadena);
		$cadena = preg_replace ("/\bfrom\b/i", "", $cadena);
		$cadena = preg_replace ("/\busers\b/i", "", $cadena);
		if (is_string($cadena)) {
			$cadena = trim($cadena);
			$cadena = strip_tags($cadena);
			$cadena = stripslashes($cadena);
			// $cadena=htmlentities($cadena);
			// Convierto caracteres especiales en entidades HTML
			// $cadena = htmlspecialchars($cadena);
		}
		// $cadena = str_replace ("-", "", $cadena);

		return $cadena;
	}

	function validarDatosRaros($cadena) {
		$alerta=false;

		$cadena = str_replace ("'", "", $cadena);
		$cadena = str_replace("*", "", $cadena);
		$cadena = str_replace (";", "", $cadena);
		$cadena = str_replace ("=", "", $cadena);

		if(preg_match("/\bmysql\b/i","",$cadena) ||
			preg_match("/\bmysqli\b/i","",$cadena) || preg_match("/\bmssql\b/i","",$cadena) ||
			preg_match("/\bquery\b/i","",$cadena) || preg_match("/\binsert\b/i","",$cadena) ||
			preg_match("/\binto\b/i","",$cadena) || preg_match("/\bupdate\b/i","",$cadena) ||
			preg_match("/\bdelete\b/i","",$cadena) || preg_match("/\bselect\b/i","",$cadena) ||
			preg_match("/\bcharacter\b/i","",$cadena) || preg_match("/\bmemb_info\b/i","",$cadena) ||
			preg_match("/\bin\b/i","",$cadena) || preg_match("/\bor\b/i","",$cadena) ||
			preg_match ("/\bfrom\b/i", "", $cadena) || preg_match ("/\busers\b/i", "", $cadena)) {
			$alerta=true;

			return $alerta;

		}
	}

	public function validarLocalHost(){
		$pos = strpos($_SERVER['HTTP_HOST'], 'localhost');
		if ($pos === false) {
			return false;
		} else {
			return true;
		}
	}

	public function validarTest(){
		$pos = strpos($_SERVER['HTTP_HOST'], 'test');
		if ($pos === false) {
			return false;
		} else {
			return true;
		}
	}

	public function validarProd(){
		$pos = strpos($_SERVER['HTTP_HOST'], 'creditos');
		if ($pos === false) {
			return false;
		} else {
			return true;
		}
	}

	public function getInfoCreditCliente($cliente) {
		$data=[];
		$data['cupoTotal']=0;
		$data['valorGastado']=0;
		$data['valorLibre']=0;
		App::import('Model', 'ShopCommerce');
		$this->ShopCommerce = new ShopCommerce();
		$SearchShopCommerce = $this->ShopCommerce->buscarPorCodigo($cliente['Customer']['code']);


		$data['shop']=$SearchShopCommerce[0]['shop']['social_reason'];
		$data['commerce']=$SearchShopCommerce[0]['shop_commerce']['name'];
		$data['commerceCode']=$SearchShopCommerce[0]['shop_commerce']['code'];

		if(isset($cliente['CreditLimit'])) {
			foreach ($cliente['CreditLimit'] as $limit) {
				if ($limit["reason"]=='Aprobación de cupo') {
					$data['cupoTotal']+= $limit["value"];
				}
			}
		}

		App::import('Controller', 'AppController');
		$this->AppController = new AppController();
		$validacion= $this->AppController->totalQuote(true,$cliente['Customer']["id"],true,2);
		$data['valorLibre']=$validacion[0];
		$data['mora']=$validacion[1];
		if ($data['valorLibre'] > $data['cupoTotal']) {
			$data['valorLibre'] = $data['cupoTotal'];
		}
		$data['valorGastado']=($data['cupoTotal']) - ($data['valorLibre']);
		return $data;
	}

	public function calculateCuotesCredit($data,$credit) {
		$totalCuotes=$credit['CreditRequest']['number_approve'];

		//la frecuencia se refiere a cuantas cuotas se paga  en el mes
		$frecuencia=$credit['Credit']["frecuency"]== 2 ? 2 : 1;
		$numberQuotes = $credit['Credit']["number_fee"];

		$this->CreditsRequest->recursive = -1;
		$this->CreditsRequest->CreditsLine->recursive = -1;
		//el sistema divide por la frecuencia entonces como 45 y 60 dias es una cuota lo igualamos a uno
		$cuoteValuesData = $this->calculate_qoute($numberQuotes,$this->request->data["valueCredit"],$frecuencia);

		$explodeCreditStart =explode(' ',$credit['Credit']['created']);

		if ($explodeCreditStart[0] !== $data['date_start']) {

			if ($credit['Credit']["type"] == 1){
				$dias 	= $totalCuotes * 30;

			} elseif ($credit['Credit']["frecuency"] == 3){
				$dias 	= 45;

			} elseif ($credit['Credit']["frecuency"] == 4){
				$dias 	= 60;

			} else{
				$days 	= $totalCuotes*15;
			}

			$created 			= $data['date_start']." ".date("H:i:s");
			$dateDisbursement 	= $created;
			$deadline= date("Y-m-d",strtotime($data['date_start']."+".$dias. " days"));
		} else {
			$created 			= $credit['Credit']['created'];
			$deadline 			= $credit['Credit']['deadline'];
		}

		$totalCuotes = $numberQuotes / $frecuencia;
		$this->loadModel("Credit");
        $this->CreditsRequest->query("update credits set created= '". $created . "'
		value_request = ".$data['value_credit'].
		"deadline = ".$deadline.
		"quota_value = ".$cuoteValuesData["cuote"].
		"where credit_id = " .$credit['Credit']['id']);

		$number = 0;
		$creditId = $this->Credit->id;
		$this->log($creditId,"debug");
		$this->loadModel("Shop");
		$priceValue			= $this->request->data["valueCredit"];
		$totalCapitalDeuda 	= $priceValue;
		$j = 0;

		if ($explodeCreditStart[0] !== $data['date_start']) {
			$creditsPlans='';
			for ($i=1; $i <= $numberQuotes; $i++) {
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
					$days 	= 45;
					$fecha = date("Y-m-d",strtotime($fechaDataRequest."+$days days"));
					$fechaIni = date("Y-m-d",strtotime($fecha."-1 days"));

				} elseif ($this->request->data["frecuency"] == 4){
					$days 	= 60;
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


				$creditRequest["CreditsRequest"]["value_disbursed"] = $this->request->data["valueCredit"];
				if (isset($this->request->data["platform"]) && ($creditRequest["CreditsRequest"]["value_approve"] < $creditRequest["CreditsRequest"]["value_disbursed"]	) ) {
					$creditRequest["CreditsRequest"]["value_approve"] = $creditRequest["CreditsRequest"]["value_disbursed"];
					//Acá le dice que si viene de crediventas y el valor final es mayor al aprobado se actualiza el valor aprobado por la formula del 12+10000
				}
				$this->CreditsRequest->save($creditRequest);

				$this->loadModel("Disbursement");
				$dataDisbursement = array(
					"Disbursement" => [
						"value" => $this->request->data["valueCredit"],
						"credit_id" => $creditRequest["CreditsRequest"]["credit_id"],
						"shop_commerce_id" => $creditRequest["CreditsRequest"]["shop_commerce_id"],
					]
				);
				$this->Disbursement->save($dataDisbursement);
				$this->Session->setFlash(__('Crédito actualizado correctamente'), 'flash_success');

			}

	}


	/**
     * inicio detail y pago
    */
    public function payment_total($creditRequestId,$quoteId)
    {
		$this->autoLayout = false;
		$this->loadModel('Credit');
	    $fecmin = $this->Credit->query("SELECT MIN(payments.CREATED) as fechamin  from payments where payments.receipt_id  IS NULL");

        $this->Credit->CreditsRequest->recursive = 1;

        $this->Credit->CreditsRequest->unBindModel(
            ["belongsTo" => ["CreditsLine"]]
        );
        $creditRequest = $this->Credit->CreditsRequest->findById($creditRequestId);

        $creditInfo = $this->Credit->findById($creditRequest["CreditsRequest"]["credit_id"]);
        $this->Credit->CreditsRequest->recursive = 1;

        $this->Credit->CreditsRequest->unBindModel(
            ["belongsTo" => ["CreditsLine"]]
        );

        $totalNoPayment = $this->Credit->CreditsPlan->find("count",["conditions"=>["CreditsPlan.state" => 0, "CreditsPlan.credit_id" => $creditInfo["Credit"]["id"] ]]);
        $juridic = $this->Credit->find("count",["conditions"=>["Credit.juridico"=>1,"Credit.customer_id"=>$creditInfo["Credit"]["customer_id"]]]);
        if ($juridic > 0) {
            $this->Session->setFlash(__('Crédito en estado Jurídico'),'flash_error');
        }

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
        $idLast     = null;

		$quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);
        foreach ($quotes as $key => $value) {
            if ($value["CreditsPlan"]["credit_old"] == 10) {
                $idLast = $value["CreditsPlan"]["id"];
                continue;
            }
            if ($cuenta > 0) {
                $pagoA = ($value["CreditsPlan"]["state"]);
                if ($pagoA == 1) {
                    $cuotaacumulada = $cuotaacumulada + $value["CreditsPlan"]["capital_value"];
                }
            }
            $cuenta--;
        }


        $lastQuote  = [];
		$value = $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"], $quoteId);
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
			$interesesOT =  ((($creditInfo["Credit"]["value_pending"] * $creditInfo["Credit"]["others_rate"]) / 100) / 30) * $days;

			//capital
			$CapitalN =  $value["CreditsPlan"]["capital_value"];

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
			["CreditsPlan.capital_value" => (
				(
					$value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0
					and $value["CreditsPlan"]["state"] == 0
				) ? ROUND($CapitalN) : ROUND($value["CreditsPlan"]["capital_value"]),
				"CreditsPlan.interest_value" => (
					($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0
					and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesT) : ROUND($value["CreditsPlan"]["interest_value"]),
				"others_value" => (
					($value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"]) > 0
					and $value["CreditsPlan"]["state"] == 0) ? ROUND($interesesOT) : ROUND($value["CreditsPlan"]["others_value"])
			],
			["CreditsPlan.id" => $value["CreditsPlan"]["id"]]
		);
		$lastQuote = $value;
        $totalCap = 0;
        $plan_id = 0;
        $valorUltQ = 0;

        $quotes = $this->Credit->CreditsPlan->getCuotesInformation($creditRequest["CreditsRequest"]["credit_id"]);
        $totalCredit = $this->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"]);
        $totalCreditFinal = $this->Credit->CreditsPlan->getCreditDeuda($creditInfo["Credit"]["id"],null,null,true);
        for ($i = 0; $i < sizeof($quotes); $i++) {
            $whereData = "";
            $pay = $this->Credit->query("select sum(value) as PaymentA from payments where credits_plan_id= ' " . $quotes[$i]["CreditsPlan"]["id"] . " ' ".$whereData);
            $quotes[$i]["CreditsPlan"] += ["TotalAbo" => $pay[0][0]["PaymentA"]];
            $capital = $quotes[$i]["CreditsPlan"]["capital_value"];
            $interes = $quotes[$i]["CreditsPlan"]["interest_value"];
            $others  = $quotes[$i]["CreditsPlan"]["others_value"];
            $totalCP = $capital + $interes + $others;
        }

		$creditsPlansIds=[];
		foreach ($quotes as $key => $value) {
			array_push($creditsPlansIds, $value['CreditsPlan']['id']);
		}

		$this->loadModel("Payment");
        $payments = $this->Payment->find("all",
			["conditions" => [
					"Payment.credits_plan_id" => $creditsPlansIds
				]
			]
		);

		return [
			'quote' => $value,
			'totalCredit' => $totalCredit,
			'fecmin' => $fecmin,
			'totalCreditFinal' => $totalCreditFinal,
			'payments' => $payments
		];

    }

}
