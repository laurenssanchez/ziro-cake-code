<?php
App::uses('AppController', 'Controller');
/**
 * RequestsPayments Controller
 *
 * @property RequestsPayment $RequestsPayment
 * @property PaginatorComponent $Paginator
 */
class RequestsPaymentsController extends AppController {


	public $components = array('Paginator');

	public function index() {
		$conditions = $this->RequestsPayment->buildConditions($this->request->query);
		$query = $this->request->query;

		if(isset($query["state"]) && $query["state"] != "" && in_array($query["state"], [0,1,2])){
			$conditions["RequestsPayment.state"] = $query["state"];
			$this->set("estados",$query["state"]);
		}else{
			$this->set("estados","");
		}

		if(isset($query["commerce"]) && $query["commerce"] != "" ){
			$conditions["ShopCommerce.code"] = $query["commerce"];
			$this->set("commerce",$query["commerce"]);

		}else{
			$this->set("commerce","");
		}

		if(isset($query["request_date"]) && $query["request_date"] != "" ){
			$conditions["DATE(RequestsPayment.created)"] = $query["request_date"];
			$this->set("request_date",$query["request_date"]);
		}else{
			$this->set("request_date","");
		}

		if(isset($query["final_date"]) && $query["final_date"] != "" ){
			$conditions["DATE(RequestsPayment.date_payment)"] = $query["final_date"];
			$this->set("final_date",$query["final_date"]);
		}else{
			$this->set("final_date","");
		}

		if(isset($query["customer"]) && $query["customer"] != "" ){

			$requests = $this->RequestsPayment->Request->find("list",["fields"=>["id","id"],"conditions"=>["Request.identification" => $query["customer"]]]);

			if (!empty($requests)) {
				$conditions["RequestsPayment.id"] = $requests;
			}else{
				$conditions["RequestsPayment.id"] = null;
			}

			$this->set("customer",$query["customer"]);
		}else{
			$this->set("customer","");
		}

		if (AuthComponent::user("role") == 4) {
			$conditions["RequestsPayment.shop_commerce_id"] = $this->RequestsPayment->ShopCommerce->find('list',["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")],"fields" => ["id","id"] ]);			
		}else if(AuthComponent::user("role") == 6){
			$conditions["RequestsPayment.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");

		}
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->RequestsPayment->recursive = 2;
		$this->Paginator->settings = array('order'=>array('RequestsPayment.modified'=>'DESC'));
		$requestsPayments = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('requestsPayments'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->RequestsPayment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->RequestsPayment->recursive = 2;
		$conditions = array('RequestsPayment.' . $this->RequestsPayment->primaryKey => $id);
		$this->set('requestsPayment', $this->RequestsPayment->find('first', compact('conditions')));
	}


	public function add($shopCommerceId = null) {

		$requestsNoPayment = $this->RequestsPayment->Request->find("all",["conditions" => ["Request.state" => 1,"Request.requests_payment_id" => NULL,"Request.shop_commerce_id" => $this->decrypt($shopCommerceId) ],]);

		if (is_null($shopCommerceId) || empty($requestsNoPayment)) {
			$this->Session->setFlash(__('Error no hay pagos por guardar.'), 'flash_error');
			$this->redirect(array('action' => 'index','controller' => 'requests'));
		}

		$this->loadModel("Config");
		$config = $this->Config->findById(1);
		$this->RequestsPayment->ShopCommerce->recursive = -1;
		$shopCommerce = $this->RequestsPayment->ShopCommerce->findById($this->decrypt($this->decrypt($shopCommerceId)));

		if ($this->request->is('post')) {
			$this->RequestsPayment->create();
			if ($this->RequestsPayment->save($this->request->data)) {

				foreach ($requestsNoPayment as $key => $value) {
					$value["Request"]["state_request_payment"] = 0;
					$value["Request"]["requests_payment_id"] = $this->RequestsPayment->id;
					$this->RequestsPayment->Request->save($value);
				}

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}

		$this->set(compact("requestsNoPayment","config","shopCommerceId"));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->RequestsPayment->id = $id;
		if (!$this->RequestsPayment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->RequestsPayment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('RequestsPayment.' . $this->RequestsPayment->primaryKey => $id);
			$this->request->data = $this->RequestsPayment->find('first', compact('conditions'));
		}
	}

	public function change($id){
		$this->autoRender = false;
		$id = $this->decrypt($id);
		$this->RequestsPayment->recursive = -1;
		$request = $this->RequestsPayment->findById($id);

		$request["RequestsPayment"]["state"] = 1;
		$request["RequestsPayment"]["note_payment"] = $this->request->data["note"];
		$request["RequestsPayment"]["date_payment"] = date("Y-m-d H:i:s");
		$this->RequestsPayment->save($request);

		$this->RequestsPayment->Request->updateAll(
			["Request.state_request_payment" => 1],
			["Request.requests_payment_id" => $id] 
		);
		$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
	}

	public function pending($id){
		$this->autoRender = false;
		$id = $this->decrypt($id);
		$this->RequestsPayment->recursive = -1;
		$request = $this->RequestsPayment->findById($id);

		$request["RequestsPayment"]["state"] = 2;
		$request["RequestsPayment"]["note"] = $this->request->data["note"];
		$request["RequestsPayment"]["date_pending"] = date("Y-m-d H:i:s");
		$this->RequestsPayment->save($request);

		$this->RequestsPayment->Request->updateAll(
			["Request.state_request_payment" => 2],
			["Request.requests_payment_id" => $id] 
		);
		$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
	}

}
