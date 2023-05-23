<?php
App::uses('AppModel', 'Model');

class RequestsPayment extends AppModel {

	public $validate = array(
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'shop_commerce_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_commerce_id'),
		),
		'comision_percentaje' => array('numeric' => array('rule' => array('numeric'),'message' => 'comision_percentaje'),
		),
		'comision_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'comision_value'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',)
	);

	public $hasMany = array(
		'Request' => array('className' => 'Request','foreignKey' => 'requests_payment_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(RequestsPayment.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
