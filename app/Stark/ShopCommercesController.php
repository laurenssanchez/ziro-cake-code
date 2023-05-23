<?php
App::uses('AppController', 'Controller');
/**
 * ShopCommerces Controller
 *
 * @property ShopCommerce $ShopCommerce
 * @property PaginatorComponent $Paginator
 */
class ShopCommercesController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('verifyCode');
	}

	public function index() {
		$this->validate_number_commerces();
		$conditions = $this->ShopCommerce->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$conditions["ShopCommerce.shop_id"] = AuthComponent::user("Shop.id");
		$this->set("q",$q);
		$this->ShopCommerce->recursive = 0;
		$this->Paginator->settings = array('order'=>array('ShopCommerce.modified'=>'DESC'));
		$shopCommerces = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('shopCommerces'));
	}

	public function validate_number_commerces($return = false){
		$totalCommerces = AuthComponent::user("Shop.number_commerces");
		$totalCreated   = $this->ShopCommerce->find("count",["conditions"=>[ "ShopCommerce.shop_id" => AuthComponent::user("Shop.id"),"ShopCommerce.state" => 1 ] ]);
		$created = false;
		if($totalCreated < $totalCommerces){
			$created = true;
		}
		if($return){
			return $created;
		}
		$this->set("created",$created);
	}

	public function verifyCode(){
		$this->autoRender = false;
		$code = 0;
		if (isset($this->request->query["data"]["Customer"]["code"])) {
			$code 	= $this->request->query["data"]["Customer"]["code"];
		}

		$allCode = $this->ShopCommerce->find("count",["conditions"=>["ShopCommerce.code"=>$code,"ShopCommerce.state" => 1]]);
		if($allCode == 0 ){
			throw new NotFoundException(__('Página no encontrada'));
		}else{
			header("HTTP/1.1 200 Ok");
		}
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->ShopCommerce->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->ShopCommerce->recursive = 1;
		$conditions = array('ShopCommerce.' . $this->ShopCommerce->primaryKey => $id);
		$this->set('shopCommerce', $this->ShopCommerce->find('first', compact('conditions')));

		$users = $this->ShopCommerce->User->findAllByStateAndShopCommerceId(1,$id);
		$this->set("users",$users);
	}


	public function add() {
		$validateCreated = $this->validate_number_commerces(true);

		if($validateCreated == false){
			$this->Session->setFlash(__('Ya están registrados el total de sucursales de tu plan. Si necesitas otra comunicate con la empresa'), 'flash_error');
			$this->redirect(array('action' => 'index'));
		}
		$this->request->data["ShopCommerce"]["code"] = $this->ShopCommerce->generate();

		if ($this->request->is('post')) {
			$this->ShopCommerce->create();
			if ($this->ShopCommerce->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'view',$this->encrypt($this->ShopCommerce->id)));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
	}

	public function add_user_commerce(){
		$commerces = $this->ShopCommerce->find("list",["conditions" => ["shop_id"=>AuthComponent::user("shop_id"),"state"=>1]]);

		if ($this->request->is('post')) {
			$this->ShopCommerce->User->create();
			if ($this->ShopCommerce->User->save($this->request->data)) {

				$varsEmail = [
					"name" 		=> $this->request->data["User"]["name"],
					"role" 		=> Configure::read("ROLES.".$this->request->data["User"]["name"]),
					"shop" 		=> AuthComponent::user("Shop.social_reason"),
					"commerce" 	=> $commerces[$this->request->data["User"]["shop_commerce_id"]],
					"email" 	=> $this->request->data["User"]["email"],
					"password"  => $this->request->data["User"]["password"],
	 			];

				$options = [
					"subject" 	=> "Bienvenido a Zíro",
					"to"   		=> $this->request->data["User"]["email"],
					"vars" 	    => $varsEmail,
					"template"	=> "new_user_sucursal",
				];

				$this->sendMail($options);

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'view',$this->encrypt($this->request->data["User"]["shop_commerce_id"]) ));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$this->set(compact("commerces"));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->ShopCommerce->id = $id;
		if (!$this->ShopCommerce->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ShopCommerce->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('ShopCommerce.' . $this->ShopCommerce->primaryKey => $id);
			$this->request->data = $this->ShopCommerce->find('first', compact('conditions'));
		}
		$users = $this->ShopCommerce->User->find('list');
		$this->set(compact('users'));
	}
}
