<?php
App::uses('AppModel', 'Model');

class CustomersAddress extends AppModel {

	public $validate = array(
		'customer_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'customer_id'),
		),
		'address_type' => array('numeric' => array('rule' => array('numeric'),'message' => 'address_type'),
		),
		'address' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'address'),
		),
		'address_city' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'address_city'),
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
				'LOWER(CustomersAddress.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
