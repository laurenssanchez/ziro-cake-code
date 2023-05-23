<?php
App::uses('AppModel', 'Model');

class ShopCommerce extends AppModel {

	public $virtualFields = array(
	    'shop_name' => '( SELECT social_reason from shops WHERE shops.id = ShopCommerce.shop_id )',
	    'shop_city' => '( SELECT city from shops WHERE shops.id = ShopCommerce.shop_id )',
	);

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

	public function buscarPorCodigo($code) {

		$sqlUpdate = "SELECT shop_commerce.id,shop_commerce.name,shop_commerce.code,shop.id, shop.social_reason
		FROM  shop_commerces AS shop_commerce
		INNER JOIN shops as shop on shop.id = shop_commerce.shop_id
		WHERE code = ".$code;
		$response = $this->query($sqlUpdate);
		return $response;
	}

}
