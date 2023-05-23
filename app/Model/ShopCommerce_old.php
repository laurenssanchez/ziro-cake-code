<?php
App::uses('AppModel', 'Model');

class ShopCommerce extends AppModel {

	public $actsAs = array(
	   'Upload.Upload' => array(
	         'image' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}shop_commerces{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	     )
	);

	public $validate = array(
		'name' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name'),
		),
		'address' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'address'),
		),
		'phone' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'phone'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
		'shop_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'shop_id'),
		),
	);


	public $belongsTo = array(
		'Shop' => array('className' => 'Shop','foreignKey' => 'shop_id',)
	);

	public $hasMany = array(
		'User' => array('className' => 'User','foreignKey' => 'shop_commerce_id','dependent' => false,),
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'shop_commerce_id','dependent' => false,),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(ShopCommerce.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function generate(){
		$this->recursive = -1;
		$code 	= rand(10000000,99999999);
		$exists = $this->findByCode($code);
		if(!empty($exists)){
			return $this->generate();
		}else{
			return $code;
		}

	}

}
