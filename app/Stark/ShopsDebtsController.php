<?php
App::uses('AppController', 'Controller');
/**
 * ShopsDebts Controller
 *
 * @property ShopsDebt $ShopsDebt
 * @property PaginatorComponent $Paginator
 */
class ShopsDebtsController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->ShopsDebt->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->ShopsDebt->recursive = 0;
		$this->Paginator->settings = array('order'=>array('ShopsDebt.modified'=>'DESC'));
		$shopsDebts = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('shopsDebts'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->ShopsDebt->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->ShopsDebt->recursive = 0;
		$conditions = array('ShopsDebt.' . $this->ShopsDebt->primaryKey => $id);
		$this->set('shopsDebt', $this->ShopsDebt->find('first', compact('conditions')));
	}


	public function add($id) {
		$this->layout 	= false;
		$this->ShopsDebt->ShopCommerce->recursive = -1;
		$commerces 		= $this->ShopsDebt->ShopCommerce->findAllByShopId($this->decrypt($id));
		$commercesId    = [];
		$commercesList  = [];
		if(!empty($commerces)){
			$commercesId = Set::extract($commerces,"{n}.ShopCommerce.id");
			foreach ($commerces as $key => $value) {
				$commercesList[$value["ShopCommerce"]["id"]] = $value["ShopCommerce"]["name"];
			}
		}else{
			$commercesId = 0;
		}
		$debts = $this->ShopsDebt->find("all",["order" => ["ShopsDebt.id" => "ASC"],"conditions" => ["ShopsDebt.shop_commerce_id" => $commercesId ] ]);
		
		if ($this->request->is('post')) {
			$this->autoRender = false;
			$this->ShopsDebt->create();
			if ($this->ShopsDebt->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				return 1;
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
				return 0;
			}
		}
		$this->ShopsDebt->ShopCommerce->Shop->recursive = -1;
		$shop = $this->ShopsDebt->ShopCommerce->Shop->findById($this->decrypt($id));
		$this->set(compact('commercesList','shop','id','debts'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->ShopsDebt->id = $id;
		if (!$this->ShopsDebt->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ShopsDebt->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('ShopsDebt.' . $this->ShopsDebt->primaryKey => $id);
			$this->request->data = $this->ShopsDebt->find('first', compact('conditions'));
		}
		$users = $this->ShopsDebt->User->find('list');
		$shops = $this->ShopsDebt->Shop->find('list');
		$credits = $this->ShopsDebt->Credit->find('list');
		$this->set(compact('users', 'shops', 'credits'));
	}
}
