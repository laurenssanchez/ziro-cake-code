<?php
App::uses('AppController', 'Controller');
/**
 * CreditsPlans Controller
 *
 * @property CreditsPlan $CreditsPlan
 * @property PaginatorComponent $Paginator
 */
class CreditsPlansController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

	public function index() {
		$conditions = $this->CreditsPlan->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CreditsPlan->recursive = 2;

		$this->Paginator->settings = array('order'=>array('CreditsPlan.modified'=>'DESC'));
		$creditsPlans = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('creditsPlans'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CreditsPlan->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CreditsPlan->recursive = 0;
		$conditions = array('CreditsPlan.' . $this->CreditsPlan->primaryKey => $id);
		$this->set('creditsPlan', $this->CreditsPlan->find('first', compact('conditions')));
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CreditsPlan->create();
			if ($this->CreditsPlan->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$credits = $this->CreditsPlan->Credit->find('list');
		$this->set(compact('credits'));
	}

	public function admin(){
		$this->layout = false;

		$quote 		= $this->decrypt($this->request->data["quote"]);
		$credit 	= $this->decrypt($this->request->data["credit"]);
		$tab 		= $this->request->data["tab"];

		$this->CreditsPlan->Credit->recursive = -1;
		$credito 	= $this->CreditsPlan->Credit->findById($credit);

		$credito["Credit"]["user_id"] = AuthComponent::user("id");
		$credito["Credit"]["admin_date"] = date("Y-m-d H:i:s");

		$this->CreditsPlan->Credit->save($credito);

		if(AuthComponent::user("role") == 11){
			$quoteInfo  = $this->CreditsPlan->getQuotesJuridico($quote);
		}else {
			$quoteInfo  = $this->CreditsPlan->getQuotesCobranzas($quote);
		}

		$this->CreditsPlan->setCobrosUnions();

		$type 		  = AuthComponent::user("role") == 11 ? 1 : 0;
		$commitments  = $this->CreditsPlan->Commitment->findAllByCreditsPlanIdAndType($quote,$type);
		$notes  	  = $this->CreditsPlan->Note->findAllByCreditsPlanIdAndType($quote,$type);
		$history  	  = $this->CreditsPlan->History->findAllByCreditsPlanIdAndType($quote,$type);

		// var_dump($commitments);

		$this->set("quote",$quoteInfo);
		$this->set("tab",$tab);
		$this->set("notes",$notes);
		$this->set("history",$history);
		$this->set("commitments",$commitments);

	}

	public function form_data(){
		$this->layout = false;
		$this->set("id",$this->decrypt($this->request->data["id"]));
	}

	public function add_commitment(){
		$this->autoRender = false;
		$this->loadModel("Commitment");
		$this->Commitment->save($this->request->data);

		$this->loadModel("History");

		$type = AuthComponent::user("role") == 11 ? 1 : 0;

		$dataNote = [ "History" => [
			"credits_plan_id" => $this->request->data["Commitment"]["credits_plan_id"],
			"user_id" 		  => AuthComponent::user("id"),
			"type" 		  	  => $type,
			"action"		  => "Se creó un compromiso para el día: ".$this->request->data["Commitment"]["deadline"],	
		]  ];

		$this->History->create();
		$this->History->save($dataNote);
	}

	public function change_state(){
		$this->autoRender = false;
		$this->loadModel("Commitment");

		$commitment = $this->Commitment->field("credits_plan_id",["id"=> $this->decrypt($this->request->data["id"]) ]);
		$deadline = $this->Commitment->field("deadline",["id"=> $this->decrypt($this->request->data["id"]) ]);

		$this->request->data["id"] = $this->decrypt($this->request->data["id"]);
		$this->request->data = ["Commitment" => $this->request->data];
		$this->Commitment->save($this->request->data);

		$this->loadModel("History");

		$type = AuthComponent::user("role") == 11 ? 1 : 0;

		$dataNote = [ "History" => [
			"credits_plan_id" => $commitment,
			"user_id" 		  => AuthComponent::user("id"),
			"type" 		  	  => $type,
			"action"		  => "Se cambió de estado un compromiso, para la fecha: ".$deadline,	
		]  ];

		$this->History->create();
		$this->History->save($dataNote);
	}

	public function send_mesage_one(){
		$this->autoRender = false;
		$this->loadModel("Commitment");
		$this->request->data["id"] = $this->decrypt($this->request->data["id"]);

		$this->sendMessageTxt($this->request->data["number"],null,$this->request->data["message"]);

		$this->loadModel("History");

		$type = AuthComponent::user("role") == 11 ? 1 : 0;

		$dataNote = [ "History" => [
			"credits_plan_id" => $this->request->data["id"],
			"user_id" 		  => AuthComponent::user("id"),
			"type" 		  	  => $type,
			"action"		  => "Se envío el mensaje: ".$this->request->data["message"]. "; Al número: ".$this->request->data["number"],	
		]  ];

		$this->History->create();
		$this->History->save($dataNote);
	}

	public function send_mesage_all(){
		$this->autoRender = false;
		$this->loadModel("CustomersPhone");

		$phones = [];
		$msgs 	= [];

		foreach ($this->request->data["dataPhone"] as $key => $value) {
			$phoneNumber = $this->CustomersPhone->field("phone_number",["customer_id" => $value]);
			if(!in_array($phoneNumber,$phones)){
				$phones[] = $phoneNumber;
				$msg = [
					"number" 	=> "57${phoneNumber}",
					"message" 	=> $this->request->data["message"],
					"type" 		=> 1
				];
				$msgs[] = $msg;
			}
		}

		$this->sendMessageAll($msgs);
	}

	public function add_note(){
		$this->autoRender = false;
		$this->loadModel("Note");
		$this->Note->save($this->request->data);

		$this->loadModel("History");

		$type = AuthComponent::user("role") == 11 ? 1 : 0;

		$dataNote = [ "History" => [
			"credits_plan_id" => $this->request->data["Note"]["credits_plan_id"],
			"user_id" 		  => AuthComponent::user("id"),
			"type" 		  	  => $type,
			"action"		  => "Se creó una nota ",	
		]  ];

		$this->History->create();
		$this->History->save($dataNote);
	}

	public function save_note_call(){
		$this->autoRender = false;
		$this->loadModel("History");

		$type = AuthComponent::user("role") == 11 ? 1 : 0;

		$dataNote = [ "History" => [
			"credits_plan_id" => $this->decrypt($this->request->data["quote"]),
			"user_id" 		  => AuthComponent::user("id"),
			"type" 		  	  => $type,	
			"action"	      => "Se realizó llamada al número: ".$this->request->data["number"],	
		]  ];

		$this->History->create();
		$this->History->save($dataNote);

	}
	public function changeJuridico(){
		$this->autoRender = false;
		$this->loadModel("History");

		$id 	= $this->decrypt($this->request->data["id"]);
		$razon  = $this->request->data["message"];

		$this->CreditsPlan->changeJuridico($id);

		$dataNote = [ "History" => [
			"credits_plan_id" => $id,
			"user_id" 		  => AuthComponent::user("id"),
			"type" 		  	  => 1,	
			"action"	      => "Se pasa a jurídico por la siguiente razon: ".$razon,	
		]  ];

		$this->History->create();
		$this->History->save($dataNote);

	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CreditsPlan->id = $id;
		if (!$this->CreditsPlan->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CreditsPlan->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CreditsPlan.' . $this->CreditsPlan->primaryKey => $id);
			$this->request->data = $this->CreditsPlan->find('first', compact('conditions'));
		}
		$credits = $this->CreditsPlan->Credit->find('list');
		$this->set(compact('credits'));
	}
}
