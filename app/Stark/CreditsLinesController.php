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
	}


	public function add() {
		if ($this->request->is('post')) {
			$this->CreditsLine->create();
			$this->CreditsLine->updateAll(
				["CreditsLine.state" => 0],
				["CreditsLine.state" => 1]
			);
			if ($this->CreditsLine->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		}
	}


	public function edit($id = null) {
		$id = $this->decrypt($id);
      	$this->CreditsLine->id = $id;
		if (!$this->CreditsLine->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->CreditsLine->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('CreditsLine.' . $this->CreditsLine->primaryKey => $id);
			$this->request->data = $this->CreditsLine->find('first', compact('conditions'));
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
