<?php
App::uses('AppController', 'Controller');
/**
 * CollectionFees Controller
 *
 * @property CollectionFee $CollectionFee
 * @property PaginatorComponent $Paginator
 */
class CollectionFeesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->CollectionFee->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CollectionFee->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CollectionFee.modified'=>'DESC'));
		$collectionFees = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('collectionFees'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CollectionFee->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CollectionFee->recursive = 0;
		$conditions = array('CollectionFee.' . $this->CollectionFee->primaryKey => $id);
		$this->set('collectionFee', $this->CollectionFee->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CollectionFee->create();
			if ($this->CollectionFee->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsLines = $this->CollectionFee->CreditsLine->find('list');
		$this->set(compact('creditsLines'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CollectionFee->id = $id;
		if (!$this->CollectionFee->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CollectionFee->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CollectionFee.' . $this->CollectionFee->primaryKey => $id);
			$this->request->data = $this->CollectionFee->find('first', compact('conditions'));
		}
		$creditsLines = $this->CollectionFee->CreditsLine->find('list');
		$this->set(compact('creditsLines'));
	}
}
