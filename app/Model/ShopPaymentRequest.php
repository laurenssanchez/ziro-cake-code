<?php
App::uses('AppModel', 'Model');

class ShopPaymentRequest extends AppModel {

	public $validate = array(
		'request_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'request_value'),
		),
		'iva' => array('numeric' => array('rule' => array('numeric'),'message' => 'iva'),
		),
		'shop_commerce_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_commerce_id'),
		),
		'user_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'user_id'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',)
	);

	public $hasMany = array(
		'Disbursement' => array('className' => 'Disbursement','foreignKey' => 'shop_payment_request_id','dependent' => false,),
		'ShopsDebt' => array('className' => 'ShopsDebt','foreignKey' => 'shop_payment_request_id','dependent' => false,),
		'Payment' => array('className' => 'Payment','foreignKey' => 'shop_payment_request_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(ShopPaymentRequest.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
