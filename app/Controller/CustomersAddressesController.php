<?php
App::uses('AppController', 'Controller');
/**
 * CustomersAddresses Controller
 *
 * @property CustomersAddress $CustomersAddress
 * @property PaginatorComponent $Paginator
 */
class CustomersAddressesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->CustomersAddress->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CustomersAddress->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CustomersAddress.modified'=>'DESC'));
		$customersAddresses = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('customersAddresses'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CustomersAddress->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CustomersAddress->recursive = 0;
		$conditions = array('CustomersAddress.' . $this->CustomersAddress->primaryKey => $id);
		$this->set('customersAddress', $this->CustomersAddress->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CustomersAddress->create();
			if ($this->CustomersAddress->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$customers = $this->CustomersAddress->Customer->find('list');
		$this->set(compact('customers'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CustomersAddress->id = $id;
		if (!$this->CustomersAddress->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CustomersAddress->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CustomersAddress.' . $this->CustomersAddress->primaryKey => $id);
			$this->request->data = $this->CustomersAddress->find('first', compact('conditions'));
		}
		$customers = $this->CustomersAddress->Customer->find('list');
		$this->set(compact('customers'));
	}
}
