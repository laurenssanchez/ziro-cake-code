<?php
App::uses('AppController', 'Controller');
/**
 * Histories Controller
 *
 * @property History $History
 * @property PaginatorComponent $Paginator
 */
class HistoriesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->History->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->History->recursive = 0;
		$this->Paginator->settings = array('order'=>array('History.modified'=>'DESC'));
		$histories = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('histories'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->History->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->History->recursive = 0;
		$conditions = array('History.' . $this->History->primaryKey => $id);
		$this->set('history', $this->History->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->History->create();
			if ($this->History->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsPlans = $this->History->CreditsPlan->find('list');
		$users = $this->History->User->find('list');
		$this->set(compact('creditsPlans', 'users'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->History->id = $id;
		if (!$this->History->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->History->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('History.' . $this->History->primaryKey => $id);
			$this->request->data = $this->History->find('first', compact('conditions'));
		}
		$creditsPlans = $this->History->CreditsPlan->find('list');
		$users = $this->History->User->find('list');
		$this->set(compact('creditsPlans', 'users'));
	}
}
