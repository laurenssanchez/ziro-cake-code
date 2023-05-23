<?php
App::uses('AppModel', 'Model');

class CustomersCode extends AppModel {

	public $belongsTo = array(
		'Customer' => array('className' => 'Customer','foreignKey' => 'customer_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Customer.identification) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function closeCodes(){
		$this->updateAll(
			["CustomersCode.state" => 2 ],
			["CustomersCode.deadline <= " => strtotime('now'), "CustomersCode.state" => 0 ]
		);
	}

	public function generate(){
		$this->recursive = -1;
		$code 	= rand(100000,999999);
		$exists = $this->findByCodeAndState($code,0);
		if(!empty($exists)){
			return $this->generate();
		}else{
			return $code;
		}

	}

}
