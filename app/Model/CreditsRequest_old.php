<?php
App::uses('AppModel', 'Model');

class CreditsRequest extends AppModel {

	public $validate = array(
		'customer_id' => 
			array(
				'numeric' => array('rule' => array('numeric'),'message' => 'customer_id'),
				'comparison' => array('rule' => array('comparison','>=',1),'message' => 'no permite cero'),
			),
		'request_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'request_value'),
		),
		'request_number' => array('numeric' => array('rule' => array('numeric'),'message' => 'request_number'),
		),
		'credits_line_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_line_id'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'Customer' => array('className' => 'Customer','foreignKey' => 'customer_id',),
		'CreditsLine' => array('className' => 'CreditsLine','foreignKey' => 'credits_line_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id'),
		'UserDisbursed' => array('className' => 'User','foreignKey' => 'user_disbursed'),
		'Credit' => array('className' => 'Credit','foreignKey' => 'credit_id','dependent' => false,),
	);

	public $hasMany = array(
		'CreditLimit' => array('className' => 'CreditLimit','foreignKey' => 'credits_request_id'),
		'Document' => array('className' => 'Document','foreignKey' => 'credits_request_id'),
		'CreditsRequestsComment' => array('className' => 'CreditsRequestsComment','foreignKey' => 'credits_request_id','dependent' => false,),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CreditsRequest.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
