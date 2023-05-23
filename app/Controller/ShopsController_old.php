<?php
App::uses('AppController', 'Controller');
/**
 * Shops Controller
 *
 * @property Shop $Shop
 * @property PaginatorComponent $Paginator
 */
class ShopsController extends AppController {


	public $components = array('Paginator');

	public function index() {
		$conditions = $this->Shop->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Shop->unBindModel([
			"belongsTo" => ["Adviser"]
		]);
		$this->Shop->recursive = 2;
		$this->Paginator->settings = array('order'=>array('Shop.modified'=>'DESC'));
		$shops = $this->Paginator->paginate(null, $conditions);

		foreach ($shops as $keyShop => $valueShop) {
			$commerces = [];
			foreach ($valueShop["ShopCommerce"] as $keyCom => $valueCommerce) {
				$commerces[] = $valueCommerce["id"];
			}
			if(!empty($commerces)){
				$this->loadModel("ShopsDebt");
				$shops[$keyShop]["Shop"]["debt"] = $this->ShopsDebt->field("SUM(value)",["shop_commerce_id"=>$commerces,"state"=>0]);
			}
		}

		$this->set(compact('shops'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Shop->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Shop->recursive = 1;
		$conditions = array('Shop.' . $this->Shop->primaryKey => $id);
		$this->set('shop', $this->Shop->find('first', compact('conditions')));
	}


	public function add($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->autoRender = false;

			if (!isset($this->request->data["Shop"]["id"])) {
				$this->Shop->create();
				$this->request->data["Shop"]["state"] 			= 0;
			}

			$this->request->data["Shop"]["products_lists"] 	= implode(",", $this->request->data["Shop"]["products_lists"]);

			if ($this->Shop->save($this->request->data)) {
				$shop_id = $this->Shop->id;
				if(!isset($this->request->data["Shop"]["id"])){
					foreach ($this->request->data["ShopReference"] as $key => $value) {
						$value["shop_id"] = $shop_id;
						$this->Shop->ShopReference->create();
						$this->Shop->ShopReference->save($value);
					}
					$this->createUserAdmin($this->request->data,$shop_id);
				}
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}

		}
		$users = $this->Shop->User->find('list',["conditions" => ["User.role" => [8]]]);
		$this->set(compact('users'));
	}

	private function createUserAdmin($data,$shop_id){
		$userInfo = ["User" => [
			"email" => $data["Shop"]["email"],
			"name"  => $data["Shop"]["name_admin"],
			"password" => $data["Shop"]["identification_admin"],
			"shop_id"  => $shop_id,
			"role" 	   => 4,
			"state"    => 0
		]];

		$this->Shop->User->create();
		if($this->Shop->User->save($userInfo)){

			$user_id   = $this->Shop->User->id;
			$varsEmail = [
				"plan" 		=> Configure::read("PLANES.".$data["Shop"]["plan"]),
				"total" 	=> $data["Shop"]["payment_total"],
				"commerces" => $data["Shop"]["number_commerces"],
				"name" 		=> $data["Shop"]["social_reason"],
				"name_user" => $data["Shop"]["name_admin"],
				"email"     => $userInfo["User"]["email"],
				"dni"  		=> $data["Shop"]["identification_admin"],
 			];

			$shop = $this->Shop->find("first",["conditions" => ["Shop.id" => $shop_id], "recursive" => -1,"fields" => ["Shop.id", "Shop.user_id"] ]);

			$shop["Shop"]["user_id"] = $user_id;
			$this->Shop->save($shop);

			$options = [
				"subject" 	=> "Bienvenido a ZÍRO",
				"to"   		=> $userInfo["User"]["email"],
				"vars" 	    => $varsEmail,
				"template"	=> "new_user_admin",
			];

			$this->sendMail($options);
		}
	}

	public function change_state($id){
		$this->autoRender = false;
		$idShop = $this->decrypt($id);
		if (!$this->Shop->exists($idShop)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$shop = $this->Shop->findById($idShop);

		$data = array(
			"ShopPayment" => [
				"shop_id"			  => $shop["Shop"]["id"],
				"date" 				  => $shop["Shop"]["created"],
				"outstanding_balance" => 0,
				"state" 			  => 1,
				"payment_value"		  => $shop["Shop"]["payment_total"],
				"payment_date"		  => date("Y-m-d H:i:s")
			]
		);

		$this->Shop->ShopPayment->create();
		$this->Shop->ShopPayment->save($data);

		$shop["Shop"]["state"] = 1;
		$shop["User"]["state"] = 1;

		unset($shop["User"]["password"]);
		$this->Shop->save($shop["Shop"]);
		$this->Shop->User->save($shop["User"]);

		$options = [
			"subject" 	=> "Usuario activado correctamente",
			"to"   		=> $shop["User"]["email"],
			"vars" 	    => [],
			"template"	=> "user_active_shop",
		];

		$this->sendMail($options);
		$this->Session->setFlash(__('El proveedor se activo correctamente'), 'flash_success');
		$this->redirect(["action"=>"index"]);
	}

	public function verifyNit(){
		$this->autoRender = false;
		if($this->request->is("ajax")){
			$nit 	= $this->request->query["data"]["Shop"]["nit"];
			$allNit = $this->Shop->find("count",["conditions"=>["nit"=>$nit]]);
			if($allNit == 0 ){
				header("HTTP/1.1 200 Ok");
			}else{
				throw new NotFoundException(__('Página no encontrada'));
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Shop->id = $id;
		if (!$this->Shop->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Shop->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Shop.' . $this->Shop->primaryKey => $id);
			$this->request->data = $this->Shop->find('first', compact('conditions'));
		}
		$users = $this->Shop->User->find('list',["conditions" => ["User.role" => [8]]]);
		$this->set(compact('users'));
	}
}
