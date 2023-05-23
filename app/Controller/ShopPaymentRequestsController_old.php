<?php
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
			//$conditions["ShopPaymentRequest.state"] = 0;
			//$this->set("estados",$query["state"]);
		}

		if(isset($query["commerce"]) && $query["commerce"] != "" ){
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->set("commerce",$query["commerce"]);
		}else{
			$this->set("commerce","");
		}

		if(isset($query["request_date"]) && $query["request_date"] != "" ){
			$conditions["DATE(ShopPaymentRequest.request_date)"] = $query["request_date"];
			$this->set("request_date",$query["request_date"]);
		}else{
			$this->set("request_date","");
		}

		if(isset($query["final_date"]) && $query["final_date"] != "" ){
			$conditions["DATE(ShopPaymentRequest.final_date)"] = $query["final_date"];
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
			$this->Paginator->settings 			 = array('order'=>array('ShopPaymentRequest.modified'=>'DESC'));
			$shopPaymentRequests 				 = $this->Paginator->paginate(null, $conditions);
		}else{
			$config 							 = array('order'=>array('ShopPaymentRequest.modified'=>'DESC'),"fields" => ["*","WEEK(ShopPaymentRequest.request_date,1) semana"], "conditions" => $conditions);
			$shopPaymentRequests 				 = $this->ShopPaymentRequest->find("all",$config);

			$allRequest	 						 = $shopPaymentRequests;
			$shopPaymentRequests 				 = [];

			foreach ($allRequest as $key => $value) {

				if( in_array(AuthComponent::user("role"), [1,2]) ){

					$disbursments = $this->ShopPaymentRequest->Disbursement->find("all",["fields" => ["Credit.customer_id"], "conditions" => ["Disbursement.shop_payment_request_id" => $value["ShopPaymentRequest"]["id"]] ]);

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

		$this->set(compact('shopPaymentRequests'));
	}

	public function index(){
		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);

		$query = $this->request->query;

		if (in_array(AuthComponent::user("role"), [4])) {

			$saldos = $this->validateShopCom(true);
			$this->set("saldos", $saldos);
			$conditions["ShopPaymentRequest.shop_id"] = AuthComponent::user("shop_id");

		}


		if (isset($query["state"]) && $query["state"] != "" && in_array($query["state"], [0, 1, 2])) {
			$conditions["ShopPaymentRequest.state"] = $query["state"];
			$this->set("estados", $query["state"]);
		} else {
			$this->set("estados", "");
		}

		if (isset($query["commerce"]) && $query["commerce"] != "") {
			$conditions["Shop.code"] = $query["commerce"];
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
			//$this->Paginator->settings = array('order' => array('ShopPaymentRequest.modified' => 'DESC'));
			$shopPaymentRequests = $this->Paginator->paginate(null, $conditions);
			echo $this->getCommercesByShop(null, true);
			exit();
			//$conditions["ShopCommerce.shop_commerce_id"] = $this->getCommercesByShop(null, true);
			//if (in_array(AuthComponent::user("role"), [4, 7])) {
			$saldos = $this->validateShopCom(true);
			$this->set("saldos", $saldos);
			/*if (AuthComponent::user("role") == 4) {
				$conditions["ShopPaymentRequest.shop_commerce_id"] = $this->getCommercesByShop(null, true);
				echo 12121;exit();
			} else {
				$conditions["ShopPaymentRequest.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");
			}*/

		} else {
			$config = array('order' => array('ShopPaymentRequest.modified' => 'DESC'), "fields" => ["*", "WEEK(ShopPaymentRequest.request_date,1) semana"], "conditions" => $conditions);
			$shopPaymentRequests = $this->ShopPaymentRequest->find("all", $config);

			$allRequest = $shopPaymentRequests;
			$shopPaymentRequests = [];

			foreach ($allRequest as $key => $value) {

				if (in_array(AuthComponent::user("role"), [1, 2])) {

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

		$this->set(compact('shopPaymentRequests'));
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
		$this->ShopPaymentRequest->recursive = 3;
		$conditions = array('ShopPaymentRequest.' . $this->ShopPaymentRequest->primaryKey => $id);
		$shopPaymentRequest = $this->ShopPaymentRequest->find('first', compact('conditions'));

		//$shop 		= $this->ShopPaymentRequest->Shop->findById($shopPaymentRequest["ShopPaymentRequest"]["shop_id"]);
		$shop = $this->ShopPaymentRequest->findById($shopPaymentRequest["ShopCommerce"]["shop_id"]);

		$socialreason =  $shop["ShopCommerce"]["Shop"]["social_reason"];
		$socialcode =  $shop["ShopCommerce"]["Shop"]["social_reason"];
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

			$this->ShopPaymentRequest->save($this->request->data);

			$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			$this->redirect(["action"=>"view", $this->encrypt($id)]);
		}

		$this->set('otherDepts', $otherDepts);
		$this->set('shopPaymentRequest', $shopPaymentRequest);
		$this->set('shop', $shop);
		$this->set('socialreason', $socialreason);
		$this->set('socialcode', $socialcode);

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

		$fecha_inicial = date('Y-m-d', strtotime('last Sunday'));

		$this->loadModel("ShopsDebt");
		$r = $this->loadModel("Disbursement");
		$this->loadModel("Payment");

		$debts = $this->ShopsDebt->find("all", ["conditions" => ["ShopsDebt.shop_commerce_id" => $id, "state" => "0"], "recursive" => -1]);

		if ($this->request->is('post')) {
			$this->autoRender = false;
			$porcentual =  $this->request->data["porcentual"];
			$disbursmentpago =  $this->request->data["disbursmentpago"];
			$iddisbursments = $this->request->data["iddisbursments"];

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
			$ivaPago = ($otherDebpt * 0.19);
			$totalPago = $disbursmentpago;

			$data = [
				"ShopPaymentRequest" => [
					"request_value" => $totalPago,
					"iva" => $ivaPago,
					"request_date" => date("Y-m-d H:i:s"),
					"shop_commerce_id" => $id,
					"payment_type" => $this->request->data["type"],
					"user_id" => AuthComponent::user("id")
				]
			];

			if ($this->request->data["type"] == 1) {
				$disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.id" => $iddisbursments, "Disbursement.state" => "1", "Disbursement.modified >= " => $fecha_inicial], "recursive" => 1]);
			}else{
				$disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.id" => $iddisbursments, "Disbursement.state" => "1", "Disbursement.modified <=" => $fecha_inicial], "recursive" => 1]);
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
				$this->sendMail($options);

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		$disbursments1 = $this->Disbursement->find("all", ["conditions" => ["Disbursement.shop_commerce_id" => $id, "Disbursement.state" => "1", "Disbursement.modified >= " => $fecha_inicial], "recursive" => 1]);


		$disbursments2 = $this->Disbursement->find("all", ["conditions" => ["Disbursement.shop_commerce_id" => $id, "Disbursement.state" => "1", "Disbursement.modified <=" => $fecha_inicial], "recursive" => 1]);


		//$disbursments = $this->Disbursement->find("all", ["conditions" => ["Disbursement.shop_commerce_i" => $id, "Disbursement.state" => "1"], "recursive" => 1]);
		//$user = $this->User->findById(3);

		$payments = $this->Payment->find("all", ["conditions" => ["Payment.shop_commerce_id" => $id, "Payment.state" => "0"], "recursive" => 2]);

		$debtsTotal = $this->ShopsDebt->field("SUM(value)", ["ShopsDebt.shop_commerce_id" => $id, "state" => "0",]);
		//$debtsTotal1 = $this->ShopsDebt->field("SUM(value)", ["ShopsDebt.shop_commerce_id" => $id, "state" => "0", "Disbursement.modified >= " => $fecha_inicial]);
		$query = "SELECT
						SUM(`ShopsDebt`.`value`)
					FROM
						`shops_debts` AS `ShopsDebt`
					LEFT JOIN `disbursements` AS `Disbursement` ON (
						`Disbursement`.`shop_commerce_id` = `ShopsDebt`.`shop_commerce_id`
					)
					WHERE
						`ShopsDebt`.`shop_commerce_id` = 35
					AND `ShopsDebt`.`state` = 0
					AND `Disbursement`.`modified` >= '" . $fecha_inicial . "'
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
						`ShopsDebt`.`shop_commerce_id` = 35
					AND `ShopsDebt`.`state` = 0
					AND `Disbursement`.`modified` <= '" . $fecha_inicial . "'
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

		if ($total <= 0) {
			$this->redirect(["action" => "index"]);
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
