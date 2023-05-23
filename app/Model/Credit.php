<?php
App::uses('AppModel', 'Model');

class Credit extends AppModel {

	public $validate = array(
		'value_request' => array('numeric' => array('rule' => array('numeric'),'message' => 'value_request'),
		),
		'value_aprooved' => array('numeric' => array('rule' => array('numeric'),'message' => 'value_aprooved'),
		),
		'number_fee' => array('numeric' => array('rule' => array('numeric'),'message' => 'number_fee'),
		),
		'credits_line_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_line_id'),
		),
		'interes_rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'interes_rate'),
		),
		'others_rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'others_rate'),
		),
		'debt_rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'debt_rate'),
		),
		'quota_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'quota_value'),
		),
		'value_pending' => array('numeric' => array('rule' => array('numeric'),'message' => 'value_pending'),
		),
		'deadline' => array('date' => array('rule' => array('date'),'message' => 'deadline'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
		'customer_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'customer_id'),
		),
		'credits_request_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_request_id'),
		),
	);


	public $belongsTo = array(
		'CreditsLine' => array('className' => 'CreditsLine','foreignKey' => 'credits_line_id',),
		'Customer' => array('className' => 'Customer','foreignKey' => 'customer_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'credits_request_id',)
	);

	public $hasMany = array(
		'CreditLimit' => array('className' => 'CreditLimit','foreignKey' => 'credit_id','dependent' => false,),
		'CreditsPlan' => array('className' => 'CreditsPlan','foreignKey' => 'credit_id','dependent' => false,),
		'ShopsDebt' => array('className' => 'ShopsDebt','foreignKey' => 'credit_id','dependent' => false,),
		'CreditAudit' => array('className' => 'CreditAudit','foreignKey' => 'credit_id','dependent' => false,),
		// 'CreditsRequest2' => array('className' => 'CreditsRequest','foreignKey' => 'credit_id',)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Credit.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function getTotalCredit($creditId){
		
	}

}
