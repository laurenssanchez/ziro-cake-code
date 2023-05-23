<?php
App::uses('AppModel', 'Model');

class customersVerified extends AppModel {

	public $validate = array(
		'identification' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'identification'),
		),
	);
}
