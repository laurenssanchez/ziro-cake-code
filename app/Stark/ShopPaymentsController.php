<?php
App::uses('AppController', 'Controller');
/**
 * ShopPayments Controller
 *
 * @property ShopPayment $ShopPayment
 * @property PaginatorComponent $Paginator
 */
class ShopPaymentsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->ShopPayment->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->ShopPayment->recursive = 0;
		$this->Paginator->settings = array('order'=>array('ShopPayment.modified'=>'DESC'));
		$shopPayments = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('shopPayments'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->ShopPayment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->ShopPayment->recursive = 0;
		$conditions = array('ShopPayment.' . $this->ShopPayment->primaryKey => $id);
		$this->set('shopPayment', $this->ShopPayment->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->ShopPayment->create();
			if ($this->ShopPayment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->ShopPayment->id = $id;
		if (!$this->ShopPayment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ShopPayment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('ShopPayment.' . $this->ShopPayment->primaryKey => $id);
			$this->request->data = $this->ShopPayment->find('first', compact('conditions'));
		}
	}
}
