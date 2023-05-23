<?php
App::uses('AppController', 'Controller');
/**
 * CustomersPhones Controller
 *
 * @property CustomersPhone $CustomersPhone
 * @property PaginatorComponent $Paginator
 */
class CustomersPhonesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->CustomersPhone->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CustomersPhone->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CustomersPhone.modified'=>'DESC'));
		$customersPhones = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('customersPhones'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CustomersPhone->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CustomersPhone->recursive = 0;
		$conditions = array('CustomersPhone.' . $this->CustomersPhone->primaryKey => $id);
		$this->set('customersPhone', $this->CustomersPhone->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CustomersPhone->create();
			if ($this->CustomersPhone->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$customers = $this->CustomersPhone->Customer->find('list');
		$this->set(compact('customers'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CustomersPhone->id = $id;
		if (!$this->CustomersPhone->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CustomersPhone->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CustomersPhone.' . $this->CustomersPhone->primaryKey => $id);
			$this->request->data = $this->CustomersPhone->find('first', compact('conditions'));
		}
		$customers = $this->CustomersPhone->Customer->find('list');
		$this->set(compact('customers'));
	}
}
