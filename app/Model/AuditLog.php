<?php
App::uses('AppModel', 'Model');

class AuditLog extends AppModel {

	public $validate = array(
		'controller' => array('ip' => array('rule' => array('notBlank'),'message' => 'ip'),
		),
		'action' => array('pagina' => array('rule' => array('notBlank'),'message' => 'pagina'),
		),
	);

}
