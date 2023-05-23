<?php
App::uses('AppModel', 'Model');

class CreditLimit extends AppModel {

	public $validate = array(
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'type_movement' => array('numeric' => array('rule' => array('numeric'),'message' => 'type_movement'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
		'reason' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'reason'),
		),
		'deadline' => array('date' => array('rule' => array('date'),'message' => 'deadline'),
		),
		'customer_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'customer_id'),
		),
	);


	public $belongsTo = array(
		'Credit' => array('className' => 'Credit','foreignKey' => 'credit_id',),
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'credits_request_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'Customer' => array('className' => 'Customer','foreignKey' => 'customer_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CreditLimit.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function totalQuote($client_id = null){

		$dataLimit  = $this->find("all",["conditions"=>["customer_id" => $client_id],"recursive"=>-1]);
		$total 		= 0;

		if(!empty($dataLimit)){
			foreach ($dataLimit as $key => $value) {
				if(in_array($value["CreditLimit"]["state"], [1,3,5]) && $value["CreditLimit"]["active"] == 1 ){
					$total+=$value["CreditLimit"]["value"];
				}
			}
		}

		if ($total < 0) {
			$total = 0;
		}

		return $total;


	}


}
