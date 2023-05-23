<?php

use Sabberworm\CSS\Property\Import;

App::uses('AppController', 'Controller');
App::import('Sanitize');

class PagesController extends AppController
{

	public $uses = array("Customer", "User");

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('home', 'register_step_one', 'register_step_two2', 'register_step_three', 'creditos', 'calculate', 'plan_payments', 'connect', 'fastpayment', 'politicas_uso_informacion', 'tyc', 'pagare', 'contrato', 'normal_request', 'commerce_payment', 'crediventas', 'generate_codes', 'validate_codes_crediventas', 'dashboardcliente', 'shops', 'comercios', 'register_step_unique', 'normal_request_unique', 'validarCodigoProveedor', 'validarCedulaCliente', 'validarCorreoCliente','validarCorreoUsuario','registroMetamap','customersVerified');
	}

	private function createAdminFromCommerce($data, $shop_id)
	{
		$this->loadModel("User");
		$userInfo = ["User" => [
			"email" => $data["Shop"]["email"],
			"name"  => $data["Shop"]["name_admin"],
			"password" => $data["Shop"]["identification_admin"],
			"shop_id"  => $shop_id,
			"role"     => 4,
			"state"    => 0
		]];

		$this->User->create();
		if ($this->User->save($userInfo)) {
			$this->loadModel("Shop");
			$user_id   = $this->User->id;
			$varsEmail = [
				"plan"    => Configure::read("PLANES." . $data["Shop"]["plan"]),
				"total"   => $data["Shop"]["payment_total"],
				"commerces" => $data["Shop"]["number_commerces"],
				"name"    => $data["Shop"]["social_reason"],
				"name_user" => $data["Shop"]["name_admin"],
				"email"     => $userInfo["User"]["email"],
				"dni"     => $data["Shop"]["identification_admin"],
			];

			$shop = $this->Shop->find("first", ["conditions" => ["Shop.id" => $shop_id], "recursive" => -1, "fields" => ["Shop.id", "Shop.user_id"]]);

			$shop["Shop"]["user_id"] = $user_id;
			$this->Shop->save($shop);

			$options = [
				"subject"   => "Bienvenido a Zíro",
				"to"      => $userInfo["User"]["email"],
				"vars"      => $varsEmail,
				"template"  => "new_user_admin",
			];

			$this->sendMail($options);
		}
	}

	// public function comercios() {

	//   $this->loadModel("Shop");

	//   $this->loadModel("AuditLog");
	//   $log= [
	//       'ip' =>$this->request->clientIp(),
	//       'pagina' =>'register proveedor get',
	//       'data' => json_encode($this->request->data)
	//   ];

	//   $this->AuditLog->create();
	//   $this->AuditLog->save($log);

	//   if ($this->request->is('post') || $this->request->is('put')) {
	//     $this->autoRender = false;

	//       $this->loadModel("AuditLog");
	//       $log= [
	//           'ip' =>$this->request->clientIp(),
	//           'pagina' =>'register proveedor post',
	//           'data' => json_encode($this->request->data)
	//       ];

	//       $this->AuditLog->create();
	//       $this->AuditLog->save($log);

	//     if (!isset($this->request->data["Shop"]["id"])) {
	//       $this->Shop->create();
	//       $this->request->data["Shop"]["state"]       = 0;
	//     }

	//   //   $this->request->data["Shop"]["products_lists"]  = implode(",", $this->request->data["Shop"]["products_lists"]);

	//     if ($this->Shop->save($this->request->data)) {
	//       $shop_id = $this->Shop->id;
	// 			$this->createAdminFromCommerce($this->request->data,$shop_id);
	//       // if(!isset($this->request->data["Shop"]["id"])){
	//       //   foreach ($this->request->data["ShopReference"] as $key => $value) {
	//       //     $value["shop_id"] = $shop_id;
	//       //     $this->Shop->ShopReference->create();
	//       //     $this->Shop->ShopReference->save($value);
	//       //   }
	//       // }
	//       $this->Session->setFlash(__('Los datos se han guardado correctamente, se ha creado un usuario de acceso al sistema, los datos de ingreso son correo electrónico y la cédula del administrador. El acceso estará pendiente de activación'), 'flash_success');
	//       $this->redirect("/");
	//     } else {
	//       $this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
	//     }

	//   }
	// }

	private function createUserShopData($data, $empresa_id)
	{
		$this->loadModel("User");
		$userInfo = ["User" => [
			"email" => $data["Empresa"]["email"],
			"name"  => $data["Empresa"]["name_admin"],
			"password" => $data["Empresa"]["identification_admin"],
			"empresa_id"  => $empresa_id,
			"role"     => 15,
			"state"    => 1
		]];

		$this->User->create();
		if ($this->User->save($userInfo)) {
			$this->loadModel("Empresa");
			$user_id   = $this->User->id;
			$varsEmail = [
				"plan"    => Configure::read("PLANES." . $data["Empresa"]["plan"]),
				"total"   => $data["Empresa"]["payment_total"],
				"commerces" => $data["Empresa"]["number_commerces"],
				"name"    => $data["Empresa"]["social_reason"],
				"name_user" => $data["Empresa"]["name_admin"],
				"email"     => $userInfo["User"]["email"],
				"dni"     => $data["Empresa"]["identification_admin"],
				"empresa" => true
			];

			$shop = $this->Empresa->find("first", ["conditions" => ["Empresa.id" => $empresa_id], "recursive" => -1, "fields" => ["Empresa.id", "Empresa.user_id"]]);

			$shop["Empresa"]["user_id"] = $user_id;
			$this->Empresa->save($shop);

			$options = [
				"subject"   => "Bienvenido a Zíro",
				"to"      => $userInfo["User"]["email"],
				"vars"      => $varsEmail,
				"template"  => "new_user_admin",
			];

			$this->sendMail($options);
		}
	}

	public function shops()
	{
		$this->loadModel("Empresa");
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->autoRender = false;

			if (!isset($this->request->data["Empresa"]["id"])) {
				$this->Empresa->create();
				$this->request->data["Empresa"]["state"]       = 1;
			}

			//   $this->request->data["Empresa"]["products_lists"]  = implode(",", $this->request->data["Empresa"]["products_lists"]);
			if ($this->Empresa->save($this->request->data)) {
				$empresa_id = $this->Empresa->id;
				if (!isset($this->request->data["Empresa"]["id"])) {
					foreach ($this->request->data["EmpresaReference"] as $key => $value) {
						$value["empresa_id"] = $empresa_id;
						$this->Empresa->EmpresaReference->create();
						$this->Empresa->EmpresaReference->save($value);
					}
					$this->createUserShopData($this->request->data, $empresa_id);
				}
				$this->Session->setFlash(__('Los datos se han guardado correctamente, se ha creado un usuario de acceso al sistema, los datos de ingreso son correo electrónico y la cédula del administrador.'), 'flash_success');
				$this->redirect("/");
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
	}

	public function display()
	{
	}

	public function generate_codes($phone = null, $email = null)
	{
		$this->response->header('Access-Control-Allow-Origin', '*');
		$this->autoRender = false;
		if (!isset($this->request->data["email"]) || !isset($this->request->data["phone"]) || empty($this->request->data["phone"]) || empty($this->request->data["email"])) {
			return 2;
		}

		$sesion_id = $this->Session->read("SESSION_ID_CUS");

		if (is_null($sesion_id)) {
			$sesion_id = uniqid();
			$this->Session->write("SESSION_ID_CUS", $sesion_id);
		}



		$email = $this->request->data["email"];
		$phone = $this->request->data["phone"];

		$codes = $this->getCodesCustomer(null, null, $sesion_id, $email, $phone);
		$this->Session->write("SESSION_CODES", $codes);

		return 1;
	}

	public function validate_codes_crediventas()
	{
		$this->autoRender = false;
		$this->loadModel("CustomersCode");
		$sesion_id = $this->Session->read("SESSION_ID_CUS");

		if ($sesion_id == null) {
			return "Por favor recargue la página ya que se ha perdido la sesión";
		} else {
			$validTimeEmail = $this->CustomersCode->findByCodeAndSesIdAndTypeCodeAndState($this->request->data["email"], $sesion_id, 1, 0);

			$validTimePhone = $this->CustomersCode->findByCodeAndSesIdAndTypeCodeAndState($this->request->data["phone"], $sesion_id, 2, 0);

			if (empty($validTimeEmail) || empty($validTimePhone)) {
				return __('Error, uno o los dos códigos expiraron su vigencia, revisa los códigos que fueron enviados nuevamente.');
			} else {
				$validTimeEmail["CustomersCode"]["state"] = 1;
				$validTimePhone["CustomersCode"]["state"] = 1;

				$this->CustomersCode->save($validTimeEmail);
				$this->CustomersCode->save($validTimePhone);
				return 1;
			}
		}
	}

	public function crediventas()
	{
		$this->layout = "layout-home";

		$this->Session->delete("SESSION_ID_CUS");
		$this->Session->delete("SESSION_CODES");

		if ($this->request->is("post")) {
			$this->autoRender = false;
			$this->loadModel("ShopCommerce");
			$this->loadModel("Customer");
			$existsCommerce = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"], "state" => 1]);

			if (!$existsCommerce) {
				return "El código de proveedor no existe";
			} else {
				$customer = $this->Customer->find("first", ["conditions" => ["identification" => $this->request->data["Customer"]["identification"], "type" => 1], "recursive" => -1]);

				if (!empty($customer)) {
					$this->loadModel("CreditsRequest");
					$actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState($customer["Customer"]["id"], $existsCommerce, [0, 1, 2]);

					if (!empty($actualStudy)) {
						return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo";
						die;
					}
				}

				if (empty($customer)) {
					$this->Customer->Create();
					$customer = $this->request->data["Customer"];
				} else {
					$customer["Customer"] = array_merge($customer["Customer"], $this->request->data["Customer"]);
				}

				if ($this->Customer->save($customer)) {
					$customerID = $this->Customer->id;

					$this->Customer->CustomersPhone->deleteAll(array('CustomersPhone.customer_id' => $customerID), false);
					$this->Customer->CustomersAddress->deleteAll(array('CustomersAddress.customer_id' => $customerID), false);
					$this->Customer->CustomersReference->deleteAll(array('CustomersReference.customer_id' => $customerID), false);

					$data = $this->request->data;

					if (!empty($data["CustomersReference"])) {
						foreach ($data["CustomersReference"] as $key => $value) {
							$value["customer_id"] = $customerID;
							$this->Customer->CustomersReference->create();
							$this->Customer->CustomersReference->save($value);
						}
					}

					if (!empty($data["CustomersAddress"])) {
						$data["CustomersAddress"]["customer_id"] = $customerID;
						$this->Customer->CustomersAddress->create();
						$this->Customer->CustomersAddress->save($data["CustomersAddress"]);
					}

					if (!empty($data["CustomersPhone"])) {
						foreach ($data["CustomersPhone"] as $key => $value) {
							$value["customer_id"] = $customerID;
							if (!empty($value["phone_number"])) {
								$this->Customer->CustomersPhone->create();
								$this->Customer->CustomersPhone->save($value);
							}
						}
					}

					$this->loadModel("ShopCommerce");
					$this->loadModel("CreditsRequest");
					$this->loadModel("CreditsLine");

					$shop_commerce_id = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"]]);
					$creditLineId = $this->CreditsLine->findByState(1);

					$dataRequest = [
						"CreditsRequest" => [
							"customer_id" => $customerID,
							"request_value" => $this->request->data["priceValue"],
							"request_number" => $this->request->data["couteValue"],
							"credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
							"shop_commerce_id" => $shop_commerce_id,
							"request_type" => $this->request->data["frecuency"],
						]
					];
					$this->CreditsRequest->create();
					if ($this->CreditsRequest->save($dataRequest)) {
						$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
						return 1;
					}
				} else {
					return "Error al guardar, por favor inténtelo de nuevo";
				}
			}
		}
	}

	/*public function home(){
		 $this->layout = "layout-home";
     if(AuthComponent::user("id")){
        if(AuthComponent::user("customer_new_request") == 5){
          $this->loadModel("User");
          $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 1]]);
          $this->redirect(array("controller"=>"pages","action"=>"register_step_one"));
        }
     }
	}*/
	public function home()
	{
		$this->layout = "layout-home";

		if (AuthComponent::user("id")) {
			if (AuthComponent::user("customer_new_request") == 5) {
				$this->loadModel("User");
				$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 1]]);
				$this->redirect(array("controller" => "pages", "action" => "register_step_one"));
			}
		}

		$this->loadModel("CreditsLine");
		$this->CreditsLine->recursive = -1;
		$creditLine = $this->CreditsLine->findByState(1);
		$creditLineId = $creditLine["CreditsLine"]["id"];

		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);

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

		//$sayHello = $valorMini;
		$this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth", "data"));
	}

	public function get_indice()
	{
		$this->layout = false;
		$this->loadModel("Credit");

		$cuotesValues = $this->Credit->CreditsPlan->getQuotesCobranzasTotal();

		$creditsIds = [];

		if (!empty($cuotesValues)) {
			foreach ($cuotesValues as $key => $value) {
				$creditsIds[$value["Credit"]["id"]] = $value["Credit"]["value_request"];
			}
		}

		$days30 = $days60 = $days90 = 0;

		$days30Total = $days60Total = $days90Total = 0;

		$iniMonths = date("Y-m-d", strtotime("-6 month"));
		$endMonths = date("Y-m-d");

		$totalDisburment = array_sum($creditsIds);

		$credits30 = [];
		$credits60 = [];
		$credits90 = [];

		if (!empty($cuotesValues)) {
			foreach ($cuotesValues as $key => $value) {
				if ($value["0"]["dias"] <= 30 && !in_array($value["CreditsPlan"]["credit_id"], $credits30)) {
					$days30++;
					//$days30Total+= ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
					$credits30[] = $value["CreditsPlan"]["credit_id"];
					$days30Total += ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]) + ($value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"]) + ($value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"]) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
				} elseif (($value["0"]["dias"] <= 60 && in_array($value["CreditsPlan"]["credit_id"], $credits30) && !in_array($value["CreditsPlan"]["credit_id"], $credits60)) || ($value["0"]["dias"] <= 60 && !in_array($value["CreditsPlan"]["credit_id"], $credits60))) {
					$days60++;
					//$days60Total+= ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
					$credits30[] = $value["CreditsPlan"]["credit_id"];
					$credits60[] = $value["CreditsPlan"]["credit_id"];
					$days60Total += ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]) + ($value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"]) + ($value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"]) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
				} else {
					if (in_array($value["CreditsPlan"]["credit_id"], $credits30)) {
						continue;
					}
					$days90++;
					//$days90Total+= ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
					$credits30[] = $value["CreditsPlan"]["credit_id"];
					$days90Total += ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]) + ($value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"]) + ($value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"]) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
				}
			}
		}

		$this->set("days30", $days30);
		$this->set("days60", $days60);
		$this->set("days90", $days90);

		$this->set("days30Total", $days30Total);
		$this->set("days60Total", $days60Total);
		$this->set("days90Total", $days90Total);


		$this->set("totalDisburment", $totalDisburment);
	}

	public function dashboard()
	{
		if (!isset($this->request->query["dateIni"]) || !isset($this->request->query["dateEnd"])) {
			$this->redirect(["controller" => "pages", "action" => "dashboard", "?" => ["dateIni" => date("Y-m-d", strtotime("-6 month")), "dateEnd" => date("Y-m-d")]]);
		} else {
			$this->loadModel("Credit");
			$this->loadModel("CreditsRequest");
			$this->loadModel("ShopPaymentRequest");
			$this->loadModel("Payment");

			$dateIni = $this->request->query["dateIni"];
			$dateEnd = $this->request->query["dateEnd"];

			$dateIniValues = date("Y-m-01");
			$dateEndValues = date("Y-m-d");

			$iniMonths = date("Y-m-d", strtotime("-6 month"));
			$endMonths = date("Y-m-d");

			$values = $colors = $months = [];

			$totalNoApprove = $this->CreditsRequest->field("SUM(request_value) total", ["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $endMonths, "state" => 4]);
			$CounttotalNoApprove = $this->CreditsRequest->field("COUNT(id) total", ["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $endMonths, "state" => 4]);

			$totalNoDisburment = $this->CreditsRequest->field("SUM(value_approve) total", ["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $endMonths, "state" => 3]);
			$CounttotalNoDisburment = $this->CreditsRequest->field("COUNT(id) total", ["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $endMonths, "state" => 3]);

			$totalPaymentCredit = $this->Credit->field("SUM(value_request) total", ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $endMonths, "Credit.credits_request_id != " => 0, "Credit.state" => 1]);
			$CounttotalPaymentCredit = $this->Credit->field("COUNT(id) total", ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $endMonths, "Credit.credits_request_id != " => 0, "Credit.state" => 1]);

			$totalDisburment = $this->Credit->field("SUM(value_request) as total", ["DATE(Credit.created) >=" => $dateIni, "DATE(Credit.created) <=" => $endMonths, "Credit.credits_request_id != " => 0]);
			$CounttotalDisburment = $this->Credit->field("COUNT(id) total", ["DATE(Credit.created) >= " => $dateIni, "DATE(Credit.created) <= " => $endMonths, "Credit.credits_request_id != " => 0]);

			$totalApprove = $this->CreditsRequest->field("SUM(value_approve) total", ["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $endMonths, "state" => [3, 5]]);
			$CounttotalApprove = $this->CreditsRequest->field("COUNT(id) total", ["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $endMonths, "state" => [3, 5]]);

			$totalNoShop = $this->ShopPaymentRequest->field("SUM(request_value) total", ["DATE(request_date) >= " => $dateIni, "DATE(request_date) <= " => $endMonths, "state" => 0]);
			$CounttotalNoShop = $this->ShopPaymentRequest->field("COUNT(id) total", ["DATE(request_date) >= " => $dateIni, "DATE(request_date) <= " => $endMonths, "state" => 0]);

			$totalNoCommerce = $this->Payment->field("SUM(value) total", ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $endMonths, "state_credishop" => 0, "value >" => 0]);
			$CounttotalNoCommerce2 = $this->Payment->find("all", ["conditions" => ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $endMonths, "state_credishop" => 0], "recursive" => -1]);

			$CounttotalNoCommerce = [];
			if (!empty($CounttotalNoCommerce2)) {
				foreach ($CounttotalNoCommerce2 as $key => $value) {
					if (!in_array($value["Payment"]["receipt_id"], $CounttotalNoCommerce)) {
						$CounttotalNoCommerce[] = $value["Payment"]["receipt_id"];
					}
				}
				$CounttotalNoCommerce = count($CounttotalNoCommerce);
			} else {
				$CounttotalNoCommerce = 0;
			}

			$totalSiMora = $this->Credit->find("first", ["fields" =>  ["SUM(value_pending) as total"], "conditions" => ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $endMonths, "state" => 0, "debt_days" => 0], "recursive" => -1]);

			$CountTotalSiMora = $this->Credit->find("count", ["fields" =>  ["SUM(value_pending) as total"], "conditions" => ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $endMonths, "state" => 0, "debt_days" => 0], "recursive" => -1]);

			$totalMonths = 0;

			$totalByMonth = $this->CreditsRequest->find("all", ["fields" =>  ["SUM(value_disbursed) as total", "MONTH(date_disbursed) as mes"], "group" => ["MONTH(date_disbursed)"], "conditions" => ["DATE(date_disbursed) >=" => $iniMonths, "DATE(date_disbursed) <=" => $endMonths, "state" => [5, 7]], "recursive" => -1, "order" => ["date_disbursed" => "ASC"]]);

			if (!empty($totalByMonth)) {

				foreach ($totalByMonth as $key => $value) {
					$totalMonths += floatval($value["0"]["total"]);
					$values[] =  floatval($value["0"]["total"]);
					$colors[] =  Configure::read("COLORS." . $value["0"]["mes"]);
					$months[] =  Configure::read("MONTHS." . $value["0"]["mes"]);
				}
			}

			$cuotesValues = [];


			$this->set("totalSiMora", $totalSiMora);
			$this->set("CountTotalSiMora", $CountTotalSiMora);

			$this->set("totalNoApprove", $totalNoApprove);
			$this->set("CounttotalNoApprove", $CounttotalNoApprove);

			$this->set("totalNoDisburment", $totalNoDisburment);
			$this->set("CounttotalNoDisburment", $CounttotalNoDisburment);

			$this->set("totalPaymentCredit", $totalPaymentCredit);
			$this->set("CounttotalPaymentCredit", $CounttotalPaymentCredit);

			$this->set("totalDisburment", $totalDisburment);
			$this->set("CounttotalDisburment", $CounttotalDisburment);

			$this->set("totalApprove", $totalApprove);
			$this->set("CounttotalApprove", $CounttotalApprove);

			$this->set("totalNoShop", $totalNoShop);
			$this->set("CounttotalNoShop", $CounttotalNoShop);

			$this->set("totalNoCommerce", $totalNoCommerce);
			$this->set("CounttotalNoCommerce", $CounttotalNoCommerce);

			$this->set("dateIni", $dateIni);
			$this->set("dateEnd", $dateEnd);

			$this->set("values", $values);
			$this->set("colors", $colors);
			$this->set("months", $months);


			$this->set("totalMora", count($cuotesValues));
			$this->set("totalMonths", $totalMonths);
		}
	}

	/*public function register_step_four() {

        $this->layout = "layout-home";

        $codes = $this->getCodesCustomer();
        $this->set("codeEmail", $codes["codeEmail"]);
        $this->set("codePhone", $codes["codePhone"]);

        if ($this->request->is("post")) {

            if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
                $totalActual = $this->totalQuote(true);
                if ($totalActual > 0 && $this->request->data["priceValue"] < $totalActual) {
                    $this->Session->setFlash(__('Error, el monto solicitado supera el preaprovado actual de: ') . $totalActual, 'flash_error');
                    $this->redirect(["action" => "register_step_four"]);
                }
            }

            $this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_complete" => 1]]);
            $this->overwrite_session_user(AuthComponent::user('id'));

            $this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 6]]);
            $this->overwrite_session_user(AuthComponent::user('id'));

            $this->Session->setFlash(__('Registro finalizado'), 'flash_success');

            $this->created();

            $this->redirect(["controller" => "credits_requests", "action" => "index"]);

        }

  }*/
	public function register_step_four()
	{
		$this->layout = "layout-home";

		//$codes = $this->getCodesCustomer();
		$this->set("codeEmail", "");
		$this->set("codePhone", "");

		if ($this->request->is("post")) {



			if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
				$totalActual = $this->totalQuote(true);
				if ($totalActual > 0 && $this->request->data["priceValue"] < $totalActual) {
					$this->Session->setFlash(__('Error, el monto solicitado supera el preaprovado actual de: ') . $totalActual, 'flash_error');
					$this->redirect(["action" => "register_step_four"]);
				}
			}



			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_complete" => 1]]);
			$this->overwrite_session_user(AuthComponent::user('id'));

			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 6]]);
			$this->overwrite_session_user(AuthComponent::user('id'));

			$this->Session->setFlash(__('Registro finalizado'), 'flash_success');

			$this->created();

			$this->redirect(["controller" => "pages", "action" => "dashboardcliente"]);
		}

		$this->loadModel("CreditsLine");
		$this->CreditsLine->recursive = -1;
		$creditLine = $this->CreditsLine->findByState(1);
		$creditLineId = $creditLine["CreditsLine"]["id"];

		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);

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

		//$sayHello = $valorMini;
		$this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth", "data"));
	}


	public function created()
	{

		$this->loadModel("CreditsRequest");


		$existsCredit = $this->CreditsRequest->findAllByCustomerIdAndState(AuthComponent::user("customer_id"), [0, 1]);
		$code         = $this->validateCodeCommerce();


		//generar codigo al credito de 13 digitos
		$code_pay = $this->generarCodigoCredito();

		$this->loadModel("ShopCommerce");
		$shop_commerce_id = $this->ShopCommerce->field("id", ["code" => $code]);
		$this->loadModel("CreditsLine");
		$creditLineId = $this->CreditsLine->findByState(1);
		$data = [
			"CreditsRequest" => [
				"code_pay"          => $code_pay,
				"customer_id" => AuthComponent::user("customer_id"),
				"request_value" => $this->request->data["priceValue"],
				// "request_number" => $this->request->data["couteValue"],
				"request_number" => 1,
				"credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
				"shop_commerce_id" => $shop_commerce_id,
				// "request_type" => $this->request->data["frecuency"]
				"request_type" => 1

			]
		];
		$this->CreditsRequest->create();
		if ($this->CreditsRequest->save($data)) {
			$this->loadModel("User");
			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 6]]);
			$this->overwrite_session_user(AuthComponent::user('id'));
			$this->CreditsRequest->CreditLimit->updateAll(
				[
					"state" => 2,
					"reason" => "'Cancelado por solicitud nueva de cupo'",
				],
				[
					"CreditLimit.customer_id" => AuthComponent::user("customer_id"),
					"CreditLimit.state" => [1, 3, 5]
				]
			);
			$this->Session->write("CODE_COMMERCE", null);
			$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
		}
	}

	public function generarCodigoCredito()
	{
		$caracteres_permitidos = '0123456789012';
		$longitud = 13;
		$code_pay = substr(str_shuffle($caracteres_permitidos), 0, $longitud);
		$this->loadModel("CreditsRequest");
		$existeCodigo = $this->CreditsRequest->field("id", ["code_pay" => $code_pay]);
		$flagCodePay = false;
		if (!$existeCodigo) {
			$flagCodePay = true;
		}
		while (!$flagCodePay) {
			$code_pay = substr(str_shuffle($caracteres_permitidos), 0, $longitud);
			$existeCodigo = $this->CreditsRequest->field("id", ["code_pay" => $code_pay]);
			if (!$existeCodigo) {
				$flagCodePay = true;
			}
		}
		return $code_pay;
	}


	public function newRequest()
	{
		if (AuthComponent::user("id")) {

			$step = AuthComponent::user("customer_new_request") == 6 ? 1 : AuthComponent::user("customer_new_request");
			switch ($step) {
				case '1':
					//$action = "register_step_one";
					$action = "register_step_unique";
					break;
				case '2':
					$action = "register_step_two";
					break;
				case '3':
					$action = "register_step_three";
					break;
				case '4':
					$action = "register_step_four";
					break;
				case '5':
					$action = "register_step_four";
					break;
			}
			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => $step]]);
			$this->overwrite_session_user(AuthComponent::user('id'));
			$this->redirect(["action" => $action]);
		}
	}

	/*public function normal_request(){
    $this->layout = "layout-home";

    if($this->request->is("ajax") && $this->request->is("post") ){
      $this->autoRender = false;
      $this->loadModel("ShopCommerce");
      $existsCommerce = $this->ShopCommerce->field("id",["code" => $this->request->data["Customer"]["code"],"state" => 1]);

      if(!$existsCommerce){
          return "El código de proveedor no existe";
      }else{
          $customer = $this->Customer->find("first",["conditions" => ["identification"=>$this->request->data["Customer"]["identification"]],"recursive" => -1 ]);

          if(!empty($customer)){
            $this->loadModel("CreditsRequest");
            $actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState($customer["Customer"]["id"],$existsCommerce,[0,1,2]);

            if(!empty($actualStudy)){
              return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo";
              die;
            }
          }

          if(empty($customer)){
            $this->Customer->Create();
            $customer = $this->request->data["Customer"];
          }else{
            $customer["Customer"] = array_merge($customer["Customer"],$this->request->data["Customer"]);
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

            $dataRequest = [
              "CreditsRequest" => [
                "customer_id" => $customerID,
                "request_value" => $this->request->data["priceValue"],
                "request_number" => $this->request->data["couteValue"],
                "credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
                "shop_commerce_id" => $shop_commerce_id,
                "request_type" => $this->request->data["frecuency"]
              ]
            ];
            $this->CreditsRequest->create();
            if ($this->CreditsRequest->save($dataRequest)) {
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
              $this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
              return 1;
            }
          }else{
            return "Error al guardar, por favor inténtelo de nuevo";
          }

      }
    }
  }*/

	public function normal_request()
	{
		$this->layout = "layout-home";

		if ($this->request->is("ajax") && $this->request->is("post")) {
			$this->autoRender = false;
			$this->loadModel("ShopCommerce");
			$existsCommerce = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"], "state" => 1]);

			if (!$existsCommerce) {
				$this->Session->setFlash(__('El código de proveedor no existe.'), 'flash_error');
				return "El código de proveedor no existe";
			} else {
				$customer = $this->Customer->find("first", ["conditions" => ["identification" => $this->request->data["Customer"]["identification"]], "recursive" => -1]);

				if (!empty($customer)) {
					$this->loadModel("CreditsRequest");
					$actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState($customer["Customer"]["id"], $existsCommerce, [0, 1, 2]);

					if (!empty($actualStudy)) {
						$this->Session->setFlash(__('Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo.'), 'flash_error');
						return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo";
						die;
					}
				}

				if (empty($customer)) {
					$this->Customer->Create();
					$customer = $this->request->data["Customer"];
				} else {
					$customer["Customer"] = array_merge($customer["Customer"], $this->request->data["Customer"]);
				}

				if ($this->Customer->save($customer)) {
					$customerID = $this->Customer->id;

					$this->Customer->CustomersPhone->deleteAll(array('CustomersPhone.customer_id' => $customerID), false);
					$this->Customer->CustomersAddress->deleteAll(array('CustomersAddress.customer_id' => $customerID), false);
					$this->Customer->CustomersReference->deleteAll(array('CustomersReference.customer_id' => $customerID), false);

					$data = $this->request->data;

					if (!empty($data["CustomersReference"])) {
						foreach ($data["CustomersReference"] as $key => $value) {
							$value["customer_id"] = $customerID;
							$this->Customer->CustomersReference->create();
							$this->Customer->CustomersReference->save($value);
						}
					}

					if (!empty($data["CustomersAddress"])) {
						$data["CustomersAddress"]["customer_id"] = $customerID;
						$this->Customer->CustomersAddress->create();
						$this->Customer->CustomersAddress->save($data["CustomersAddress"]);
					}

					if (!empty($data["CustomersPhone"])) {
						foreach ($data["CustomersPhone"] as $key => $value) {
							$value["customer_id"] = $customerID;
							if (!empty($value["phone_number"])) {
								$this->Customer->CustomersPhone->create();
								$this->Customer->CustomersPhone->save($value);
							}
						}
					}

					$this->loadModel("ShopCommerce");
					$this->loadModel("CreditsRequest");
					$this->loadModel("CreditsLine");

					$shop_commerce_id = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"]]);
					$creditLineId = $this->CreditsLine->findByState(1);

					$dataRequest = [
						"CreditsRequest" => [
							"customer_id" => $customerID,
							"request_value" => $this->request->data["priceValue"],
							"request_number" => $this->request->data["couteValue"],
							"credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
							"shop_commerce_id" => $shop_commerce_id,
							"request_type" => $this->request->data["frecuency"],
						],
					];
					$this->CreditsRequest->create();
					if ($this->CreditsRequest->save($dataRequest)) {
						$this->CreditsRequest->CreditLimit->updateAll(
							[
								"state" => 2,
								"reason" => "'Cancelado por solicitud nueva de cupo'",
							],
							[
								"CreditLimit.customer_id" => AuthComponent::user("customer_id"),
								"CreditLimit.state" => [1, 3, 5],
							]
						);
						$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
						return 1;
					}
				} else {
					$this->Session->setFlash(__('Error al guardar, por favor inténtelo de nuevo'), 'flash_error');
					return "Error al guardar, por favor inténtelo de nuevo";
				}
			}
		}

		$this->loadModel("CreditsLine");
		$this->CreditsLine->recursive = -1;
		$creditLine = $this->CreditsLine->findByState(1);
		$creditLineId = $creditLine["CreditsLine"]["id"];

		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);

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

		//$sayHello = $valorMini;
		$this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth", "data"));
	}

	public function normal_request_unique()
	{
		$this->layout = "layout-home";

		if ($this->request->is("ajax") && $this->request->is("post")) {
			$this->autoRender = false;
			$this->loadModel("ShopCommerce");
			$existsCommerce = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"], "state" => 1]);

			if (!$existsCommerce) {
				$this->Session->setFlash(__('El código de proveedor no existe.'), 'flash_error');
				return "El código de proveedor no existe";
			} else {
				$customer = $this->Customer->find("first", ["conditions" => ["identification" => $this->request->data["Customer"]["identification"]], "recursive" => -1]);

				if (!empty($customer)) {
					$this->loadModel("CreditsRequest");
					$actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState($customer["Customer"]["id"], $existsCommerce, [0, 1, 2]);

					if (!empty($actualStudy)) {
						$this->Session->setFlash(__('Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo.'), 'flash_error');
						return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo";
						die;
					}
				}

				if (empty($customer)) {
					$this->Customer->Create();
					$customer = $this->request->data["Customer"];
				} else {
					$customer["Customer"] = array_merge($customer["Customer"], $this->request->data["Customer"]);
				}

				if ($this->Customer->save($customer)) {
					$customerID = $this->Customer->id;

					$this->Customer->CustomersPhone->deleteAll(array('CustomersPhone.customer_id' => $customerID), false);
					$this->Customer->CustomersAddress->deleteAll(array('CustomersAddress.customer_id' => $customerID), false);
					$this->Customer->CustomersReference->deleteAll(array('CustomersReference.customer_id' => $customerID), false);

					$data = $this->request->data;

					if (!empty($data["CustomersReference"])) {
						foreach ($data["CustomersReference"] as $key => $value) {
							$value["customer_id"] = $customerID;
							$this->Customer->CustomersReference->create();
							$this->Customer->CustomersReference->save($value);
						}
					}

					if (!empty($data["CustomersAddress"])) {
						$data["CustomersAddress"]["customer_id"] = $customerID;
						$this->Customer->CustomersAddress->create();
						$this->Customer->CustomersAddress->save($data["CustomersAddress"]);
					}

					if (!empty($data["CustomersPhone"])) {
						foreach ($data["CustomersPhone"] as $key => $value) {
							$value["customer_id"] = $customerID;
							if (!empty($value["phone_number"])) {
								$this->Customer->CustomersPhone->create();
								$this->Customer->CustomersPhone->save($value);
							}
						}
					}

					$this->loadModel("ShopCommerce");
					$this->loadModel("CreditsRequest");
					$this->loadModel("CreditsLine");

					$shop_commerce_id = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"]]);
					$creditLineId = $this->CreditsLine->findByState(1);

					$dataRequest = [
						"CreditsRequest" => [
							"customer_id" => $customerID,
							"request_value" => $this->request->data["priceValue"],
							"request_number" => $this->request->data["couteValue"],
							"credits_line_id" => is_null($creditLineId) ? 1 : $creditLineId["CreditsLine"]["id"],
							"shop_commerce_id" => $shop_commerce_id,
							"request_type" => $this->request->data["frecuency"],
						],
					];
					$this->CreditsRequest->create();
					if ($this->CreditsRequest->save($dataRequest)) {
						$this->CreditsRequest->CreditLimit->updateAll(
							[
								"state" => 2,
								"reason" => "'Cancelado por solicitud nueva de cupo'",
							],
							[
								"CreditLimit.customer_id" => AuthComponent::user("customer_id"),
								"CreditLimit.state" => [1, 3, 5],
							]
						);
						$this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
						return 1;
					}
				} else {
					$this->Session->setFlash(__('Error al guardar, por favor inténtelo de nuevo'), 'flash_error');
					return "Error al guardar, por favor inténtelo de nuevo";
				}
			}
		}

		$this->loadModel("CreditsLine");
		$this->CreditsLine->recursive = -1;
		$creditLine = $this->CreditsLine->findByState(1);
		$creditLineId = $creditLine["CreditsLine"]["id"];

		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);

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

		//$sayHello = $valorMini;
		$this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth", "data"));
	}

	public function register_step_unique($comercio = null, $codigo = null)
	{
		// debug($hashedPassword = AuthComponent::password('166'));
		// die();

		$this->layout = "layout-home";
		$this->loadModel("AuditLog");
		$log= [
			'ip' =>$this->request->clientIp(),
			'pagina' =>'register get'
		];

		$this->AuditLog->create();
		$this->AuditLog->save($log);


		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
		}

		if ($this->request->is("ajax") && ($this->request->is("post") || $this->request->is("put"))) {
			$this->autoRender = false;

			//validar si cliente esta verificado
			$this->loadModel("customersVerified");
			$cedula=$this->request->data["Customer"]["identification"];
			$customerVerified = $this->customersVerified->field("id", ["identification" => trim($cedula)]);
			if (empty($customerVerified)) {
				$this->Session->setFlash(__('No se puede guardar la información debido a que en nuestros registros la cédula '.$cedula.' no se encuentra verificada.'), 'flash_error');
				return 'No se puede guardar la información debido a que en nuestros registros la cédula '.$cedula.' no se encuentra verificada.';
			}
			if (empty($customer)) {
				$this->Customer->create();
				$existCustomer = $this->Customer->find("first", ["conditions" => ["identification" => trim($this->request->data["Customer"]["identification"]), "type" => 1], "recursive" => -1]);
				$emailExists = $this->Customer->User->field("email", ["email" => $this->request->data["Customer"]["email"]]);

				if ($emailExists != false || !empty($existCustomer)) {
					$this->Session->setFlash(__('El correo eléctronico o la cédula ya están registradas'), 'flash_error');
					return "El correo eléctronico o la cédula ya están registradas";
				} elseif (!empty($existCustomer)) {
					$this->Session->setFlash(__('La cédula ya está registrada'), 'flash_error');
					return "La cédula ya está registrada";
				}
			}

			$this->loadModel("ShopCommerce");
			$existsCommerce = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"], "state" => 1]);

			if (!$existsCommerce) {
				$this->Session->setFlash(__('El código de proveedor no existe'), 'flash_error');
				return "El código de proveedor no existe";
			} else {
				if (!empty($customer)) {
					$this->loadModel("CreditsRequest");
					$actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState(AuthComponent::user("customer_id"), $existsCommerce, [0, 1, 2]);

					if (!empty($actualStudy)) {
						$this->Session->setFlash(__('Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al mismo tiempo'), 'flash_error');
						return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al mismo tiempo";
					}
				}
			}

			//$this->request->dataer"]["type"] = 1;
			$data["Customer"]["id"]         = AuthComponent::user("customer_id");
			$data["Customer"]["data_full"]  = 1;
			$data["Customer"]["type"]  = 1;
			$data["Customer"]["document_file_up"]  =  $this->request->data["Customer"]["document_file_up2"];
			$data["Customer"]["document_file_down"]  =  $this->request->data["Customer"]["document_file_down2"];
			$data["Customer"]["image_file"]  =  $this->request->data["Customer"]["image_file2"];
			$data["Customer"] = array_merge($data["Customer"], $this->request->data["Customer"]);
			//if($this->Customer->save($datosLimpios)){
			if ($this->Customer->save($data)) {
				$customer_id  = $this->Customer->id;
				$this->Session->write("CODE_COMMERCE", $this->request->data["Customer"]["code"]);
				if (empty($customer)) {
					$registerUser = $this->create_and_login_user($this->request->data,$customer_id);
				} else {
					$registerUser = true;
					$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 2]]);
					$this->overwrite_session_user(AuthComponent::user('id'));
				}
				if ($registerUser) {
					//$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
					//return "register_step_unique";
				} else {
					$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
					return "Error al guardar la información, vuelva a intentarlo.";
				}
			}
		}

		$this->validateCodeCommerce();

		if ($this->request->is("post")  || $this->request->is("put")) {
			$this->loadModel("customersVerified");
			$cedula=$this->request->data["Customer"]["identification"];
			$customerVerified = $this->customersVerified->field("id", ["identification" => trim($cedula)]);
			if (empty($customerVerified)) {
				$this->Session->setFlash(__('No se puede guardar la información debido a que en nuestros registros la cédula '.$cedula.' no se encuentra verificada.'), 'flash_error');
				return 'No se puede guardar la información debido a que en nuestros registros la cédula '.$cedula.' no se encuentra verificada.';
			}

			$this->request->data["Customer"]["id"] = AuthComponent::user("customer_id");
			//if ($this->Customer->save($datosLimpios)) {
			if (empty($customer)) {
				$this->User->save(["User" => ["id" => AuthComponent::user("id"), "name" => $this->request->data["Customer"]["name"] . " " . $this->request->data["Customer"]["last_name"]]]);
				$this->overwrite_session_user(AuthComponent::user('id'));
			} else {
				if (isset($this->request->data["name"])) {
					$this->User->save(["User" => ["id" => AuthComponent::user("id"), "name" => $this->request->data["Customer"]["name"] . " " . $this->request->data["Customer"]["last_name"]]]);
				}
			}
			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 3]]);
			$this->overwrite_session_user(AuthComponent::user('id'));
			//$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			//$this->redirect(array('action' => 'register_step_four',"controller"=>"pages"));
			//} else {
			//$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			//}
		}

		//referencias de los clientes
		if ($this->request->is("post")) {
			$this->loadModel("customersVerified");
			$cedula=$this->request->data["Customer"]["identification"];
			$customerVerified = $this->customersVerified->field("id", ["identification" => trim($cedula)]);
			if (empty($customerVerified)) {
				$this->Session->setFlash(__('No se puede guardar la información debido a que en nuestros registros la cédula '.$cedula.' no se encuentra verificada.'), 'flash_error');
				return 'No se puede guardar la información debido a que en nuestros registros la cédula '.$cedula.' no se encuentra verificada.';
			}
			if (!empty($this->request->data["CustomersReference"])) {
				foreach ($this->request->data["CustomersReference"] as $key => $value) {

					$this->Customer->CustomersReference->set($value);
					if ($this->Customer->CustomersReference->validates()) {
					} else {
						// didn't validate logic
						$this->Session->setFlash($this->Customer->CustomersReference->validationErrors);
					}

					$value["customer_id"] = AuthComponent::user("customer_id");
					if (!isset($value["id"])) {
						$this->Customer->CustomersReference->create();
					}
					$this->Customer->CustomersReference->save($value);
				}
			}

			if (!empty($this->request->data["CustomersAddress"])) {
				$this->request->data["CustomersAddress"]["customer_id"] = AuthComponent::user("customer_id");
				if (!isset($this->request->data["CustomersAddress"]["id"])) {
					$this->Customer->CustomersAddress->create();
				}
				$this->Customer->CustomersAddress->save($this->request->data["CustomersAddress"]);
			}

			if (!empty($this->request->data["CustomersPhone"])) {
				foreach ($this->request->data["CustomersPhone"] as $key => $value) {
					$value["customer_id"] = AuthComponent::user("customer_id");
					if (!empty($value["phone_number"])) {
						if (!isset($value["id"])) {
							$this->Customer->CustomersPhone->create();
						}
						$this->Customer->CustomersPhone->save($value);
					}
				}
			}

			$data["Customer"]["id"]         = AuthComponent::user("customer_id");
			$data["Customer"]["data_full"]  = 1;

			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 4]]);
			$this->overwrite_session_user(AuthComponent::user('id'));

			//$this->Session->setFlash(__('Los datos se han guardado correctamentes'), 'flash_success');
			//$this->redirect(array('action' => 'register_step_unique',"controller"=>"pages"));
			//} else {
			//$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			//}



			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_complete" => 1]]);
			$this->overwrite_session_user(AuthComponent::user('id'));
			$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 6]]);
			$this->overwrite_session_user(AuthComponent::user('id'));
			$this->Session->setFlash(__('Registro finalizado'), 'flash_success');
			$this->created();

			//enviar mensaje cel

			//$this->sendMessajeRegister($this->request->data["CustomersPhone"][1]["phone_number"]);

			//alerta a ziro de nuevo usuario creado
			$this->loadModel("ShopCommerce");
			$commerce = $this->ShopCommerce->find("first", ["conditions" => ["code" => trim($this->request->data["Customer"]["code"]), "ShopCommerce.state" => 1]]);
			//data a la vista
			$customerInfo =  [
				"customerNombre" => $this->request->data["Customer"]["name"] . ' ' . $this->request->data["Customer"]["last_name"],
				"customerIdentificacion" => $this->request->data["Customer"]["identification"],
				"customerEmail" => $this->request->data["Customer"]["email"],
				"customerTelefono" => $this->request->data["CustomersPhone"][1]["phone_number"],
				"customerUserId" => $this->request->data["Customer"]["user_id_commerce"],
				"proveedor"  => $commerce['Shop']['social_reason'],
				"commerce"  => $commerce['ShopCommerce']['name'],
			];

			//correos a notificar
			$correos = [
				'john@somosziro.com',
				'juancacreativo@somosziro.com',
				'efi@somosziro.com',
				'yordy@somosziro.com',
				'laurens@somosziro.com',
				'monica@somosziro.com',
				'victoria@somosziro.com',
			];

			//opciones para enviar el correo
			$options = [
				"subject"   => "Alerta nuevo cliente registrado en Ziro",
				"to"        => $correos,
				"vars"      => $customerInfo,
				"template"  => "new_customer",
			];

			//enviar email a equipo ziro de nuevo cliente
			$this->sendMail($options);

			//enviar correo de bienvenida al cliente
			$infoWelcome = [
				"customerName" => $this->request->data["Customer"]["name"] . ' ' . $this->request->data["Customer"]["last_name"],
			];

			$optionsWelcome = [
				"subject"   => "Bienvenido a la familia de Ziro",
				"to"        => $this->request->data["Customer"]["email"],
				"vars"      => $infoWelcome,
				"template"  => "welcome_customer",
			];
			$this->sendMail($optionsWelcome);
			return "dashboardcliente";
			$this->redirect(["controller" => "pages", "action" => "dashboardcliente"]);
		}


		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
			unset($customer["Customer"]["code"]);
			$datosLimpios = $customer;
			$this->set("customer", $customer);
		}

		$this->loadModel("CreditsLine");
		$this->CreditsLine->recursive = -1;
		$creditLine = $this->CreditsLine->findByState(1);
		$creditLineId = $creditLine["CreditsLine"]["id"];

		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);

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
		}

		$this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth", "data", "codigo"));
	}


	public function validarDatosRegistro($data)
	{
		$alerta = false;
		/////////priceValue//////////
		// $priceValue=filter_var($priceValue, FILTER_SANITIZE_NUMBER_INT);
		$priceValue = $this->prepararParaSql($data['priceValue']);
		$data['priceValue'] = $priceValue;

		if (empty($data['priceValue'])) {
			$alerta = true;
		}


		/////////Customer password//////////
		// $password=filter_var($password, FILTER_SANITIZE_NUMBER_INT);
		$password = $this->prepararParaSql($data['Customer']['password']);
		$data['Customer']['password'] = $password;

		if (empty($password)) {
			$alerta = true;
		}

		/////////Customer code//////////
		$code = $this->prepararParaSql($data['Customer']['code']);
		$data['Customer']['code'] = $code;

		if (empty($code)) {
			$alerta = true;
		}

		/////////Customer name//////////
		$name = $this->prepararParaSql($data['Customer']['name']);
		$data['Customer']['name'] = $name;
		if (empty($name)) {
			$alerta = true;
		}

		/////////Customer last_name
		$last_name = $this->prepararParaSql($data['Customer']['last_name']);
		$data['Customer']['last_name'] = $last_name;
		if (empty($last_name)) {
			$alerta = true;
		}

		/////////Customer identification_type//////////
		//valor original
		$identification_type = $this->prepararParaSql($data['Customer']['identification_type']);
		$data['Customer']['identification_type'] = $identification_type;
		if (empty($identification_type)) {
			$alerta = true;
		}

		/////////Customer identification//////////
		// $identification=filter_var($identification, FILTER_SANITIZE_NUMBER_INT);
		$identification = $this->prepararParaSql($data['Customer']['identification']);
		$data['Customer']['identification'] = $identification;
		if (empty($identification)) {
			$alerta = true;
		}

		/////////Customer email//////////
		$email = $this->prepararParaSql($data['Customer']['email']);
		$data['Customer']['email'] = $email;
		if (empty($email)) {
			$alerta = true;
		}
		/////////Customer nit//////////
		$nit = $this->prepararParaSql($data['Customer']['nit']);
		$data['Customer']['nit'] = $nit;
		if (empty($nit)) {
			$alerta = true;
		}


		/////////Customer buss_name//////////
		$buss_name = $this->prepararParaSql($data['Customer']['buss_name']);
		$data['Customer']['buss_name'] = $buss_name;
		if (empty($buss_name)) {
			$alerta = true;
		}

		/////////Customer cci//////////
		// $cci=filter_var($cci, FILTER_UNSAFE_RAW);
		$cci = $this->prepararParaSql($data['Customer']['cci']);
		$data['Customer']['cci'] = $cci;
		if (empty($cci)) {
			$alerta = true;
		}

		/////////Customer politics//////////
		// $politics=filter_var($politics, FILTER_UNSAFE_RAW);
		$nit = $this->prepararParaSql($data['Customer']['politics']);
		$data['Customer']['politics'] = $nit;
		if (empty($politics)) {
			$alerta = true;
		}

		/////////CustomersPhone phone_number//////////
		$phone_number = $this->prepararParaSql($data['CustomersPhone'][1]['phone_number']);
		$data['CustomersPhone'][1]['phone_number'] = $phone_number;
		if (empty($phone_number)) {
			$alerta = true;
		}

		/////////CustomersPhone phone_type//////////
		$phone_type = $this->prepararParaSql($data['CustomersPhone'][1]['phone_type']);
		$data['CustomersPhone'][1]['phone_type'] = $phone_type;
		if (empty($phone_type)) {
			$alerta = true;
		}

		/////////CustomersAddress address//////////
		$address = $this->prepararParaSql($data['CustomersAddress']['address']);
		$data['CustomersAddress']['address'] = $address;
		if (empty($address)) {
			$alerta = true;
		}

		/////////CustomersAddress address_city//////////
		$address_city = $this->prepararParaSql($data['CustomersAddress']['address_city']);
		$data['CustomersAddress']['address_city'] = $address_city;
		if (empty($address_city)) {
			$alerta = true;
		}

		/////////CustomersAddress address_street//////////
		$address_street = $this->prepararParaSql($data['CustomersAddress']['address_street']);
		$data['CustomersAddress']['address_street'] = $address_street;
		if (empty($address_street)) {
			$alerta = true;
		}

		/////////CustomersAddress address_type//////////
		$address_type = $this->prepararParaSql($data['CustomersAddress']['address_type']);
		$data['CustomersAddress']['address_type'] = $address_type;
		if (empty($address_type)) {
			$alerta = true;
		}

		return [
			'status' => $alerta,
			'data' => $data
		];
	}

	public function datosObligatorios($data) {
		if (strlen($data["name"])>25 ||  empty($data["name"])) {
			return true;
		}

		if (strlen($data["last_name"])>25 ||  empty($data["last_name"])) {
			return true;
		}

		if (is_null($data["email"]) || empty($data["email"])) {
			return true;
		}

		if (is_null($data["password"]) || empty($data["password"])) {
			return true;
		}

		return false;
	}

	public function register_step_one()
	{
		$this->layout = "layout-home";

		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
		}

		if ($this->request->is("ajax") && ($this->request->is("post") || $this->request->is("put"))) {
			$this->autoRender = false;

			if (empty($customer)) {
				$this->Customer->create();
				$existCustomer = $this->Customer->find("first", ["conditions" => ["identification" => trim($this->request->data["Customer"]["identification"]), "type" => 1], "recursive" => -1]);
				$emailExists = $this->Customer->User->field("email", ["email" => $this->request->data["Customer"]["email"]]);

				if ($emailExists != false || !empty($existCustomer)) {
					return "El correo eléctronico o la cédula ya están registradas";
				} elseif (!empty($existCustomer)) {
					return "La cédula ya está registrada";
				}
			}

			$this->loadModel("ShopCommerce");
			$existsCommerce = $this->ShopCommerce->field("id", ["code" => $this->request->data["Customer"]["code"], "state" => 1]);

			if (!$existsCommerce) {
				return "El código de proveedor no existe";
			} else {
				if (!empty($customer)) {
					$this->loadModel("CreditsRequest");
					$actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState(AuthComponent::user("customer_id"), $existsCommerce, [0, 1, 2]);

					if (!empty($actualStudy)) {
						return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo";
					}
				}
			}
			$this->request->data["Customer"]["type"] = 1;
			if ($this->Customer->save($this->request->data)) {
				$customer_id  = $this->Customer->id;
				$this->Session->write("CODE_COMMERCE", $this->request->data["Customer"]["code"]);
				if (empty($customer)) {
					$registerUser = $this->create_and_login_user($this->request->data, $customer_id);
				} else {
					$registerUser = true;
					$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 2]]);
					$this->overwrite_session_user(AuthComponent::user('id'));
				}
				if ($registerUser) {
					$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
					return "register_step_two";
				} else {
					$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
					return "Error al guardar la información, vuelva a intentarlo.";
				}
			}
		}

		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
			unset($customer["Customer"]["code"]);
			$this->request->data = $customer;
			$this->set("customer", $customer);
		}
	}

	private function create_and_login_user($data, $customer_id)
	{

		$dataUser    = array("User" => [
			"email" => $data["Customer"]["email"],
			"name"  => $data["Customer"]["identification"],
			"password"     => $data["Customer"]["password"],
			"customer_id"  => $customer_id,
			"role"         => 5
		]);

		$this->Customer->User->create();

		if ($this->Customer->User->save($dataUser)) {
			$user_id = $this->Customer->User->id;
			$this->Customer->User->save(["User" => ["id" => $user_id, "customer_new_request" => 2]]);
			$this->Customer->User->recursive = -1;
			$user    = $this->Customer->User->findById($user_id);

			$customer["Customer"]["id"]              = $customer_id;
			$customer = $data["Customer"]["user_id"] = $user_id;
			$this->Customer->save($customer);

			if ($this->Auth->login($user["User"])) {
				return true;
			} else {
				return false;
			}
		}
	}
	public function register_step_two()
	{
		$this->layout = "layout-home";
		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
		}
		$this->validateCodeCommerce();

		if ($this->request->is("post")  || $this->request->is("put")) {
			$this->request->data["Customer"]["id"] = AuthComponent::user("customer_id");
			if ($this->Customer->save($this->request->data)) {
				if (empty($customer)) {
					$this->User->save(["User" => ["id" => AuthComponent::user("id"), "name" => $this->request->data["Customer"]["name"] . " " . $this->request->data["Customer"]["last_name"]]]);
					$this->overwrite_session_user(AuthComponent::user('id'));
				} else {
					if (isset($this->request->data["Customer"]["name"])) {
						$this->User->save(["User" => ["id" => AuthComponent::user("id"), "name" => $this->request->data["Customer"]["name"] . " " . $this->request->data["Customer"]["last_name"]]]);
					}
				}
				$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 3]]);
				$this->overwrite_session_user(AuthComponent::user('id'));
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'register_step_three', "controller" => "pages"));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
			$this->request->data = $customer;
			$this->set("customer", $customer);
		}
	}

	public function register_step_three()
	{
		$this->layout = "layout-home";
		$this->validateCodeCommerce();
		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
		}
		if ($this->request->is("post")) {
			$data = $this->request->data;

			if (!empty($data["CustomersReference"])) {
				foreach ($data["CustomersReference"] as $key => $value) {

					$this->Customer->CustomersReference->set($value);
					if ($this->Customer->CustomersReference->validates()) {
					} else {
						// didn't validate logic
						$this->Session->setFlash($this->Customer->CustomersReference->validationErrors);
					}

					$value["customer_id"] = AuthComponent::user("customer_id");
					if (!isset($value["id"])) {
						$this->Customer->CustomersReference->create();
					}
					$this->Customer->CustomersReference->save($value);
				}
			}

			if (!empty($data["CustomersAddress"])) {
				$data["CustomersAddress"]["customer_id"] = AuthComponent::user("customer_id");
				if (!isset($data["CustomersAddress"]["id"])) {
					$this->Customer->CustomersAddress->create();
				}
				$this->Customer->CustomersAddress->save($data["CustomersAddress"]);
			}

			if (!empty($data["CustomersPhone"])) {
				foreach ($data["CustomersPhone"] as $key => $value) {
					$value["customer_id"] = AuthComponent::user("customer_id");
					if (!empty($value["phone_number"])) {
						if (!isset($value["id"])) {
							$this->Customer->CustomersPhone->create();
						}
						$this->Customer->CustomersPhone->save($value);
					}
				}
			}

			$data["Customer"]["id"]         = AuthComponent::user("customer_id");
			$data["Customer"]["data_full"]  = 1;

			if ($this->Customer->save($data["Customer"])) {

				$this->User->save(["User" => ["id" => AuthComponent::user("id"), "customer_new_request" => 4]]);
				$this->overwrite_session_user(AuthComponent::user('id'));

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'register_step_four', "controller" => "pages"));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
			$this->Customer->recursive = 1;
			$customer = $this->Customer->findById(AuthComponent::user("customer_id"));
			$this->set("customer", $customer);
		}
	}

	public function creditos()
	{
	}

	public function calculate()
	{
		$this->autoRender = false;
		if ($this->request->is("ajax") || $this->request->is("post")) {
			$data_credit = $this->calculate_qoute(
				$this->request->data["couteValue"],
				$this->request->data["priceValue"],
				$this->request->data["frecuency"]
			);
			return number_format($data_credit["cuote"], 0, ".", ",");
		}
	}


	public function fastpayment()
	{
		$this->layout = "layout-fast";
	}

	public function commerce_payment()
	{
		$this->layout = "layout-fast";
	}

	public function politicas_uso_informacion()
	{
		$this->layout = "layout-home";
	}

	public function tyc()
	{
		$this->layout = "layout-home";
	}

	public function contrato()
	{
		$this->layout = "layout-home";
	}

	public function pagare()
	{
		$this->layout = "layout-home";
	}

	public function dashboardcliente()
	{
		if (AuthComponent::user("role") == 5) {
			$this->loadModel("Credit");
			$this->loadModel("ShopCommerce");
			$customer = $this->Credit->Customer->findById(AuthComponent::user("customer_id"));

			$this->ShopCommerce->unBindModel(["hasMany" => ["User", "CreditsRequest"]]);
			$commerces = $this->ShopCommerce->find("all", ["conditions" => ["ShopCommerce.state" => 1, "Shop.state" => 1]]);

			$list = [];

			if (!empty($commerces)) {
				foreach ($commerces as $key => $value) {
					$list[$value["ShopCommerce"]["id"]] = $value["ShopCommerce"]["code"] . " - " . $value["ShopCommerce"]["name"] . " | " . $value["Shop"]["social_reason"];
				}
			}

			$creditsCliente = [];

			if (!empty($customer)) {
				$this->Session->write("customer_id", $customer["Customer"]["id"]);
				$creditsCliente = $this->Credit->find("all", ["recursive" => -1, "conditions" => ["Credit.state" => 0, "Credit.customer_id" => $customer["Customer"]["id"], "Credit.credits_request_id != " => 0, "Credit.juridico" => 0]]);

				$total = 0;
				if (!empty($creditsCliente)) {
					$creditsCliente = $this->getSaldosByCredit($creditsCliente);
					foreach ($creditsCliente as $key => $value) {
						$total +=  $value["values"]["total"];
					}
				}

				$this->set("total", $total);
				$this->set("customer", $customer);
			}
			$this->set("list", $list);

			/******Datos Simulador ******/


			$this->loadModel("CreditsLine");
			$this->CreditsLine->recursive = -1;
			$creditLine = $this->CreditsLine->findByState(1);
			$creditLineId = $creditLine["CreditsLine"]["id"];

			$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $creditLineId);

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
			}

			//$sayHello = $valorMini;
			$this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth", "data"));


			/****** FinDatos Simulador ******/

			$this->loadModel("CreditsRequest");
			$conditions = [
				"customer_id" => AuthComponent::user("customer_id"),
				"state" => 4,
				"extra" => 1,
				"DATE(date_admin) >=" => date("Y-m-d", strtotime("-30 day")),
				"DATE(date_admin) <=" => date("Y-m-d"),
			];

			$actualNoTrue = $this->CreditsRequest->find("first", ["conditions" => $conditions, "recursive" => -1]);

			$conditions = [
				"customer_id" => AuthComponent::user("customer_id"),
				"state" => 4,
				"DATE(date_admin) >=" => date("Y-m-d", strtotime("-30 day")),
				"DATE(date_admin) <=" => date("Y-m-d"),
			];

			$actualNoTrueNormal = $this->CreditsRequest->find("first", ["conditions" => $conditions, "recursive" => -1]);

			$this->set("actualNoTrue", empty($actualNoTrue) ? false : true);
			$this->set("creditoNormal", $actualNoTrueNormal);
			$this->set("actualNoTrueNormal", empty($actualNoTrueNormal) ? false : true);
		} else {
			$this->redirect(["controller" => "credits_requests", "action" => "index"]);
		}
	}

	public function plan_payments()
	{
		$this->layout = false;
		if ($this->request->is("ajax")) {
			$data_credit = $this->calculate_qoute(
				$this->request->data["couteValue"],
				$this->request->data["priceValue"],
				$this->request->data["frecuency"]
			);
			$frecuency = $this->request->data["frecuency"];
			$priceValue = $this->request->data["priceValue"];
			$couteValue = $this->request->data["couteValue"];

			$this->set(compact("data_credit", "priceValue", "couteValue", "frecuency"));

			if (AuthComponent::user("id") && AuthComponent::user("role") == 5) {
				$totalActual = $this->totalQuote(true);
				$this->set("totalActual", $totalActual);
			}
		}
	}
	public function sendMessajeRegister($phone)
	{
		$this->layout = false;
		$data = [];
		$data['sms_settings']['cellvoz_account'] = '00486765881';
		$data['sms_settings']['cellvoz_api_key'] = '364a6fc7dd823121a24604b262f2d610bed025a7';
		$data['sms_settings']['cellvoz_password'] = 'Ziro1234*';
		$data['sms_body'] = 'Gracias por unirte a Ziro. Por favor verifica tu identidad: https://onx.la/f0d92';
		// $this->enviaSmsCellvoz($data, $phone, false);
		return $this->enviaSmsTwillio($data, $phone, false);
	}

	public function validarCodigoProveedor()
	{
		$this->autoRender = false;
		$this->loadModel("ShopCommerce");
		$code = $this->request->data['customerCode'];
		$totalCadenas = strlen($code);
		$code = filter_var($code, FILTER_SANITIZE_NUMBER_INT);
		$code = $this->prepararParaSql($code);

		if (empty($code)) {
			return 2;
		}
		$allCode = $this->ShopCommerce->find("count", ["conditions" => ["ShopCommerce.code" => $code, "ShopCommerce.state" => 1]]);
		if ($allCode == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	 * valida si la cedula del cliente existe en la bd
	 */
	public function validarCedulaCliente()
	{
		$this->autoRender = false;
		$this->loadModel("Customers");
		$code = $this->request->data['identification'];
		$totalCadenas = strlen($code);
		// $code=filter_var($code, FILTER_SANITIZE_NUMBER_INT);
		$code = $this->prepararParaSql($code);

		if (empty($code)) {
			return 2;
		}
		$allCode = $this->Customers->find("count", ["conditions" => ["Customers.identification" => $code]]);
		if ($allCode == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	 * valida si el correo del cliente existe en la bd
	 */
	public function validarCorreoUsuario()
	{
		$this->autoRender = false;
		$this->loadModel("User");
		$code = $this->request->data['email'];
		// $code=filter_var($code, FILTER_SANITIZE_EMAIL);
		$code = $this->prepararParaSql($code);

		if (empty($code)) {
			return 2;
		}
		$allCode = $this->User->find("count", ["conditions" => ["User.email" => $code]]);
		if ($allCode == 0) {
			return 0;
		} else {
			return 1;
		}
	}


	/**
	 * valida si el correo existe en la tabla customer bd
	 */
	public function validarCorreoCliente()
	{
		$this->autoRender = false;
		$this->loadModel("Customer");
		$code = $this->request->data['email'];
		// $code=filter_var($code, FILTER_SANITIZE_EMAIL);
		$code = $this->prepararParaSql($code);

		if (empty($code)) {
			return 2;
		}
		$allCode = $this->Customer->find("count", ["conditions" => ["Customer.email" => $code]]);
		if ($allCode == 0) {
			return 0;
		} else {
			return 1;
		}
	}

	/**
	 * REGISTRO CON METAMAP PRIMER SERVICIO OAUTH
	*/

	 public function registroMetamap(){
		$this->autoRender = false;


		// el autorizacion sale de Use your client_id y client_secret as your username and password to get your access token.

		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.getmati.com/oauth",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "grant_type=client_credentials",
			CURLOPT_HTTPHEADER => [
				"Content-Type: application/x-www-form-urlencoded",
				"accept: application/json",
				"authorization: Basic NjMwZmM0ZDQ3NzM0ZjYwMDFjOTY5NzdiOkxWVUNPRDY3UUNESFVNNEFQVlU3QVE1Nk1PWklYQ1hM"
			],
		]);

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {


			return $this->registroMetamapVerification($response);

		}



	}

	/**
	 * REGISTRO CON METAMAP SEGUNDO SERVICIO verificacion
	*/
	public function registroMetamapVerification($response){
		// respuesta correcta oauth
		$curl = curl_init();

		$respuesta_final_autentificacion = json_decode($response, true);
		$token_autentificacion = $respuesta_final_autentificacion['access_token'];

		curl_setopt_array($curl, [
			CURLOPT_URL => "https://api.getmati.com/v2/verifications/",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => "{\"metadata\":{\"user-defined-1\":\"abcde\",\"user-defined-2\":\"12345\"}}",
			CURLOPT_HTTPHEADER => [
				"accept: application/json",
				"authorization: Bearer " . $token_autentificacion,
				"content-type: application/json"
			],
		]);

		$responseVerification = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {

			$respuestaFinalVerification = json_decode($responseVerification, true);
			$identity = $respuestaFinalVerification['identity'];

			return $this->registroMetamapVerification($identity, $token_autentificacion);
		}

	}

	/**
	 * REGISTRO CON METAMAP TERCER SERVICIO SEND-INPUT
	*/
	public function registroMetamapSendInput($identity, $token_autentificacion,$image){

		$curl1 = curl_init();
    $upload_url = 'https://raw.githubusercontent.com/cuatl/Utils/master/enviarArchivoCurlPHP/test.jpg';

		// existe un problema cuando se usa curfile
		curl_setopt_array($curl1, array(
			CURLOPT_URL => 'https://api.getmati.com/v2/identities/63a3882426e72f001c2a90b8/send-input',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SAFE_UPLOAD => false,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => array('inputs' => '[
				{"inputType":"document-photo","group":0,
						"data":{
							"type":"national-id",
							"country":"CO",
							"region":"",
							"page":"front",
							"filename":"frontce1.jpeg"
							}
						},
				{"inputType":"document-photo","group":0,
						"data":{
							"type":"national-id",
							"country":"CO",
							"region":"",
							"page":"back",
							"filename":"backce1.jpeg"
							}
						} ,
			{
				"inputType": "selfie-photo",
				"data": {
					"type": "selfie-photo",
					"filename": "fotoperfil1.jpeg"
				}
			}
				]',
				'document'=> new CURLFile($upload_url),
				'document'=> new CURLFile($upload_url),
				'selfie'=> new CURLFile($upload_url),
			),
				CURLOPT_HTTPHEADER => [
					"accept: application/json",
					"authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJjbGllbnQiOnsiX2lkIjoiNjMwZmM0ZDQ3NzM0ZjYwMDFjOTY5NzdiIiwibWVyY2hhbnQiOnsiX2lkIjoiNjMwZmM0ZDQ3NzM0ZjYwMDFjOTY5NzZmIiwib3duZXIiOiI2MzBmYzRkNDc3MzRmNjAwMWM5Njk3NmMiLCJzdWJzY3JpcHRpb25TdGF0dXMiOnsidmFsdWUiOiJhY3RpdmUiLCJ1cGRhdGVkQXQiOiIyMDIyLTA4LTMxVDIwOjMwOjEyLjQyMloifX19LCJ1c2VyIjp7Il9pZCI6IjYzMGZjNGQ0NzczNGY2MDAxYzk2OTc2YyJ9LCJzY29wZSI6InZlcmlmaWNhdGlvbl9mbG93IGlkZW50aXR5OnJlYWQgdmVyaWZpY2F0aW9uOnJlYWQiLCJpYXQiOjE2NzE2NjU3MDIsImV4cCI6MTY3MTY2OTMwMiwiaXNzIjoib2F1dGgyLXNlcnZlciJ9.tK9kOYYpt2sJTsaZRaMLe3XiDBtiM5jtFuo3uqjl_AY"
				],
		));

		$responseSendInput = curl_exec($curl1);

		$respuestaFinalVerification = json_decode($responseSendInput, true);

		debug($responseSendInput);
		die();

		$err = curl_error($curl1);
		debug($err);
		die();

		curl_close($curl1);

		if ($err) {
			echo "cURL Error #:" . $err;
		} else {



		}
		return $responseSendInput;
	}



	public function sendMailComoPagarClientes()
	{
		$this->autoRender = false;

		$this->loadModel("Credit");

		$creditos = $this->Credit->find("all", ["conditions" => ["Credit.state" => 0, "Credit.deadline" =>  [
			'2022-11-17',
			'2022-11-18',
			'2022-11-19',
			'2022-11-20',
			'2022-11-21',
			'2022-11-22',
		]]]);
		$emails = [];

		foreach ($creditos as  $credito) {

			$nameCliente = $credito['Customer']['name'] . ' ' . $credito['Customer']['last_name'];

			$this->loadModel("User");
			$emailSearch = $this->User->find("first",  ["fields" => ["email"], "recursive" => -1, "conditions" => ["User.customer_id" => $credito['Customer']['id']]]);
			$email = $emailSearch['User']['email'];
			$telefono = $credito['Customer']['celular'];
			$cedula = $credito['Customer']['identification'];

			array_push($emails, $cedula . '---' . $nameCliente . ' ----- ' . $email . '-----' . $telefono);

			$options = [
				"subject" 	=> "¿Cómo hacer mi pago exitoso?",
				"to"   		=> [$email, 'laurens@somosziro.com', 'juancacreativo@somosziro.com'],
				"vars" 	    => array(
					'nameCliente' => $nameCliente,
					"code_pay" => $credito['Credit']['code_pay'],
					"quota_value" => $credito['Credit']['quota_value'],
					"deadline" => $credito['Credit']['deadline'],
				),
				"template"	=> "como_pagar",
			];

			$this->sendMail($options);
		}

		pr($emails);
		die();
	}
	public function smsPago15Dias() {
		$data = [];
		$data['sms_body'] = 'Estar al día con tu crédito Ziro es súper fácil, tan fácil como fue pedirlo. Puedes pagar en línea o en uno de los miles de corresponsales en todo el país. Ingresa a este link https://bit.ly/3VIG84v y entérate de los medios de pago que tenemos para ti
		';
		$this->autoRender = false;

		$this->loadModel("CreditsLine");
		$creditosToPay = $this->CreditsLine->query("SELECT  DATEDIFF(credits_plans.deadline,CURDATE()) as dias_pago, credits_plans.deadline, credits_plans.id,
		credits_plans.capital_value, credits_plans.credit_id,
		credits_plans.state,credits.id, credits.code_pay, credits.quota_value,
		credits.customer_id, customers.id, customers.name, customers.email,
		customers.identification,
		customers_phones.id, customers_phones.customer_id, customers_phones.phone_number
		FROM credits_plans
		INNER JOIN credits ON credits.id = credits_plans.credit_id
		INNER JOIN customers ON customers.id = credits.customer_id
		INNER JOIN customers_phones ON customers.id = customers_phones.customer_id
		WHERE   credits_plans.deadline >= CURDATE()
		AND DATEDIFF(credits_plans.deadline, CURDATE()) =15
		AND credits_plans.state=0");

		//clientes que faltan 15 dias por pagar
		foreach($creditosToPay as $value) {
			// $phone=$value['customers_phones']['phone_number'];
			$phone='3023439045';
			// $this->enviaSmsCellvoz($data, $phone, false);
			// $this->enviaSmsTwillio($data, $phone, false);
		}

		//correos a notificar
		$correos = [
			// 'juancacreativo@somosziro.com',
			'laurens@somosziro.com',
		];


		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje 15 dias enviado a los clientes",
			"to"        => $correos,
			"vars"      => ['creditosToPay' => $creditosToPay],
			"template"  => "sms_15_dias",
		];

		//enviar email a equipo ziro de nuevo cliente
		$this->sendMail($options);
		debug('Fin');
		die();
	}

	public function smsPago5Dias() {
		$data = [];

		$this->autoRender = false;

		$this->loadModel("CreditsLine");
		$arrayCustomers=[];
		$creditosToPay = $this->CreditsLine->query("SELECT  DATEDIFF(credits_plans.deadline,CURDATE()) as dias_pago, credits_plans.deadline, credits_plans.id,
		credits_plans.capital_value, credits_plans.credit_id,
		credits_plans.state,credits.id, credits.code_pay, credits.quota_value,
		credits.customer_id, customers.id, customers.name, customers.email,
		customers.identification,
		customers_phones.id, customers_phones.customer_id, customers_phones.phone_number
		FROM credits_plans
		INNER JOIN credits ON credits.id = credits_plans.credit_id
		INNER JOIN customers ON customers.id = credits.customer_id
		INNER JOIN customers_phones ON customers.id = customers_phones.customer_id
		WHERE   credits_plans.deadline >= CURDATE()
		AND DATEDIFF(credits_plans.deadline, CURDATE()) =5
		AND credits_plans.state=0");

		//clientes que faltan 15 dias por pagar
		foreach($creditosToPay as $value) {
			$customerId=$value['customers']['id'];
			$this->loadModel("Credits");
			$totalCreditos=$this->Credits->field("COUNT(id) total", ["customer_id" => $customerId]);

			if ($totalCreditos ==1) {
				array_push($arrayCustomers,$customerId);
				$data['sms_body'] = 'Hola, '.strtoupper($value['customers']['name']).' tu crédito Zíro está próximo a vencer, tienes un pago pendiente de tu factura por un valor de $'.number_format($value['credits_plans']['capital_value']).', tu fecha límite es el '.$value['credits_plans']['deadline'] .'. Ingresa a este link https://bit.ly/3VIG84v y entérate de los medios de pago que tenemos para ti. Si necesitas más información, por favor no dudes en ponerte en contacto con nosotros. Apreciamos mucho tu apoyo y tu compromiso.';
				// $phone=$value['customers_phones']['phone_number'];
				$phone='3023439045';
				// $this->enviaSmsCellvoz($data, $phone, false);
				// $this->enviaSmsTwillio($data, $phone, false);
			}

		}

		//correos a notificar
		$correos = [
			'juancacreativo@somosziro.com',
			'laurens@somosziro.com',
		];

		//opciones para enviar el correo
		$options = [
			"subject"   => "Mensaje 15 días enviado a los clientes",
			"to"        => $correos,
			"vars"      => [
				'creditosToPay' => $creditosToPay,
				'dias'          => 15,
				'arrayCustomers' => $arrayCustomers
			],
			"template"  => "sms_recordatorio_pago",
		];


		$this->sendMail($options);
		debug($arrayCustomers);
		die();
	}

	public function smsPago2Dias() {
		$data = [];

		$this->autoRender = false;

		$this->loadModel("CreditsLine");
		$creditosToPay = $this->CreditsLine->query("SELECT  DATEDIFF(credits_plans.deadline,CURDATE()) as dias_pago, credits_plans.deadline, credits_plans.id,
		credits_plans.capital_value, credits_plans.credit_id,
		credits_plans.state,credits.id, credits.code_pay, credits.quota_value,
		credits.customer_id, customers.id, customers.name, customers.email,
		customers.identification,
		customers_phones.id, customers_phones.customer_id, customers_phones.phone_number
		FROM credits_plans
		INNER JOIN credits ON credits.id = credits_plans.credit_id
		INNER JOIN customers ON customers.id = credits.customer_id
		INNER JOIN customers_phones ON customers.id = customers_phones.customer_id
		WHERE   credits_plans.deadline >= CURDATE()
		AND DATEDIFF(credits_plans.deadline, CURDATE()) =2
		AND credits_plans.state=0");

		//clientes que faltan 15 dias por pagar
		foreach($creditosToPay as $value) {
			$data['sms_body'] = 'Hola, '.strtoupper($value['customers']['name']).' tu crédito Zíro está próximo a vencer, tienes un pago pendiente de tu factura por un valor de $'.number_format($value['credits_plans']['capital_value']).', tu fecha límite es el '.$value['credits_plans']['deadline'] .'. Ingresa a este link https://bit.ly/3VIG84v y entérate de los medios de pago que tenemos para ti. Si necesitas más información, por favor no dudes en ponerte en contacto con nosotros. Apreciamos mucho tu apoyo y tu compromiso.';
			// $phone=$value['customers_phones']['phone_number'];
			$phone='3023439045';
			$this->enviaSmsCellvoz($data, $phone, false);
			// $this->enviaSmsTwillio($data, $phone, false);
		}
		debug('Terminado');
		die();
	}

	public function customersVerified() {
		$this->autoRender = false;
		$this->loadModel("customersVerified");
		$data = ["customersVerified" => [
			"identification" 	=> $this->request->data["identification"],
			"name" 				=> $this->request->data["name"],
			"last_name" 		=> $this->request->data["last_name"],
			"score" 			=> $this->request->data["score"],
		]];
		$this->customersVerified->create();
		if ($this->customersVerified->save($data)) {
			return 1;
		} else {
			return 2;
		}
	}

	function actualizarDisbursements() {
		// Conectarse a la base de datos
		$this->loadModel('Disbursement');
		$this->loadModel('Credit');
		$fecha = $this->request->query["fecha"];
		$this->autoRender = false;
		// Consulta para obtener los registros de disbursements
		$disbursements = $this->Disbursement->find('all', array(
			'conditions' => array('Disbursement.created >=' => $fecha)
		));

		// Recorrer los resultados de la consulta
		foreach ($disbursements as $disbursement) {
			// Buscar el crédito correspondiente en la tabla credits
			$credit = $this->Credit->find('first', array(
				'conditions' => array('Credit.id' => $disbursement['Disbursement']['credit_id'])
			));

			// Si se encuentra un crédito, actualizar el valor de disbursement
			if ($credit) {
				$disbursement['Disbursement']['value'] = $credit['Credit']['value_request'];
				$this->Disbursement->save($disbursement);
			}
		}

		return 'operación terminada';
	}



}
