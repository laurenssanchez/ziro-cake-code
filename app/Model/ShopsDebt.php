<?php
App::uses('AppModel', 'Model');

class ShopsDebt extends AppModel {

	public $validate = array(
		'user_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'user_id'),
		),
		'shop_commerce_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_commerce_id'),
		),
		'type' => array('numeric' => array('rule' => array('numeric'),'message' => 'type'),
		),
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'reason' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'reason'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'Credit' => array('className' => 'Credit','foreignKey' => 'credit_id',),
		'ShopPaymentRequest' => array('className' => 'ShopPaymentRequest','foreignKey' => 'shop_payment_request_id',),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(ShopsDebt.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
