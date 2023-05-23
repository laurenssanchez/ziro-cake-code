<?php
App::uses('AppController', 'Controller');
/**
 * CustomersReferences Controller
 *
 * @property CustomersReference $CustomersReference
 * @property PaginatorComponent $Paginator
 */
class CustomersReferencesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->CustomersReference->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CustomersReference->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CustomersReference.modified'=>'DESC'));
		$customersReferences = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('customersReferences'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CustomersReference->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CustomersReference->recursive = 0;
		$conditions = array('CustomersReference.' . $this->CustomersReference->primaryKey => $id);
		$this->set('customersReference', $this->CustomersReference->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CustomersReference->create();
			if ($this->CustomersReference->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$customers = $this->CustomersReference->Customer->find('list');
		$this->set(compact('customers'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CustomersReference->id = $id;
		if (!$this->CustomersReference->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CustomersReference->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CustomersReference.' . $this->CustomersReference->primaryKey => $id);
			$this->request->data = $this->CustomersReference->find('first', compact('conditions'));
		}
		$customers = $this->CustomersReference->Customer->find('list');
		$this->set(compact('customers'));
	}
}
