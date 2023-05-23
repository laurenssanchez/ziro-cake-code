<?php
App::uses('AppController', 'Controller');
/**
 * Customers Controller
 *
 * @property Customer $Customer
 * @property PaginatorComponent $Paginator
 */
class CustomersController extends AppController {

	public $components = array('Paginator');

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('validate_setp_one');
	}

	public function validate_setp_one(){
		$this->autoRender = false;
		var_dump($this->request->data);
		die;
	}

	public function edit_info(){
		$this->autoRender = false;
		$this->loadModel("NotesCustomer");
		$this->loadModel("User");

		$this->Customer->save($this->request->data["Customer"]);
		$this->Customer->CustomersPhone->save($this->request->data["CustomersPhone"]);
		$this->Customer->CustomersAddress->save($this->request->data["CustomersAddress"]);
		$this->Customer->CustomersReference->save($this->request->data["CustomersReference"][0]);
		$this->Customer->CustomersReference->save($this->request->data["CustomersReference"][1]);
		$this->Customer->CustomersReference->save($this->request->data["CustomersReference"][2]);

		$dataNote = [
			"NotesCustomer" => [
				"id" => null,
				"user_id" => AuthComponent::user("id"),
				"note" => "Se edito la información del usuario",
				"credits_request_id" => $this->request->data["id"]
			]
		];

		$this->NotesCustomer->create();
		$this->NotesCustomer->save($dataNote);

		if(!empty($this->request->data["Customer"]["email"])){
			$this->User->recursive = -1;
			$user = $this->User->findByCustomerId($this->request->data["Customer"]["id"]);
			if (!empty($user)) {				
				$user["User"]["email"] = $this->request->data["Customer"]["email"];
				$this->User->save($user);
			}
		}


	}

	public function index() {
		$conditions = $this->Customer->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->Customer->recursive = 0;
		$this->Paginator->settings = array('order'=>array('Customer.modified'=>'DESC'));
		$customers = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('customers'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->Customer->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->Customer->recursive = 0;
		$conditions = array('Customer.' . $this->Customer->primaryKey => $id);
		$this->set('customer', $this->Customer->find('first', compact('conditions')));
	}

	public function get_data_customers(){
		$this->layout = false;
		$this->Customer->recursive = 2;
		$customer = $this->Customer->findById($this->decrypt($this->request->data["customer"]));
		if(isset($this->request->data["request"])){
			$this->loadModel("CreditsRequest");
			$request = $this->CreditsRequest->findById($this->decrypt($this->request->data["request"]));
			$this->set("request",$request);

			$this->loadModel("NotesCustomer");

			$notes = $this->NotesCustomer->findAllByCreditsRequestId($this->decrypt($this->request->data["request"]));
			$this->set("notes",$notes);

		}
		$this->loadModel("Credit");
		$conditions 	= ["Credit.credits_request_id != " => 0,"Credit.customer_id" => $this->decrypt($this->request->data["customer"])];
		$credits 		= $this->Credit->find("all",["conditions"=>$conditions]);

		if(!empty($credits)){
			foreach ($credits as $key => $value) {
				$credits[$key]["saldos"] = $this->calculateTotales($value["Credit"],$value["CreditsPlan"]);
				$totalDebts 			 = 0;
				foreach ($value["CreditsPlan"] as $keyData => $valueData) {
					if(!is_null($valueData["date_debt"])){
						$totalDebts++;
					}
				}

				$credits[$key]["debts"] = $totalDebts;
			}
		}

		$this->set("customer",$customer);
		$this->set("credits",$credits);
	}

	private function calculateTotales($credit,$quotes){
		$this->loadModel("Credit");
		$quotes 		= $this->Credit->CreditsPlan->getDataQuotes($quotes,$credit["last_payment_date"],$credit["debt_rate"],$credit["id"]);

		$capitalTotal 	= $othersValue = $interesValue = 0;

		$totalDebt 		= 0;	
		$totalCredit 	= 0;
		$totalQuoteDebt	= 0;

		foreach ($quotes as $keyQt => $valueQt) {

			$capitalTotal = floatval($valueQt["capital_value"]-$valueQt["capital_payment"]);
			$othersValue  = floatval($valueQt["others_value"]-$valueQt["others_payment"]);
			$othersValue  = floatval($valueQt["interest_value"]-$valueQt["interest_payment"]);

			$totalCredit+=floatVal($capitalTotal+$othersValue+$interesValue+$valueQt["debt_value"]+$valueQt["debt_honor"]);
			

			if($valueQt["debt_value"] > 0 || $valueQt["debt_honor"] > 0){
				$totalQuoteDebt++;
				$totalDebt += floatVal($valueQt["debt_value"]+$valueQt["debt_honor"]);
			}
		}		

		return ["saldo" => $totalCredit,"debt" => $totalDebt,"totalDebt" => $totalQuoteDebt];
		
	}

	public function add_note(){
		$this->autoRender = false;
		$this->loadModel("NotesCustomer");
		if(!empty($this->request->data["NotesCustomer"]["note"])){
			$this->NotesCustomer->create();
			$this->NotesCustomer->save($this->request->data);	
		}
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->Customer->create();
			if ($this->Customer->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$users = $this->Customer->User->find('list');

		$this->set(compact('users'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->Customer->id = $id;
		if (!$this->Customer->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Customer->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Customer.' . $this->Customer->primaryKey => $id);
			$this->request->data = $this->Customer->find('first', compact('conditions'));
		}
		$users = $this->Customer->User->find('list');
		$this->set(compact('users'));
	}
}
