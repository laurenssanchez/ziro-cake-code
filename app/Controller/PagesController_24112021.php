<?php


App::uses('AppController', 'Controller');

class PagesController extends AppController {

	public $uses = array("Customer","User");

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow( 'home', 'register_step_one', 'register_step_two2', 'register_step_three', 'creditos','calculate','plan_payments','connect','fastpayment','politicas_uso_informacion','tyc','pagare','contrato','normal_request','commerce_payment','crediventas','generate_codes','validate_codes_crediventas','dashboardcliente');
	}


	public function display() {

	}

  public function generate_codes($phone = null, $email = null){
    $this->response->header('Access-Control-Allow-Origin', '*');
    $this->autoRender = false;
    if (!isset($this->request->data["email"]) || !isset($this->request->data["phone"]) || empty($this->request->data["phone"]) || empty($this->request->data["email"]) ) {
       return 2;
    }

    $sesion_id = $this->Session->read("SESSION_ID_CUS");

    if (is_null($sesion_id)) {
      $sesion_id = uniqid();
      $this->Session->write("SESSION_ID_CUS",$sesion_id);
    }



    $email = $this->request->data["email"];
    $phone = $this->request->data["phone"];

    $codes = $this->getCodesCustomer(null,null, $sesion_id, $email, $phone);
    $this->Session->write("SESSION_CODES",$codes);

    return 1;
  }

  public function validate_codes_crediventas(){
    $this->autoRender = false;
    $this->loadModel("CustomersCode");
    $sesion_id = $this->Session->read("SESSION_ID_CUS");

    if ($sesion_id == null) {
          return "Por favor recargue la página ya que se ha perdido la sesión";
    }else{
        $validTimeEmail = $this->CustomersCode->findByCodeAndSesIdAndTypeCodeAndState($this->request->data["email"], $sesion_id,1,0);

        $validTimePhone = $this->CustomersCode->findByCodeAndSesIdAndTypeCodeAndState($this->request->data["phone"], $sesion_id,2,0);

        if(empty($validTimeEmail) || empty($validTimePhone)){
          return __('Error, uno o los dos códigos expiraron su vigencia, revisa los códigos que fueron enviados nuevamente.');
        }else{
          $validTimeEmail["CustomersCode"]["state"] = 1;
          $validTimePhone["CustomersCode"]["state"] = 1;

          $this->CustomersCode->save($validTimeEmail);
          $this->CustomersCode->save($validTimePhone);
          return 1;
        }
    }
  }

  public function crediventas() {
    $this->layout = "layout-home";

    $this->Session->delete("SESSION_ID_CUS");
    $this->Session->delete("SESSION_CODES");

    if ($this->request->is("post")) {
      $this->autoRender = false;
      $this->loadModel("ShopCommerce");
      $this->loadModel("Customer");
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
                "request_type" => $this->request->data["frecuency"],
              ]
            ];
            $this->CreditsRequest->create();
            if ($this->CreditsRequest->save($dataRequest)) {
              $this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
              return 1;
            }
          }else{
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
      $this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth","data"));
  }


  public function dashboard() {
    if(!isset($this->request->query["dateIni"]) || !isset($this->request->query["dateEnd"])){
      $this->redirect(["controller" => "pages", "action" => "dashboard","?"=>["dateIni" => date("Y-m-d",strtotime("-6 month")),"dateEnd" => date("Y-m-d") ]]);
    }else{
      $this->loadModel("Credit");
      $this->loadModel("CreditsRequest");
      $this->loadModel("ShopPaymentRequest");
      $this->loadModel("Payment");

      $dateIni = $this->request->query["dateIni"];
      $dateEnd = $this->request->query["dateEnd"];

      $values = $colors = $months = [];

      $days30 = $days60 = $days90 = 0;

      $days30Total = $days60Total = $days90Total = 0;

      $totalNoApprove = $this->CreditsRequest->field("SUM(request_value) total",["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $dateEnd, "state" => 4]);
      $CounttotalNoApprove = $this->CreditsRequest->field("COUNT(id) total",["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $dateEnd, "state" => 4]);

      $totalNoDisburment = $this->CreditsRequest->field("SUM(request_value) total",["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $dateEnd, "state" => 3]);
      $CounttotalNoDisburment = $this->CreditsRequest->field("COUNT(id) total",["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $dateEnd, "state" => 3]);

      $totalPaymentCredit = $this->Credit->field("SUM(value_aprooved) total",["DATE(created) >= " => $dateIni, "DATE(created) <= " => $dateEnd, "Credit.credits_request_id != " => 0, "Credit.state" => 1]);
      $CounttotalPaymentCredit = $this->Credit->field("COUNT(id) total",["DATE(created) >= " => $dateIni, "DATE(created) <= " => $dateEnd, "Credit.credits_request_id != " => 0, "Credit.state" => 1]);

      $totalDisburment = $this->Credit->field("SUM(value_request) as total",["DATE(Credit.created) >=" => $dateIni, "DATE(Credit.created) <=" => $dateEnd, "Credit.credits_request_id != " => 0 ]);
      $CounttotalDisburment = $this->Credit->field("COUNT(id) total",["DATE(Credit.created) >= " => $dateIni, "DATE(Credit.created) <= " => $dateEnd, "Credit.credits_request_id != " => 0 ]);

      $totalApprove = $this->CreditsRequest->field("SUM(value_approve) total",["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $dateEnd, "state" => [3,5,7] ]);
      $CounttotalApprove = $this->CreditsRequest->field("COUNT(id) total",["DATE(date_admin) >= " => $dateIni, "DATE(date_admin) <= " => $dateEnd, "state" => [3,5,7] ]);

      $totalNoShop = $this->ShopPaymentRequest->field("SUM(request_value) total",["DATE(request_date) >= " => $dateIni, "DATE(request_date) <= " => $dateEnd, "state" => 0 ]);
      $CounttotalNoShop = $this->ShopPaymentRequest->field("COUNT(id) total",["DATE(request_date) >= " => $dateIni, "DATE(request_date) <= " => $dateEnd, "state" => 0 ]);

      $totalNoCommerce = $this->Payment->field("SUM(value) total",["DATE(created) >= " => $dateIni, "DATE(created) <= " => $dateEnd, "state_credishop" => 0 ]);
      $CounttotalNoCommerce2 = $this->Payment->find("all", ["conditions" => ["DATE(created) >= " => $dateIni, "DATE(created) <= " => $dateEnd, "state_credishop" => 0 ],"recursive" => -1 ] );

      $CounttotalNoCommerce = [];
      if(!empty($CounttotalNoCommerce2)){
        foreach ($CounttotalNoCommerce2 as $key => $value) {
          if(!in_array($value["Payment"]["receipt_id"], $CounttotalNoCommerce)){
            $CounttotalNoCommerce[] = $value["Payment"]["receipt_id"];
          }
        }
        $CounttotalNoCommerce = count($CounttotalNoCommerce);
      }

      $totalByMonth = $this->CreditsRequest->find("all",["fields" =>  [ "SUM(value_disbursed) as total","MONTH(date_admin) as mes"], "group" => ["mes" ], "conditions" => ["DATE(date_disbursed) >= " => $dateIni, "DATE(date_disbursed) <= " => $dateEnd, "state" => [5,7] ], "recursive" => -1 ]);

      $totalMonths = 0;

      if (!empty($totalByMonth)) {

        foreach ($totalByMonth as $key => $value) {
          $totalMonths+= floatval($value["0"]["total"]);
          $values[] =  floatval($value["0"]["total"]);
          $colors[] =  Configure::read("COLORS.".$value["0"]["mes"]);
          $months[] =  Configure::read("MONTHS.".$value["0"]["mes"]);
        }
      }

      $cuotesValues = $this->Credit->CreditsPlan->getQuotesCobranzasTotal();

      $credits30 = [];
      $credits60 = [];
      $credits90 = [];

      if (!empty($cuotesValues)) {
        foreach ($cuotesValues as $key => $value) {
          if($value["0"]["dias"] <= 30 && !in_array($value["CreditsPlan"]["credit_id"], $credits30)){
            $days30++;
            $days30Total+= ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
            $credits30[] = $value["CreditsPlan"]["credit_id"];
            // $days30Total+= ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]) + ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
          }elseif( ( $value["0"]["dias"] <= 60 && in_array($value["CreditsPlan"]["credit_id"], $credits30) && !in_array($value["CreditsPlan"]["credit_id"], $credits60)) || ($value["0"]["dias"] <= 60 && !in_array($value["CreditsPlan"]["credit_id"], $credits60) ) ){
            $days60++;
            $days60Total+= ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
            $credits30[] = $value["CreditsPlan"]["credit_id"];
            $credits60[] = $value["CreditsPlan"]["credit_id"];
            // $days60Total+= ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]) + ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
          }else{
            if (in_array($value["CreditsPlan"]["credit_id"], $credits30)) {
              continue;
            }
            $days90++;
            $days90Total+= ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
            $credits30[] = $value["CreditsPlan"]["credit_id"];
            // $days90Total+= ($value["CreditsPlan"]["capital_value"] - $value["CreditsPlan"]["capital_payment"]) + ( $value["CreditsPlan"]["interest_value"] - $value["CreditsPlan"]["interest_payment"] ) + ( $value["CreditsPlan"]["others_value"] - $value["CreditsPlan"]["others_payment"] ) + $value["CreditsPlan"]["debt_value"] + $value["CreditsPlan"]["debt_honor"];
          }
        }
      }


      $this->set("totalNoApprove",$totalNoApprove);
      $this->set("CounttotalNoApprove",$CounttotalNoApprove);

      $this->set("totalNoDisburment",$totalNoDisburment);
      $this->set("CounttotalNoDisburment",$CounttotalNoDisburment);

      $this->set("totalPaymentCredit",$totalPaymentCredit);
      $this->set("CounttotalPaymentCredit",$CounttotalPaymentCredit);

      $this->set("totalDisburment",$totalDisburment);
      $this->set("CounttotalDisburment",$CounttotalDisburment);

      $this->set("totalApprove",$totalApprove);
      $this->set("CounttotalApprove",$CounttotalApprove);

      $this->set("totalNoShop",$totalNoShop);
      $this->set("CounttotalNoShop",$CounttotalNoShop);

      $this->set("totalNoCommerce",$totalNoCommerce);
      $this->set("CounttotalNoCommerce",$CounttotalNoCommerce);

      $this->set("dateIni",$dateIni);
      $this->set("dateEnd",$dateEnd);

      $this->set("values",$values);
      $this->set("colors",$colors);
      $this->set("months",$months);

      $this->set("days30",$days30);
      $this->set("days60",$days60);
      $this->set("days90",$days90);

      $this->set("days30Total",$days30Total);
      $this->set("days60Total",$days60Total);
      $this->set("days90Total",$days90Total);

      $this->set("totalMora",count($cuotesValues));
      $this->set("totalMonths",$totalMonths);

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
     // $this->set("codeEmail", $codes["codeEmail"]);
     // $this->set("codePhone", $codes["codePhone"]);

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
      $this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth","data"));



  }


  public function created(){

    $this->loadModel("CreditsRequest");


    $existsCredit = $this->CreditsRequest->findAllByCustomerIdAndState(AuthComponent::user("customer_id"),[0,1]);
    $code         = $this->validateCodeCommerce();

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

  public function newRequest(){
    if(AuthComponent::user("id")){

        $step = AuthComponent::user("customer_new_request") == 6 ? 1 : AuthComponent::user("customer_new_request");
        switch ($step) {
          case '1':
            $action = "register_step_one";
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
        $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => $step]]);
        $this->overwrite_session_user(AuthComponent::user('id'));
        $this->redirect(["action"=>$action]);
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
              return "El código de proveedor no existe";
          } else {
              $customer = $this->Customer->find("first", ["conditions" => ["identification" => $this->request->data["Customer"]["identification"]], "recursive" => -1]);

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
      $this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth","data"));

  }

	public function register_step_one(){
		$this->layout = "layout-home";

    if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
      $customer = $this->Customer->findById(AuthComponent::user("customer_id"));
    }

    if($this->request->is("ajax") && ($this->request->is("post") || $this->request->is("put")) ){
      $this->autoRender = false;

      if(empty($customer)){
        $this->Customer->create();
        $existCustomer = $this->Customer->field("identification",["identification"=>$this->request->data["Customer"]["identification"]]);
        $emailExists = $this->Customer->User->field("email",["email"=>$this->request->data["Customer"]["email"]]);

        if($emailExists != false){
          return "El correo eléctronico ya está registrado";
        }elseif($existCustomer != false){
          return "La cédula ya está registrada";
        }
      }

      $this->loadModel("ShopCommerce");
      $existsCommerce = $this->ShopCommerce->field("id",["code" => $this->request->data["Customer"]["code"],"state" => 1]);

      if(!$existsCommerce){
          return "El código de proveedor no existe";
      }else{
        if(!empty($customer)){
          $this->loadModel("CreditsRequest");
          $actualStudy = $this->CreditsRequest->findByCustomerIdAndShopCommerceIdAndState(AuthComponent::user("customer_id"),$existsCommerce,[0,1,2]);

          if(!empty($actualStudy)){
            return "Existe una solicitud en proceso en esté mismo proveedor, no es posible tener dos al tiempo";
          }
        }
      }

      if($this->Customer->save($this->request->data)){
        $customer_id  = $this->Customer->id;
        $this->Session->write("CODE_COMMERCE",$this->request->data["Customer"]["code"]);
        if(empty($customer)){
          $registerUser = $this->create_and_login_user($this->request->data,$customer_id);
        }else{
          $registerUser = true;
          $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 2]]);
          $this->overwrite_session_user(AuthComponent::user('id'));
        }
        if($registerUser){
          $this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
          return "register_step_two";
        }else{
          $this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
          return "Error al guardar la información, vuelva a intentarlo.";
        }
      }
    }

    if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
      $customer = $this->Customer->findById(AuthComponent::user("customer_id"));
      unset($customer["Customer"]["code"]);
      $this->request->data = $customer;
      $this->set("customer",$customer);
    }

  }

  private function create_and_login_user($data,$customer_id){

    $dataUser    = array("User" => [
        "email" => $data["Customer"]["email"],
        "name"  => $data["Customer"]["identification"],
        "password"     => $data["Customer"]["password"],
        "customer_id"  => $customer_id,
        "role"         => 5
    ]);

    $this->Customer->User->create();

    if($this->Customer->User->save($dataUser)){
      $user_id = $this->Customer->User->id;
      $this->Customer->User->save(["User"=>["id" => $user_id,"customer_new_request" => 2]]);
      $this->Customer->User->recursive = -1;
      $user    = $this->Customer->User->findById($user_id);

      $customer["Customer"]["id"]              = $customer_id;
      $customer = $data["Customer"]["user_id"] = $user_id;
      $this->Customer->save($customer);

      if($this->Auth->login($user["User"])){
        return true;
      }else{
        return false;
      }

    }
  }
  public function register_step_two(){
		$this->layout = "layout-home";
    if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
      $customer = $this->Customer->findById(AuthComponent::user("customer_id"));
    }
    $this->validateCodeCommerce();

    if($this->request->is("post")  || $this->request->is("put") ){
      $this->request->data["Customer"]["id"] = AuthComponent::user("customer_id");
      if ($this->Customer->save($this->request->data)) {
        if (empty($customer)) {
          $this->User->save(["User"=>["id" => AuthComponent::user("id"),"name" => $this->request->data["Customer"]["name"]." ".$this->request->data["Customer"]["last_name"] ]]);
          $this->overwrite_session_user(AuthComponent::user('id'));
        }else{
          if(isset($this->request->data["Customer"]["name"])){
            $this->User->save(["User"=>["id" => AuthComponent::user("id"),"name" => $this->request->data["Customer"]["name"]." ".$this->request->data["Customer"]["last_name"] ]]);
          }
        }
        $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 3]]);
        $this->overwrite_session_user(AuthComponent::user('id'));
        $this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
        $this->redirect(array('action' => 'register_step_three',"controller"=>"pages"));
      } else {
        $this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
      }
    }
    if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
      $customer = $this->Customer->findById(AuthComponent::user("customer_id"));
      $this->request->data = $customer;
      $this->set("customer",$customer);
    }
   }

   public function register_step_three(){
		$this->layout = "layout-home";
    $this->validateCodeCommerce();
    if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
      $customer = $this->Customer->findById(AuthComponent::user("customer_id"));
    }
      if($this->request->is("post")){
        $data = $this->request->data;

        if(!empty($data["CustomersReference"])){
          foreach ($data["CustomersReference"] as $key => $value) {
            $value["customer_id"] = AuthComponent::user("customer_id");
            if (!isset($value["id"])) {
              $this->Customer->CustomersReference->create();
            }
            $this->Customer->CustomersReference->save($value);
          }
        }

        if(!empty($data["CustomersAddress"])){
          $data["CustomersAddress"]["customer_id"] = AuthComponent::user("customer_id");
          if (!isset($data["CustomersAddress"]["id"])) {
            $this->Customer->CustomersAddress->create();
          }
          $this->Customer->CustomersAddress->save($data["CustomersAddress"]);
        }

        if(!empty($data["CustomersPhone"])){
          foreach ($data["CustomersPhone"] as $key => $value) {
            $value["customer_id"] = AuthComponent::user("customer_id");
            if(!empty($value["phone_number"])){
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

          $this->User->save(["User"=>["id" => AuthComponent::user("id"),"customer_new_request" => 4]]);
          $this->overwrite_session_user(AuthComponent::user('id'));

          $this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
          $this->redirect(array('action' => 'register_step_four',"controller"=>"pages"));
        } else {
          $this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
        }
      }

    if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
      $this->Customer->recursive = 1;
      $customer = $this->Customer->findById(AuthComponent::user("customer_id"));
      $this->set("customer",$customer);
    }

   }

   public function creditos(){
   }

    public function calculate(){
    	$this->autoRender = false;
    	if($this->request->is("ajax")){
    		$data_credit = $this->calculate_qoute(
    			$this->request->data["couteValue"],
    			$this->request->data["priceValue"],
          $this->request->data["frecuency"]
    		);
			  return number_format($data_credit["cuote"], 0,".",",");
    	}
    }

  public function fastpayment(){
    $this->layout = "layout-fast";
  }

  public function commerce_payment() {
    $this->layout = "layout-fast";
  }

   public function politicas_uso_informacion(){
    $this->layout = "layout-home";
   }

  public function tyc(){
    $this->layout = "layout-home";
   }

  public function contrato(){
    $this->layout = "layout-home";
  }

  public function pagare(){
    $this->layout = "layout-home";
  }

	  public function dashboardcliente(){
    if (AuthComponent::user("role") == 5) {
      $this->loadModel("Credit");
      $this->loadModel("ShopCommerce");
      $customer = $this->Credit->Customer->findById(AuthComponent::user("customer_id"));

      $this->ShopCommerce->unBindModel(["hasMany"=>["User","CreditsRequest"]]);
      $commerces = $this->ShopCommerce->find("all",["conditions"=>["ShopCommerce.state"=>1,"Shop.state"=>1]]);

      $list = [];

      if (!empty($commerces)) {
        foreach ($commerces as $key => $value) {
          $list[$value["ShopCommerce"]["id"]] = $value["ShopCommerce"]["code"]." - ".$value["ShopCommerce"]["name"]." | ".$value["Shop"]["social_reason"];
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

          $this->set("total",$total);
          $this->set("customer",$customer);
      }
      $this->set("list",$list);

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
      $this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth","data"));


      /****** FinDatos Simulador ******/

    }else{
      $this->redirect(["controller"=>"credits_requests","action"=>"index"]);
    }

  }

    public function plan_payments(){
    	$this->layout = false;
    	if($this->request->is("ajax")){
    		$data_credit = $this->calculate_qoute(
    			$this->request->data["couteValue"],
          $this->request->data["priceValue"],
    			$this->request->data["frecuency"]
    		);
    		$frecuency = $this->request->data["frecuency"];
    		$priceValue = $this->request->data["priceValue"];
        $couteValue = $this->request->data["couteValue"];

    		$this->set(compact("data_credit","priceValue","couteValue","frecuency"));

        if(AuthComponent::user("id") && AuthComponent::user("role") == 5){
          $totalActual = $this->totalQuote(true);
          $this->set("totalActual",$totalActual);
        }
    	}
    }

}

