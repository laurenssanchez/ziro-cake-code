<?php
App::uses('AppController', 'Controller');
/**
 * Requests Controller
 *
 * @property Request $Request
 * @property PaginatorComponent $Paginator
 */
class RequestsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('payment_commerce_search','payment_commerce_credishop');
	}

	public $components = array('Paginator');

	public function payment_commerce_credishop(){
		$this->autoRender = false;

		$requestData = $this->request->data;
		if(isset($requestData["x_signature"])){

			$p_cust_id_cliente	=	Configure::read("PAYMENT.id");
            $p_key				= 	Configure::read("PAYMENT.p_key");

            $x_ref_payco		=	$requestData['x_ref_payco'];
            $x_transaction_id	=	$requestData['x_transaction_id'];
            $x_amount			=	$requestData['x_amount'];
            $x_currency_code	=	$requestData['x_currency_code'];
            $x_signature		=	$requestData['x_signature'];
            $continue 			= 	true;

            if(!Configure::read("PAYMENT.test")){
            	$signature=hash('sha256',
	               $p_cust_id_cliente.'^'
	              .$p_key.'^'
	              .$x_ref_payco.'^'
	              .$x_transaction_id.'^'
	              .$x_amount.'^'
	              .$x_currency_code
	            );

	            $this->log($signature,"debug");
	            $this->log($x_signature,"debug");

            	if($signature != $x_signature){
            		$continue = false;
            	}
            }

            if($requestData["x_cod_response"] == 1 && $continue){

            	$detail = ["RequestsDetail" => ["request_id" =>$requestData["x_extra1"],"state_payment" => $requestData["x_cod_response"], "value"  => $requestData["x_amount_ok"], "response" => json_encode($requestData) ] ];

            	$this->loadModel("RequestsDetail");
            	$this->RequestsDetail->create();
            	$this->RequestsDetail->save($detail);


            	$this->Request->recusive = -1;
            	$request = $this->Request->findById($requestData["x_extra1"]);

            	$request["Request"]["requests_detail_id"] = $this->RequestsDetail->id;
            	$request["Request"]["state"] 			  = 1;
            	$request["Request"]["date_payment"]       = date("Y-m-d H:i:s");

            	$this->Request->save($request);

            }
		}else{
			$this->log(json_encode($this->request->data),"debug");
		}
		$this->log(json_encode($this->request->data),"debug");
		$this->log("final", "debug");


		$this->log($this->request->data,"debug");
	}

	public function payment_commerce_search(){
		$this->autoRender = false;

		$this->Request->recursive = -1;
		$code = $this->Request->findByCodeAndState($this->request->data["codigo"],0);

		if (empty($code)) {
			return 10;
		}

		$datos = [
			"name" => "Pago Crédito",
			"description" => "Pago a proveedor código:". $this->request->data["codigo"] ,
			"invoice" => date("YmdHis"),
			"currency" => "cop",
			"amount" => $code["Request"]["value"],
			"tax_base" => "0",
	        "tax" => "0",
	        "country" => "co",
	        "lang" => "es",
	        "external" => false,
	        "extra1" => $code["Request"]["id"],
	        "confirmation" => Router::url("/",true)."payment_commerce_credishop",
          	"response" => Router::url("/",true)."payment_web_credishop_response",
			"number_doc_billing" => $code["Request"]["identification"]
		];

		return json_encode(["configuration" => Configure::read("PAYMENT"),"datos" => $datos]);
	}

	public function index() {
		$conditions = $this->Request->buildConditions($this->request->query);

		if (AuthComponent::user("role") == 4) {
			$conditions["Request.shop_commerce_id"] = $this->Request->ShopCommerce->find('list',["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")],"fields" => ["id","id"] ]);
		}else{
			$conditions["Request.shop_commerce_id"] = AuthComponent::user("shop_commerce_id");

		}

		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Request->recursive = 1;
		$this->Paginator->settings = array('order'=>array('Request.modified'=>'DESC'));
		$requests = $this->Paginator->paginate(null, $conditions);

		$requestsNoPayment = $this->Request->find("all",["conditions" => ["Request.state" => 1,"Request.requests_payment_id" => NULL,"Request.shop_commerce_id" => $conditions["Request.shop_commerce_id"] ],"fields" => ["SUM(Request.value) total","ShopCommerce.*"],"group" => ["Request.shop_commerce_id"],"recursive" => 2 ]);

		$this->set(compact('requests','requestsNoPayment'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Request->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Request->recursive = 0;
		$conditions = array('Request.' . $this->Request->primaryKey => $id);
		$this->set('request', $this->Request->find('first', compact('conditions')));
	}

	public function view_recipe($id = null) {
		$this->layout = false;
		$id = $this->decrypt($id);
		if (!$this->Request->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Request->recursive = 2;
		$conditions = array('Request.' . $this->Request->primaryKey => $id);
		$request =  $this->Request->find('first', compact('conditions'));

		// echo "<pre>";
		// var_dump($request);

		$this->set("request",$request);

	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Request->create();
			$this->request->data["Request"]["code"] = $this->Request->generate();
			if ($this->Request->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'view',$this->encrypt($this->Request->id)));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		if (AuthComponent::user("role") == 4) {
			$shopCommerces = $this->Request->ShopCommerce->find('list',["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")] ]);
		}else{
			$shopCommerces = $this->Request->ShopCommerce->find('list',["conditions" => ["ShopCommerce.id" => AuthComponent::user("shop_commerce_id") ] ]);

		}
		$this->set(compact('shopCommerces', 'requestsDetails', 'requestsPayments', 'users', 'credits', 'payments'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Request->id = $id;
		if (!$this->Request->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Request->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Request.' . $this->Request->primaryKey => $id);
			$recursive = -1;
			$this->request->data = $this->Request->find('first', compact('conditions','recursive'));
		}
		if (AuthComponent::user("role") == 4) {
			$shopCommerces = $this->Request->ShopCommerce->find('list',["conditions" => ["ShopCommerce.shop_id" => AuthComponent::user("shop_id")] ]);
		}else{
			$shopCommerces = $this->Request->ShopCommerce->find('list',["conditions" => ["ShopCommerce.id" => AuthComponent::user("shop_commerce_id") ] ]);

		}
		$this->set(compact('shopCommerces', 'requestsDetails', 'requestsPayments', 'users', 'credits', 'payments'));
	}
}
