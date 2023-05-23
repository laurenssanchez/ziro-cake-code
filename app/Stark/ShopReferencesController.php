<?php
App::uses('AppController', 'Controller');
/**
 * ShopReferences Controller
 *
 * @property ShopReference $ShopReference
 * @property PaginatorComponent $Paginator
 */
class ShopReferencesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->ShopReference->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->ShopReference->recursive = 0;
		$this->Paginator->settings = array('order'=>array('ShopReference.modified'=>'DESC'));
		$shopReferences = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('shopReferences'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->ShopReference->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->ShopReference->recursive = 0;
		$conditions = array('ShopReference.' . $this->ShopReference->primaryKey => $id);
		$this->set('shopReference', $this->ShopReference->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->ShopReference->create();
			if ($this->ShopReference->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$shops = $this->ShopReference->Shop->find('list');
		$this->set(compact('shops'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->ShopReference->id = $id;
		if (!$this->ShopReference->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ShopReference->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('ShopReference.' . $this->ShopReference->primaryKey => $id);
			$this->request->data = $this->ShopReference->find('first', compact('conditions'));
		}
		$shops = $this->ShopReference->Shop->find('list');
		$this->set(compact('shops'));
	}
}
