<?php
App::uses('AppController', 'Controller');
/**
 * Disbursements Controller
 *
 * @property Disbursement $Disbursement
 * @property PaginatorComponent $Paginator
 */
class DisbursementsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function beforeFilter()
    {
        parent::beforeFilter();
      	die();
	}

	public function index() {
		$conditions = $this->Disbursement->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Disbursement->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Disbursement.modified'=>'DESC'));
		$disbursements = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('disbursements'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Disbursement->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Disbursement->recursive = 0;
		$conditions = array('Disbursement.' . $this->Disbursement->primaryKey => $id);
		$this->set('disbursement', $this->Disbursement->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Disbursement->create();
			if ($this->Disbursement->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$credits = $this->Disbursement->Credit->find('list');
		$shopCommerces = $this->Disbursement->ShopCommerce->find('list');
		$this->set(compact('credits', 'shopCommerces'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Disbursement->id = $id;
		if (!$this->Disbursement->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Disbursement->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Disbursement.' . $this->Disbursement->primaryKey => $id);
			$this->request->data = $this->Disbursement->find('first', compact('conditions'));
		}
		$credits = $this->Disbursement->Credit->find('list');
		$shopCommerces = $this->Disbursement->ShopCommerce->find('list');
		$this->set(compact('credits', 'shopCommerces'));
	}
}
