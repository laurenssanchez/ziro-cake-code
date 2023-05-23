<?php
App::uses('AppModel', 'Model');

class Request extends AppModel {

	public $validate = array(
		'identification' => array('numeric' => array('rule' => array('numeric'),'message' => 'identification'),
		),
		'shop_commerce_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_commerce_id'),
		),
		'code' => array('numeric' => array('rule' => array('numeric'),'message' => 'code'),
		),
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
		'user_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'user_id'),
		),
	);


	public $belongsTo = array(
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',),
		'RequestsDetail' => array('className' => 'RequestsDetail','foreignKey' => 'requests_detail_id',),
		'RequestsPayment' => array('className' => 'RequestsPayment','foreignKey' => 'requests_payment_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',)
	);



	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Request.code) LIKE'=>"%{$params['q']}%",
				'LOWER(Request.identification) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function generate(){
		$this->recursive = -1;
		$code 	= rand(100000,999999);
		$exists = $this->findByCode($code);
		if(!empty($exists)){
			return $this->generate();
		}else{
			return $code;
		}

	}

}
