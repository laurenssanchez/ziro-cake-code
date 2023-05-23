<?php
App::uses('AppController', 'Controller');
/**
 * ShopPaymentRequests Controller
 *
 * @property ShopPaymentRequest $ShopPaymentRequest
 * @property PaginatorComponent $Paginator
 */
class ShopPaymentRequestsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {

		$conditions = $this->ShopPaymentRequest->buildConditions($this->request->query);
		
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);

		if(in_array(AuthComponent::user("role"), [4,7])){
			$saldos = $this->validateShopCom(true);
			$this->set("saldos",$saldos);
			if(AuthComponent::user("role") == 4){
				$conditions["ShopPaymentRequest.shop_commerce_id"] = $this->getCommercesByShop(null,true);
			}else{
				$conditions["ShopPaymentRequest.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");				
			}
		}

		$this->ShopPaymentRequest->recursive = 2;
		$this->Paginator->settings 			 = array('order'=>array('ShopPaymentRequest.modified'=>'DESC'));
		$shopPaymentRequests 				 = $this->Paginator->paginate(null, $conditions);

		$this->set(compact('shopPaymentRequests'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->ShopPaymentRequest->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->ShopPaymentRequest->recursive = 2;
		$conditions = array('ShopPaymentRequest.' . $this->ShopPaymentRequest->primaryKey => $id);
		$shopPaymentRequest = $this->ShopPaymentRequest->find('first', compact('conditions'));

		$otherDepts = $this->ShopPaymentRequest->ShopsDebt->find("all",["conditions"=>["ShopsDebt.shop_commerce_id"=>$shopPaymentRequest["ShopPaymentRequest"]["shop_commerce_id"],"ShopsDebt.state"=>"0"],"recursive" => -1]);

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
			$this->redirect(["action"=>"index"]);
		}

		$this->set('otherDepts', $otherDepts);
		$this->set('shopPaymentRequest', $shopPaymentRequest);
	}


	public function add($shop_commerce_id) {

		$this->ShopPaymentRequest->ShopCommerce->unBindModel(["hasMany"=>["User","CreditsRequest"]]);

		$id 			= $this->decrypt($shop_commerce_id);
		$shopCommerce 	= $this->ShopPaymentRequest->ShopCommerce->findById($id);

		$this->loadModel("ShopsDebt");
  		$this->loadModel("Disbursement");
  		$this->loadModel("Payment");

		$debts 			= $this->ShopsDebt->find("all",["conditions"=>["ShopsDebt.shop_commerce_id"=>$id,"state"=>"0"],"recursive" => -1]);
  		$disbursments 	= $this->Disbursement->find("all",["conditions"=>["Disbursement.shop_commerce_id"=>$id,"Disbursement.state"=>"1"],"recursive"=>1]);

  		// $payments 			= $this->Payment->find("all",["conditions" => ["Payment.shop_commerce_id"=>$id,"Payment.state"=>"0"],"recursive" => 2 ]);

  		$debtsTotal			= $this->ShopsDebt->field("SUM(value)",["ShopsDebt.shop_commerce_id"=>$id,"state"=>"0"]);
  		$disbursmentsTotal 	= $this->Disbursement->field("SUM(value)",["Disbursement.shop_commerce_id"=>$id,"state"=>"1"]);
  		$total 				= 0;

  		
  		$debtsTotal 				= is_null($debtsTotal) ? 0 : $debtsTotal;
		$disbursmentsTotal 			= is_null($disbursmentsTotal) ? 0 : $disbursmentsTotal;

  		$total = $disbursmentsTotal-$debtsTotal;

  		if($total <= 0){
  			$this->redirect(["action"=>"index"]);
  		}


		if (is_null($shopCommerce)) {
			throw new NotFoundException(__('Página no encontrada'));
		}

		if ($this->request->is('post')) {
			$this->autoRender = false;
			$porcentual = $this->request->data["type"] == 1 ? ($shopCommerce["Shop"]["cost_min"]/100) : ($shopCommerce["Shop"]["cost_max"]/100);
			$otherDebpt = $total*$porcentual;

			$debtInfo = [
				"ShopsDebt" => [
					"user_id" 	=> 0,
					"type" 		=> 2,
					"value" 	=> $otherDebpt,
					"reason" 	=> $this->request->data["type"] == 1 ? "Comisión Pago 1" : "Comisión Pago 2",
					"state" 	=> 0,
					"shop_commerce_id" => $id,
				]
			];

			$this->ShopsDebt->create();
			$this->ShopsDebt->save($debtInfo);

			$debtInfo["ShopsDebt"]["id"] = $this->ShopsDebt->id;

			$debts[] = $debtInfo;

			$ivaPago 	= ($otherDebpt*0.19) + ($debtsTotal*0.19);
 			$totalPago 	= $total-$otherDebpt-$ivaPago;

 			$data = [
	 			"ShopPaymentRequest" => [
	 				"request_value" 	=> $totalPago,
	 				"iva" 				=> $ivaPago,
	 				"request_date" 		=> date("Y-m-d H:i:s"),
	 				"shop_commerce_id" 	=> $id,
	 				"payment_type"		=> $this->request->data["type"],
	 				"user_id" 			=> AuthComponent::user("id")
	 			]
	 		];

			$this->ShopPaymentRequest->create();
			if ($this->ShopPaymentRequest->save($data)) {
				$shopPaymentRequestId = $this->ShopPaymentRequest->id;
				
				foreach ($debts as $key => $value) {
					$value["ShopsDebt"]["state"] 				   = 1;
					$value["ShopsDebt"]["shop_payment_request_id"] = $shopPaymentRequestId;
					$this->ShopsDebt->save($value);
				}

				foreach ($disbursments as $key => $value) {
					$value["Disbursement"]["state"] 				  = 2;
					$value["Disbursement"]["shop_payment_request_id"] = $shopPaymentRequestId;
					$this->Disbursement->save($value);
				}

				$this->loadModel("User");
				$this->User->recursive = -1;
				$users = $this->User->findAllByRole([1,2]);

				$emails = Set::extract($users,"{n}.User.email");

				$options = [
					"subject" 	=> "Pago solicitado ".$shopCommerce["Shop"]["social_reason"]." - ".$shopCommerce["ShopCommerce"]["name"],
					"to"   		=> $emails,
					"vars" 	    => ["name"=>$shopCommerce["Shop"]["social_reason"]." - ".$shopCommerce["ShopCommerce"]["name"], "total" => $totalPago],
					"template"	=> "payment_request",
				];
				$this->sendMail($options);

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		$this->set(compact('shopCommerce',"debts","disbursments","total","debtsTotal","paymentsTotal","payments"));
	}


	public function edit($id = null) {
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
