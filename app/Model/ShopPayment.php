<?php
App::uses('AppModel', 'Model');

class ShopPayment extends AppModel {

	public $validate = array(
		'date' => array('datetime' => array('rule' => array('datetime'),'message' => 'date'),
		),
		'outstanding_balance' => array('numeric' => array('rule' => array('numeric'),'message' => 'outstanding balance'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(ShopPayment.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
