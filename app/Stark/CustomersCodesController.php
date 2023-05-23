<?php
App::uses('AppController', 'Controller');
/**
 * CustomersCodes Controller
 *
 * @property CustomersCode $CustomersCode
 * @property PaginatorComponent $Paginator
 */
class CustomersCodesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {

		if(!isset($this->request->query["tab"]) || (isset($this->request->query["tab"]) && !in_array($this->request->query["tab"],[1,2] ) ) ){
			$this->redirect(["controller" => "customers_codes","action"=>"index","?" => ["tab" => 1]]);
		}else{
			$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
			$this->set("q",$q);
			$tab = $this->request->query["tab"];
			if($tab == 2){
				$this->loadModel("User");
				$users = $this->User->find("all",["conditions"=>["User.code != " => null ] ]);
				$this->set("users",$users);
			}else{
				$conditions = $this->CustomersCode->buildConditions($this->request->query);
				$this->CustomersCode->recursive = 0;
				$this->Paginator->settings = array('order'=>array('CustomersCode.modified'=>'DESC'));
				$customersCodes = $this->Paginator->paginate(null, $conditions);
				$this->set(compact('customersCodes'));
			}
			$this->set("tab",$tab);
		}


	}

	public function resend($id = null) {
		$this->autoRender = false;
		$id = $this->decrypt($id);
		if (!$this->CustomersCode->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CustomersCode->recursive = 2;
		$conditions = array('CustomersCode.' . $this->CustomersCode->primaryKey => $id);
		$customersCode = $this->CustomersCode->find('first', compact('conditions'));

		if($customersCode["CustomersCode"]["type_code"] == "2"){
			$this->sendMessageTxt($customersCode["Customer"]["CustomersPhone"]["0"]["phone_number"],$customersCode["CustomersCode"]["code"] );
			$this->Session->setFlash(__('Código reenviado'), 'flash_success');
		}
	}
	public function resend_user($id = null) {
		$this->autoRender = false;
		$id = $this->decrypt($id);
		$this->loadModel("User");
		if (!$this->User->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$conditions = array('User.' . $this->User->primaryKey => $id);
		$user = $this->User->find('first', compact('conditions'));

		if(!empty($user)){
			$this->sendMessageTxt($user["User"]["phone"],$user["User"]["code"] );
			$this->Session->setFlash(__('Código reenviado'), 'flash_success');
		}
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CustomersCode->create();
			if ($this->CustomersCode->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$customers = $this->CustomersCode->Customer->find('list');
		$this->set(compact('customers'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CustomersCode->id = $id;
		if (!$this->CustomersCode->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CustomersCode->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CustomersCode.' . $this->CustomersCode->primaryKey => $id);
			$this->request->data = $this->CustomersCode->find('first', compact('conditions'));
		}
		$customers = $this->CustomersCode->Customer->find('list');
		$this->set(compact('customers'));
	}
}
