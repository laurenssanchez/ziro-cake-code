<?php
App::uses('AppModel', 'Model');

class CustomersPhone extends AppModel {

	public $validate = array(
		'customer_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'customer_id'),
		),
		'phone_type' => array('numeric' => array('rule' => array('numeric'),'message' => 'phone_type'),
		),
		'phone_number' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'phone_number'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'Customer' => array('className' => 'Customer','foreignKey' => 'customer_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CustomersPhone.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
