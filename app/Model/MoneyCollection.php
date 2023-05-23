<?php
App::uses('AppModel', 'Model');

class MoneyCollection extends AppModel {

	public $validate = array(
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
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

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(MoneyCollection.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
