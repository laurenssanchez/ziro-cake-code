<?php
App::uses('AppModel', 'Model');

class ShopReference extends AppModel {

	public $validate = array(
		'name' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name'),
		),
		'phone' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'phone'),
		),
		'commerce' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'commerce'),
		),
		'shop_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_id'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'Shop' => array('className' => 'Shop','foreignKey' => 'shop_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(ShopReference.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
