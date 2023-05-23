<?php
App::uses('AppController', 'Controller');
/**
 * RequestsDetails Controller
 *
 * @property RequestsDetail $RequestsDetail
 * @property PaginatorComponent $Paginator
 */
class RequestsDetailsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->RequestsDetail->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->RequestsDetail->recursive = 0;
		$this->Paginator->settings = array('order'=>array('RequestsDetail.modified'=>'DESC'));
		$requestsDetails = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('requestsDetails'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->RequestsDetail->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->RequestsDetail->recursive = 0;
		$conditions = array('RequestsDetail.' . $this->RequestsDetail->primaryKey => $id);
		$this->set('requestsDetail', $this->RequestsDetail->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->RequestsDetail->create();
			if ($this->RequestsDetail->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$requests = $this->RequestsDetail->Request->find('list');
		$this->set(compact('requests'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->RequestsDetail->id = $id;
		if (!$this->RequestsDetail->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->RequestsDetail->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('RequestsDetail.' . $this->RequestsDetail->primaryKey => $id);
			$this->request->data = $this->RequestsDetail->find('first', compact('conditions'));
		}
		$requests = $this->RequestsDetail->Request->find('list');
		$this->set(compact('requests'));
	}
}
