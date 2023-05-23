<?php
App::uses('AppController', 'Controller');

/**
 * Actions Controller
 *
 * @property Action $Action
 * @property PaginatorComponent $Paginator
 */
class ActionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->Action->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Action->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Action.modified'=>'DESC'));
		$actions = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('actions'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Action->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Action->recursive = 0;
		$conditions = array('Action.' . $this->Action->primaryKey => $id);
		$this->set('action', $this->Action->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Action->create();
			if ($this->Action->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Action->id = $id;
		if (!$this->Action->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Action->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Action.' . $this->Action->primaryKey => $id);
			$this->request->data = $this->Action->find('first', compact('conditions'));
		}
	}
}
