<?php
App::uses('AppModel', 'Model');

class Repayment extends AppModel {

	public $validate = array(
		'credits_plan_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_plan_id'),
		),
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'user_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'user_id'),
		),
		'type' => array('numeric' => array('rule' => array('numeric'),'message' => 'type'),
		),
		'juridic' => array('numeric' => array('rule' => array('numeric'),'message' => 'juridic'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
		'state_credishop' => array('numeric' => array('rule' => array('numeric'),'message' => 'state_credishop'),
		),
	);


	public $belongsTo = array(
		'CreditsPlan' => array('className' => 'CreditsPlan','foreignKey' => 'credits_plan_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'ShopPaymentRequest' => array('className' => 'ShopPaymentRequest','foreignKey' => 'shop_payment_request_id',),
		'Receipt' => array('className' => 'Receipt','foreignKey' => 'receipt_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Repayment.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
