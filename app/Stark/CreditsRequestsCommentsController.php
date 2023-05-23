<?php
App::uses('AppController', 'Controller');
/**
 * CreditsRequestsComments Controller
 *
 * @property CreditsRequestsComment $CreditsRequestsComment
 * @property PaginatorComponent $Paginator
 */
class CreditsRequestsCommentsController extends AppController {


	public $components = array('Paginator');

	public function index() {
		$conditions = $this->CreditsRequestsComment->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CreditsRequestsComment->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CreditsRequestsComment.modified'=>'DESC'));
		$creditsRequestsComments = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('creditsRequestsComments'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CreditsRequestsComment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CreditsRequestsComment->recursive = 0;
		$conditions = array('CreditsRequestsComment.' . $this->CreditsRequestsComment->primaryKey => $id);
		$this->set('creditsRequestsComment', $this->CreditsRequestsComment->find('first', compact('conditions')));
	}

	public function view_comment(){
		$this->layout = false;
		$request = $this->decrypt($this->request->data["request"]);	
		$allComments = $this->CreditsRequestsComment->find("all",["recursive" => -1, "conditions" => ["CreditsRequestsComment.credits_request_id"=>$request]]);

		$this->set(compact("request","allComments"));
	}

	public function return_to_approved(){
		$this->autoRender = false;
		if($this->request->is("ajax") && $this->request->is("post")){
			$this->CreditsRequestsComment->create();
			$request = $this->decrypt($this->request->data["id"]);
			$dataComment = [
				"CreditsRequestsComment" => [
					"type" => "Devolución de crédito",
					"user_id" => AuthComponent::user("id"),
					"credits_request_id" => $request,
					"comment" => $this->request->data["reason"],
				]
			];
			if($this->CreditsRequestsComment->save($dataComment)){

				$this->CreditsRequestsComment->CreditsRequest->recursive = -1;
				$requestData = $this->CreditsRequestsComment->CreditsRequest->findById($request);

				$requestData["CreditsRequest"]["state"] 			= 3;
				$requestData["CreditsRequest"]["date_disbursed"] 	= null;
				$requestData["CreditsRequest"]["user_disbursed"] 	= null;
				$requestData["CreditsRequest"]["value_disbursed"] 	= null;
				$requestData["CreditsRequest"]["reason_reject"] 	= null;
				$requestData["CreditsRequest"]["credit_id"] 		= null;
				$requestData["CreditsRequest"]["returned"] 			= 1;
				$this->CreditsRequestsComment->CreditsRequest->save($requestData);

				$this->loadModel("Credit");
				$this->Credit->recursive = -1;

				$credit = $this->Credit->findByCreditsRequestId($request);

				$credit["Credit"]["credits_request_id"] = 0;
				$credit["Credit"]["request_ant"] = $request;

				$this->Credit->save($credit);

				$this->loadModel("Disbursement");
				$disbursment = $this->Disbursement->findByCreditId($credit["Credit"]["id"]);
				$this->Disbursement->delete($disbursment["Disbursement"]["id"]);

				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');


			}else{
				$this->Session->setFlash(__('No se pudo guardar'), 'flash_error');
			}
		}
	}

	public function return_request(){
		$this->autoRender = false;
		if($this->request->is("ajax") && $this->request->is("post")){
			$this->CreditsRequestsComment->create();
			$request = $this->decrypt($this->request->data["id"]);
			$dataComment = [
				"CreditsRequestsComment" => [
					"type" => "Devolución de crédito",
					"user_id" => AuthComponent::user("id"),
					"credits_request_id" => $request,
					"comment" => $this->request->data["reason"]
				]
			];
			if($this->CreditsRequestsComment->save($dataComment)){
				$this->CreditsRequestsComment->CreditsRequest->recursive = -1;
				$requestData = $this->CreditsRequestsComment->CreditsRequest->findById($request);
				$requestData["CreditsRequest"]["state"] = 2;
				$requestData["CreditsRequest"]["value_approve"] = null;
				$requestData["CreditsRequest"]["date_disbursed"] = null;
				$requestData["CreditsRequest"]["number_approve"] = null;
				$requestData["CreditsRequest"]["user_disbursed"] = null;
				$requestData["CreditsRequest"]["reason_reject"] = null;
				$this->CreditsRequestsComment->CreditsRequest->save($requestData);
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
			}else{
				$this->Session->setFlash(__('No se pudo guardar'), 'flash_error');
			}
		}
	}

	public function save_comment(){
		$this->autoRender = false;
		$credits_request_id = $this->request->data["CreditsRequestsComment"]["credits_request_id"];
		if($this->request->is("ajax") && $this->request->is("post")){
			$this->CreditsRequestsComment->create();
			if ($this->CreditsRequestsComment->save($this->request->data)) {

				$allComments = $this->CreditsRequestsComment->find("all",["recursive" => -1, "conditions" => ["CreditsRequestsComment.credits_request_id"=>$credits_request_id]]);
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->CreditsRequestsComment->CreditsRequest->recursive = -1;
				$requestData = $this->CreditsRequestsComment->CreditsRequest->findById($credits_request_id);
				if(count($allComments) >= 1 && $requestData["CreditsRequest"]["state"] <= 2 ){
					$requestData["CreditsRequest"]["state"] = 2;
					$this->CreditsRequestsComment->CreditsRequest->save($requestData);
					return 1;
				}
				return $this->encrypt($credits_request_id);
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
				return 1;
			}
		}

	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CreditsRequestsComment->create();
			if ($this->CreditsRequestsComment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
		$creditsRequests = $this->CreditsRequestsComment->CreditsRequest->find('list');
		$this->set(compact('creditsRequests'));
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CreditsRequestsComment->id = $id;
		if (!$this->CreditsRequestsComment->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CreditsRequestsComment->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CreditsRequestsComment.' . $this->CreditsRequestsComment->primaryKey => $id);
			$this->request->data = $this->CreditsRequestsComment->find('first', compact('conditions'));
		}
		$creditsRequests = $this->CreditsRequestsComment->CreditsRequest->find('list');
		$this->set(compact('creditsRequests'));
	}
}
