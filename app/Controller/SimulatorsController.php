<?php
App::uses('AppController', 'Controller');
/**
 * Simulators Controller
 *
 * @property Simulator $Simulator
 * @property PaginatorComponent $Paginator
 */
class SimulatorsController extends AppController {

	public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('simulate');
    }

	public $components = array('Paginator');

	public function index() {
		$conditions = $this->Simulator->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Simulator->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Simulator.modified'=>'DESC'));
		$simulators = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('simulators'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Simulator->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Simulator->recursive = 0;
		$conditions = array('Simulator.' . $this->Simulator->primaryKey => $id);
		$this->set('simulator', $this->Simulator->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Simulator->create();
			if ($this->Simulator->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsLines = $this->Simulator->CreditsLine->find('list');
		$this->set(compact('creditsLines'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Simulator->id = $id;
		if (!$this->Simulator->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Simulator->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Simulator.' . $this->Simulator->primaryKey => $id);
			$this->request->data = $this->Simulator->find('first', compact('conditions'));
		}
		$creditsLines = $this->Simulator->CreditsLine->find('list');
		$this->set(compact('creditsLines'));
	}

	public function simulate($id) {
		$this->layout = false;
		$this->layout = "layout-home";
		$simulator = $this->Simulator->findById($this->decrypt(base64_decode($id)));

		if ($simulator["Simulator"]["state"] == 0) {
			throw new NotFoundException(__('Página no encontrada'));
		}

		$this->loadModel("CreditsLinesDetail");
		$this->loadModel("CreditsLine");

	    $creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = " . $simulator["Simulator"]["credits_line_id"]);

	    $valorMini = 0;
	    $Valormax = 0;
	    $minMonth = 0;
	    $maxMonth = 0;

	    if ($this->request->is("post")) {
	    	$this->autoRender = false;
	    	$this->loadModel("Customer");
	    	$this->Customer->create();
	        $existCustomer = $this->Customer->field("identification",["identification"=>$this->request->data["Customer"]["identification"],"type" => 1]);
	        $emailExists = $this->Customer->User->field("email",["email"=>$this->request->data["Customer"]["email"]]);

	        if($emailExists != false){
	          return "El correo eléctronico ya está registrado";
	        }elseif($existCustomer != false){
	          return "La cédula ya está registrada";
	        }
	        $customer = $this->request->data["Customer"];
	        if($this->Customer->save($customer)){
            $customerID = $this->Customer->id;

            $this->Customer->CustomersPhone->deleteAll(array('CustomersPhone.customer_id' => $customerID), false);
            $this->Customer->CustomersAddress->deleteAll(array('CustomersAddress.customer_id' => $customerID), false);
            $this->Customer->CustomersReference->deleteAll(array('CustomersReference.customer_id' => $customerID), false);

            $data = $this->request->data;

            if(!empty($data["CustomersReference"])){
              foreach ($data["CustomersReference"] as $key => $value) {
                $value["customer_id"] = $customerID;
                $this->Customer->CustomersReference->create();              
                $this->Customer->CustomersReference->save($value);
              }
            }

            if(!empty($data["CustomersAddress"])){
              $data["CustomersAddress"]["customer_id"] = $customerID;
              $this->Customer->CustomersAddress->create();              
              $this->Customer->CustomersAddress->save($data["CustomersAddress"]);
            }

            if(!empty($data["CustomersPhone"])){
              foreach ($data["CustomersPhone"] as $key => $value) {
                $value["customer_id"] = $customerID;
                if(!empty($value["phone_number"])){
                  $this->Customer->CustomersPhone->create();              
                  $this->Customer->CustomersPhone->save($value);
                }
              }
            }

            $this->loadModel("ShopCommerce");
            $this->loadModel("CreditsRequest");
            $this->loadModel("CreditsLine");
            
            $shop_commerce_id = $this->ShopCommerce->field("id",["code" => $simulator["Simulator"]["commerce_code"] ]);

            $dataRequest = [
              "CreditsRequest" => [
                "customer_id" => $customerID,
                "request_value" => $this->request->data["priceValue"],
                "request_number" => $this->request->data["couteValue"],
                "credits_line_id" => $simulator["Simulator"]["credits_line_id"],
                "shop_commerce_id" => $shop_commerce_id,
                "request_type" => $this->request->data["frecuency"],
                "simulator_id" => $simulator["Simulator"]["id"],
              ]
            ];
            $this->CreditsRequest->create();
            if ($this->CreditsRequest->save($dataRequest)) {
            	$registerUser = $this->create_login_user($this->request->data,$customerID);
                $this->Session->setFlash(__('Solicitud creada correctamente'), 'flash_success');
                return "final";
            }
          }else{
            return "Error al guardar, por favor inténtelo de nuevo";
          }

	    }

	    $data = json_encode($creditLineDetail);

		foreach ($creditLineDetail as $key => $value) {
			if ($valorMini == 0) {
			  $valorMini = $value["credits_lines_details"]["min_value"];

			} else if ($value["credits_lines_details"]["min_value"] <= $valorMini) {
			  $valorMini = $value["credits_lines_details"]["min_value"];

			}

			if ($Valormax == 0) {
			  $Valormax = $value["credits_lines_details"]["max_value"];

			} else if ($value["credits_lines_details"]["max_value"] >= $valorMini) {
			  $Valormax = $value["credits_lines_details"]["max_value"];

			}

			if ($minMonth == 0) {
			  $minMonth = $value["credits_lines_details"]["min_month"];

			} else if ($value["credits_lines_details"]["max_value"] <= $minMonth) {
			  $minMonth = $value["credits_lines_details"]["min_month"];

			}

			if ($maxMonth == 0) {
			  $maxMonth = $value["credits_lines_details"]["max_month"];

			} else if ($value["credits_lines_details"]["max_month"] >= $maxMonth) {
			  $maxMonth = $value["credits_lines_details"]["max_month"];

			}
	      }

	      //$sayHello = $valorMini;
	      $this->set(compact("valorMini", "Valormax", "minMonth", "maxMonth","data","simulator"));
	}


	private function create_login_user($data,$customer_id){
		$this->loadModel("Customer");
	    $dataUser    = array("User" => [
	        "email" => $data["Customer"]["email"],
	        "name"  => $data["Customer"]["identification"],
	        "password"     => $data["Customer"]["password"],
	        "customer_id"  => $customer_id,
	        "role"         => 5     
	    ]);

	    $this->Customer->User->create();

	    if($this->Customer->User->save($dataUser)){
	      $user_id = $this->Customer->User->id;      
	      $this->Customer->User->save(["User"=>["id" => $user_id,"customer_new_request" => 2]]);

	      $customer["Customer"]["id"]              = $customer_id;
	      $customer = $data["Customer"]["user_id"] = $user_id;
	      $this->Customer->save($customer);

	    }
	  }

}
