<?php
App::uses('AppController', 'Controller');
/**
 * Commitments Controller
 *
 * @property Commitment $Commitment
 * @property PaginatorComponent $Paginator
 */
class CommitmentsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->Commitment->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Commitment->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Commitment.modified'=>'DESC'));
		$commitments = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('commitments'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Commitment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Commitment->recursive = 0;
		$conditions = array('Commitment.' . $this->Commitment->primaryKey => $id);
		$this->set('commitment', $this->Commitment->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Commitment->create();
			if ($this->Commitment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsPlans = $this->Commitment->CreditsPlan->find('list');
		$users = $this->Commitment->User->find('list');
		$this->set(compact('creditsPlans', 'users'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Commitment->id = $id;
		if (!$this->Commitment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Commitment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Commitment.' . $this->Commitment->primaryKey => $id);
			$this->request->data = $this->Commitment->find('first', compact('conditions'));
		}
		$creditsPlans = $this->Commitment->CreditsPlan->find('list');
		$users = $this->Commitment->User->find('list');
		$this->set(compact('creditsPlans', 'users'));
	}
}
