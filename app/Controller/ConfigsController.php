<?php
App::uses('AppController', 'Controller');
/**
 * Configs Controller
 *
 * @property Config $Config
 * @property PaginatorComponent $Paginator
 */
class ConfigsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {
		$conditions = $this->Config->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Config->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Config.modified'=>'DESC'));
		$configs = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('configs'));
		$this->redirect(array('action' => 'edit'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Config->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Config->recursive = 0;
		$conditions = array('Config.' . $this->Config->primaryKey => $id);
		$this->set('config', $this->Config->find('first', compact('conditions')));
		$this->redirect(array('action' => 'edit'));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Config->create();
			if ($this->Config->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$this->redirect(array('action' => 'edit'));
	}


	public function edit($id = null) {
		$id = 1;
      	$this->Config->id = $id;
		if (!$this->Config->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Config->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'edit'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Config.' . $this->Config->primaryKey => $id);
			$this->request->data = $this->Config->find('first', compact('conditions'));
		}
	}
}
