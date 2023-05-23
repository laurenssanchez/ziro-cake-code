<?php
App::uses('AppController', 'Controller');
/**
 * CreditsLines Controller
 *
 * @property CreditsLine $CreditsLine
 * @property PaginatorComponent $Paginator
 */
class CreditsLinesController extends AppController {

/**
 * Components
 *
 * @var array
 */
	public $components = array('Paginator');

public function index() {
		$conditions = $this->CreditsLine->buildConditions($this->request->query);
		$q = isset($this->request->query['q']) ? $this->request->query['q'] : "";
		$this->set("q",$q);
		$this->CreditsLine->recursive = 0;
		$this->Paginator->settings = array('order'=>array('CreditsLine.modified'=>'DESC'));
		$creditsLines = $this->Paginator->paginate(null, $conditions);
		$this->set(compact('creditsLines'));
	}

	public function view($id = null) {
		$id = $this->decrypt($id);
		if (!$this->CreditsLine->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		$this->CreditsLine->recursive = 0;
		$conditions = array('CreditsLine.' . $this->CreditsLine->primaryKey => $id);
		$this->set('creditsLine', $this->CreditsLine->find('first', compact('conditions')));
		$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = ". $id);
		$this->set('creditLineDetail', $creditLineDetail);
	}


	public function add() {
		
		if ($this->request->is('post')) {

			$name = $this->request->data["name"];
			$description = $this->request->data['description'];
			$arrayTotal = json_decode($this->request->data['arrayTotal'], true);

			$this->loadModel("CreditsLine");
			$this->CreditsLine->create();
			
			$creditLine = [
				"CreditsLine" =>
					[
						"name" => $name,
						"description"   => $description,
						"state" => 0,
					]
			];

			$creditLineDetail= "";

			if ($this->CreditsLine->save($creditLine)) {
				$CreditsLineId = $this->CreditsLine->id;

				foreach ($arrayTotal as $key => $value) {

					if (!$this->CreditsLine->query("insert into credits_lines_details(credit_line_id,count,month,min_month,max_month,min_value,max_value,interest_rate,others_rate,debt_rate) values(".$CreditsLineId.",".$value["count"].",".$value["month"].",".$value["min_month"].",".$value["max_month"].",".$value["min_value"].",".$value["max_value"].",".$value["interest_rate"].",".$value["others_rate"].",".$value["debt_rate"].") " )) {
						$data['status'] = 'ok';
					}else{
						$data['status'] = '';
						$data['error'] = 'Error al guardar detalle, por favor inténtelo más tarde ';
						break;
					}
				}
			} else {
				$data['status'] = '';
				$data['error'] = 'Error al guardar, por favor inténtelo más tarde ';
			}

			$this->set(compact('data', 'jsonHeaders'));

			$this->autoLayout = false;
			$this->response->disableCache();
			$this->response->modified('now');
			$this->response->checkNotModified($this->request);
			$this->render(false);
		
			$this->response->body(json_encode($data));
			//$this->response->body($data);
			$this->response->statusCode(200);
			$this->response->type('application/json');
		
			return $this->response;
		}
		
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CreditsLine->id = $id;
		if (!$this->CreditsLine->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post')) {
			$id = $this->decrypt($this->request->data["id"]);
			$name = $this->request->data["name"];
			$description = $this->request->data['description'];
			$state = $this->request->data['state'];
			$arrayTotal = json_decode($this->request->data['arrayTotal'], true);

			$this->loadModel("CreditsLine");
			//$this->CreditsLine->create();
			
			$creditLine = [
				"CreditsLine" =>
					[
						"id"  => $id,
						"name" => $name,
						"description"   => $description,
						"state" => $state,
					]
			];

			$creditLineDetail= "";

			if ($this->CreditsLine->save($creditLine)) {
				
				if (!$this->CreditsLine->query("delete from credits_lines_details where credit_line_id =".$id)) {

					foreach ($arrayTotal as $key => $value) {

						if (!$this->CreditsLine->query("insert into credits_lines_details(credit_line_id,count,month,min_month,max_month,min_value,max_value,interest_rate,others_rate,debt_rate) values(".$id.",".$value["count"].",".$value["month"].",".$value["min_month"].",".$value["max_month"].",".$value["min_value"].",".$value["max_value"].",".$value["interest_rate"].",".$value["others_rate"].",".$value["debt_rate"].") " )) {
							$data['status'] = 'ok';
						}else{
							$data['status'] = '';
							$data['error'] = 'Error al guardar detalle, por favor inténtelo más tarde ';
							break;
						}
					}
				}
				else{
					//$data['status'] = '';
					//$data['error'] = 'Error al eliminar datos del detalle, por favor inténtelo más tarde ';
					foreach ($arrayTotal as $key => $value) {

						if (!$this->CreditsLine->query("insert into credits_lines_details(credit_line_id,count,month,min_month,max_month,min_value,max_value,interest_rate,others_rate,debt_rate) values(".$id.",".$value["count"].",".$value["month"].",".$value["min_month"].",".$value["max_month"].",".$value["min_value"].",".$value["max_value"].",".$value["interest_rate"].",".$value["others_rate"].",".$value["debt_rate"].") " )) {
							$data['status'] = 'ok';
						}else{
							$data['status'] = '';
							$data['error'] = 'Error al guardar detalle, por favor inténtelo más tarde ';
							break;
						}
					}
				}

			} else {
				$data['status'] = '';
				$data['error'] = 'Error al guardar, por favor inténtelo más tarde ';
			}

			$this->set(compact('data', 'jsonHeaders'));

			$this->autoLayout = false;
			$this->response->disableCache();
			$this->response->modified('now');
			$this->response->checkNotModified($this->request);
			$this->render(false);
		
			$this->response->body(json_encode($data));
			//$this->response->body($data);
			$this->response->statusCode(200);
			$this->response->type('application/json');
		
			return $this->response;
			
		} else {
			$conditions = array('CreditsLine.' . $this->CreditsLine->primaryKey => $id);
			$this->set('creditsLine', $this->CreditsLine->find('first', compact('conditions')));
			$creditLineDetail = $this->CreditsLine->query("SELECT * FROM credits_lines_details where credit_line_id = ". $id);
			$this->set('creditLineDetail', $creditLineDetail);
		}
	}

	public function change_state($id){
		$this->autoRender = false;
		$id = $this->decrypt($id);

		$lineaActual = $this->CreditsLine->findById($id);
		if($lineaActual["CreditsLine"]["state"] == 1){
			$this->Session->setFlash(__('Error al guardar, debe existir una línea activa por lo menos.'), 'flash_error');
		}else{
			$lineaActual["CreditsLine"]["state"] = 1;
			$this->CreditsLine->updateAll(
				["CreditsLine.state" => 0],
				["CreditsLine.state" => 1]
			);
			$this->CreditsLine->save($lineaActual);
			$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
		}
		$this->redirect(array('action' => 'index'));
	}
}
