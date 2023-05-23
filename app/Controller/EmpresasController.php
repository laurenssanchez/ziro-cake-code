<?php
App::uses('AppController', 'Controller');
/**
 * Shops Controller
 *
 * @property Shop $Shop
 * @property PaginatorComponent $Paginator
 */
class EmpresasController extends AppController {


	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow( 'verifyNit');
	}




	public function index() {
		$conditions = $this->Empresa->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Empresa->unBindModel([
			"belongsTo" => ["Adviser"]
		]);
		$this->Empresa->recursive = 2;
		$this->Paginator->settings = array('order'=>array('Shop.modified'=>'DESC'));
		$shops = $this->Paginator->paginate(null, $conditions);

		$this->set(compact('shops'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Empresa->recursive = 1;
		$conditions = array('Empresa.' . $this->Empresa->primaryKey => $id);
		$this->set('shop', $this->Empresa->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->autoRender = false;

			if (!isset($this->request->data["Empresa"]["id"])) {
				$this->Empresa->create();
				$this->request->data["Empresa"]["state"] 			= 0;
			}

// 			$this->request->data["Empresa"]["products_lists"] 	= implode(",", $this->request->data["Empresa"]["products_lists"]);

			if ($this->Empresa->save($this->request->data)) {
				$shop_id = $this->Empresa->id;
				if(!isset($this->request->data["Empresa"]["id"])){
					foreach ($this->request->data["EmpresaReference"] as $key => $value) {
						$value["shop_id"] = $shop_id;
						$this->Empresa->EmpresaReference->create();
						$this->Empresa->EmpresaReference->save($value);
					}
					$this->createUserAdmin($this->request->data,$shop_id);
				}
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}

		}
		$users = $this->Empresa->User->find('list',["conditions" => ["User.role" => [8]]]);
		$this->set(compact('users'));
	}

	private function createUserAdmin($data,$shop_id){
		$userInfo = ["User" => [
			"email" => $data["Empresa"]["email"],
			"name"  => $data["Empresa"]["name_admin"],
			"password" => $data["Empresa"]["identification_admin"],
			"shop_id"  => $shop_id,
			"role" 	   => 4,
			"state"    => 0
		]];

		$this->Empresa->User->create();
		if($this->Empresa->User->save($userInfo)){

			$user_id   = $this->Empresa->User->id;
			$varsEmail = [
				"plan" 		=> Configure::read("PLANES.".$data["Empresa"]["plan"]),
				"total" 	=> $data["Empresa"]["payment_total"],
				"commerces" => $data["Empresa"]["number_commerces"],
				"name" 		=> $data["Empresa"]["social_reason"],
				"name_user" => $data["Empresa"]["name_admin"],
				"email"     => $userInfo["User"]["email"],
				"dni"  		=> $data["Empresa"]["identification_admin"],
 			];

			$shop = $this->Empresa->find("first",["conditions" => ["Shop.id" => $shop_id], "recursive" => -1,"fields" => ["Shop.id", "Shop.user_id"] ]);

			$shop["Empresa"]["user_id"] = $user_id;
			$this->Empresa->save($shop);

			$options = [
				"subject" 	=> "Bienvenido a Zíro",
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
		if (!$this->Empresa->exists($idShop)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$shop = $this->Empresa->findById($idShop);

		$shop["Empresa"]["state"] = 1;
		$this->Empresa->save($shop["Empresa"]);

		$this->Session->setFlash(__('El proveedor se activo correctamente'), 'flash_success');
		$this->redirect(["action"=>"index"]);
	}

	public function verifyNit(){
		$this->autoRender = false;
		if($this->request->is("ajax")){
			$nit 	= $this->request->query["data"]["Empresa"]["nit"];
			$allNit = $this->Empresa->find("count",["conditions"=>["nit"=>$nit]]);
			if($allNit == 0 ){
				header("HTTP/1.1 200 Ok");
			}else{
				throw new NotFoundException(__('Página no encontrada'));
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Empresa->id = $id;
		if (!$this->Empresa->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {

			$this->request->data["Empresa"]["products_lists"] 	= implode(",", $this->request->data["Empresa"]["products_lists"]);

			if ($this->request->data["Empresa"]["id"]) {

				$data = array(
					'id'=> $this->request->data["Empresa"]["id"],
					'nit'=> $this->request->data["Empresa"]["nit"],
					'social_reason'=> $this->request->data["Empresa"]["social_reason"],
					'guild'=> $this->request->data["Empresa"]["guild"],
					'department'=> $this->request->data["Empresa"]["department"],
					'city'=> $this->request->data["Empresa"]["city"],
					'address'=> $this->request->data["Empresa"]["address"],
					'phone'=> $this->request->data["Empresa"]["phone"],
					'identification_admin'=> $this->request->data["Empresa"]["identification_admin"],
					'name_admin'=> $this->request->data["Empresa"]["name_admin"],
					'email'=> $this->request->data["Empresa"]["email"],
					'cellpone_admin'=> $this->request->data["Empresa"]["cellpone_admin"],
					'identification_account'=> $this->request->data["Empresa"]["identification_account"],
					'account_type'=> $this->request->data["Empresa"]["account_type"],
					'account_bank'=> $this->request->data["Empresa"]["account_bank"],
					'account_number'=> $this->request->data["Empresa"]["account_number"],
					'services_list'=> $this->request->data["Empresa"]["services_list"],
					'products_lists'=> $this->request->data["Empresa"]["products_lists"],
					'adviser'=> $this->request->data["Empresa"]["adviser"],
					'plan'=> $this->request->data["Empresa"]["plan"],
					'payment_type'=> $this->request->data["Empresa"]["payment_type"],
					'number_commerces'=> $this->request->data["Empresa"]["number_commerces"],
					'cost_min'=> $this->request->data["Empresa"]["cost_min"],
					'cost_max'=> $this->request->data["Empresa"]["cost_max"],
					//'payment_total'=> $this->request->data["Empresa"]["payment_total"],
					//'payment_total'=> 0,
					'chamber_commerce_file'=> $this->request->data["Empresa"]["chamber_commerce_file"],
					'rut_file'=> $this->request->data["Empresa"]["rut_file"],
					'image_admin'=> $this->request->data["Empresa"]["image_admin"],
					'identification_up_file'=> $this->request->data["Empresa"]["identification_up_file"],
					'identification_down_file'=> $this->request->data["Empresa"]["identification_down_file"],
				);

				$this->Empresa->id = $this->request->data["Empresa"]["id"];
				//$this->Empresa->set($this->request->data["Empresa"]);
				$this->Empresa->set($data);
				$this->Empresa->save();

				$shop_id = $this->Empresa->id;
				if(!isset($this->request->data["Empresa"]["id"])){
					foreach ($this->request->data["EmpresaReference"] as $key => $value) {
						$value["shop_id"] = $shop_id;
						$this->Empresa->EmpresaReference->create();
						$this->Empresa->EmpresaReference->save($value);
					}
					$this->createUserAdmin($this->request->data,$shop_id);
				}

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));

			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}

		} else {
			$conditions = array('Empresa.' . $this->Empresa->primaryKey => $id);
			$this->request->data = $this->Empresa->find('first', compact('conditions'));
		}
		$users = $this->Empresa->User->find('list',["conditions" => ["User.role" => [8]]]);
		$this->set(compact('users'));
	}
}
