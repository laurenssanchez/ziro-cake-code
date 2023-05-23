<?php
App::uses('AppController', 'Controller');
/**
 * Automatics Controller
 *
 * @property Automatic $Automatic
 * @property PaginatorComponent $Paginator
 */
class AutomaticsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->Automatic->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Automatic->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Automatic.modified'=>'DESC'));
		$automatics = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('automatics'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Automatic->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Automatic->recursive = 0;
		$conditions = array('Automatic.' . $this->Automatic->primaryKey => $id);
		$this->set('automatic', $this->Automatic->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Automatic->create();
			if ($this->Automatic->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Automatic->id = $id;
		if (!$this->Automatic->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Automatic->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Automatic.' . $this->Automatic->primaryKey => $id);
			$this->request->data = $this->Automatic->find('first', compact('conditions'));
		}
	}
}
