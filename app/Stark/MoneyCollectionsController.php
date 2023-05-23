<?php
App::uses('AppController', 'Controller');
/**
 * MoneyCollections Controller
 *
 * @property MoneyCollection $MoneyCollection
 * @property PaginatorComponent $Paginator
 */
class MoneyCollectionsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->MoneyCollection->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->MoneyCollection->recursive = 0;
		$this->Paginator->settings = array('order'=>array('MoneyCollection.modified'=>'DESC'));
		$moneyCollections = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('moneyCollections'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->MoneyCollection->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->MoneyCollection->recursive = 0;
		$conditions = array('MoneyCollection.' . $this->MoneyCollection->primaryKey => $id);
		$this->set('moneyCollection', $this->MoneyCollection->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->MoneyCollection->create();
			if ($this->MoneyCollection->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$shopCommerces = $this->MoneyCollection->ShopCommerce->find('list');
		$users = $this->MoneyCollection->User->find('list');
		$this->set(compact('shopCommerces', 'users'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->MoneyCollection->id = $id;
		if (!$this->MoneyCollection->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->MoneyCollection->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('MoneyCollection.' . $this->MoneyCollection->primaryKey => $id);
			$this->request->data = $this->MoneyCollection->find('first', compact('conditions'));
		}
		$shopCommerces = $this->MoneyCollection->ShopCommerce->find('list');
		$users = $this->MoneyCollection->User->find('list');
		$this->set(compact('shopCommerces', 'users'));
	}
}
