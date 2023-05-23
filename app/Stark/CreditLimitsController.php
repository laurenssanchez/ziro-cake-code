<?php
App::uses('AppController', 'Controller');
/**
 * CreditLimits Controller
 *
 * @property CreditLimit $CreditLimit
 * @property PaginatorComponent $Paginator
 */
class CreditLimitsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->CreditLimit->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CreditLimit->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CreditLimit.modified'=>'DESC'));
		$creditLimits = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('creditLimits'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CreditLimit->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CreditLimit->recursive = 0;
		$conditions = array('CreditLimit.' . $this->CreditLimit->primaryKey => $id);
		$this->set('creditLimit', $this->CreditLimit->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CreditLimit->create();
			if ($this->CreditLimit->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$credits = $this->CreditLimit->Credit->find('list');
		$creditsRequests = $this->CreditLimit->CreditsRequest->find('list');
		$users = $this->CreditLimit->User->find('list');
		$customers = $this->CreditLimit->Customer->find('list');
		$this->set(compact('credits', 'creditsRequests', 'users', 'customers'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CreditLimit->id = $id;
		if (!$this->CreditLimit->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CreditLimit->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CreditLimit.' . $this->CreditLimit->primaryKey => $id);
			$this->request->data = $this->CreditLimit->find('first', compact('conditions'));
		}
		$credits = $this->CreditLimit->Credit->find('list');
		$creditsRequests = $this->CreditLimit->CreditsRequest->find('list');
		$users = $this->CreditLimit->User->find('list');
		$customers = $this->CreditLimit->Customer->find('list');
		$this->set(compact('credits', 'creditsRequests', 'users', 'customers'));
	}
}
