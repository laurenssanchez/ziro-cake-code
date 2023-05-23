<?php
App::uses('AppController', 'Controller');

class SignaturesController extends AppController {


	public function edit($id = null) {
		$id = 1;
      	$this->Signature->id = $id;
		if (!$this->Signature->exists($id)) {
			throw new NotFoundException(__('Página no encontrada'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Signature->save($this->request->data)) {
				$this->Session->setFlash(__('Los datos se han guardado correctamente'), 'flash_success');
				$this->redirect(array('action' => 'edit'));
			} else {
				$this->Session->setFlash(__('Error al guardar, por favor inténtelo más tarde'), 'flash_error');
			}
		} else {
			$conditions = array('Signature.' . $this->Signature->primaryKey => $id);
			$this->request->data = $this->Signature->find('first', compact('conditions'));
		}
	}
}
