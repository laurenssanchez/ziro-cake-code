<?php
App::uses('AppController', 'Controller');
/**
 * Notes Controller
 *
 * @property Note $Note
 * @property PaginatorComponent $Paginator
 */
class NotesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->Note->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Note->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Note.modified'=>'DESC'));
		$notes = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('notes'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Note->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Note->recursive = 0;
		$conditions = array('Note.' . $this->Note->primaryKey => $id);
		$this->set('note', $this->Note->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Note->create();
			if ($this->Note->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsPlans = $this->Note->CreditsPlan->find('list');
		$users = $this->Note->User->find('list');
		$this->set(compact('creditsPlans', 'users'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Note->id = $id;
		if (!$this->Note->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Note->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Note.' . $this->Note->primaryKey => $id);
			$this->request->data = $this->Note->find('first', compact('conditions'));
		}
		$creditsPlans = $this->Note->CreditsPlan->find('list');
		$users = $this->Note->User->find('list');
		$this->set(compact('creditsPlans', 'users'));
	}
}
