<?php
App::uses('AppModel', 'Model');

class CustomersReference extends AppModel {

	public $validate = array(
		'name' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name'),
		),
		'phone' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'phone'),
		),
		'customer_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'customer_id'),
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
				'LOWER(CustomersReference.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
