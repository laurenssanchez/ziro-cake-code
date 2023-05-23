<?php
App::uses('AppController', 'Controller');
/**
 * Receipts Controller
 *
 * @property Receipt $Receipt
 * @property PaginatorComponent $Paginator
 */
class ReceiptsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {
		$conditions = $this->Receipt->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Receipt->recursive = 1;
		$this->Paginator->settings = array('order'=>array('Receipt.modified'=>'DESC'));
		$receipts = $this->Paginator->paginate(null, $conditions);

		if(!empty($receipts)){
			$this->loadModel("Customer");
			$this->loadModel("Credit");
			foreach ($receipts as $key => $value) {
				$this->Customer->recursive = -1;
				$this->Credit->recursive = -1;
				$credit   = $this->Credit->findById($value["CreditsPlan"]["credit_id"]);
				$customer = $this->Customer->findById($credit["Credit"]["customer_id"]);
				$receipts[$key]["Customer"] = $customer["Customer"];
				$receipts[$key]["Shop"] = $this->Receipt->ShopCommerce->Shop->field("social_reason",["id"=>$value["ShopCommerce"]["shop_id"]]);
			}
		}

		$this->set(compact('receipts'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Receipt->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Receipt->recursive = 0;
		$conditions = array('Receipt.' . $this->Receipt->primaryKey => $id);
		$this->set('receipt', $this->Receipt->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Receipt->create();
			if ($this->Receipt->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsPlans = $this->Receipt->CreditsPlan->find('list');
		$users = $this->Receipt->User->find('list');
		$shopCommerces = $this->Receipt->ShopCommerce->find('list');
		$this->set(compact('creditsPlans', 'users', 'shopCommerces'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Receipt->id = $id;
		if (!$this->Receipt->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Receipt->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Receipt.' . $this->Receipt->primaryKey => $id);
			$this->request->data = $this->Receipt->find('first', compact('conditions'));
		}
		$creditsPlans = $this->Receipt->CreditsPlan->find('list');
		$users = $this->Receipt->User->find('list');
		$shopCommerces = $this->Receipt->ShopCommerce->find('list');
		$this->set(compact('creditsPlans', 'users', 'shopCommerces'));
	}
}
