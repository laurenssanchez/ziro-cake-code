<?php
App::uses('AppModel', 'Model');

class Disbursement extends AppModel {

	public $validate = array(
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'unpaid_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'unpaid_value'),
		),
		'credit_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credit_id'),
		),
		'shop_commerce_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_commerce_id'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'Credit' => array('className' => 'Credit','foreignKey' => 'credit_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'ShopPaymentRequest' => array('className' => 'ShopPaymentRequest','foreignKey' => 'shop_payment_request_id',),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Disbursement.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
