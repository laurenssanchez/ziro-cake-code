<?php
App::uses('AppModel', 'Model');

class User extends AppModel {

	public function beforeSave($options = array())
	{
		if (isset($this->data[$this->alias]['password']) && !empty($this->data[$this->alias]['password']))
		{
	  		$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}
		return true;
	}

	public $validate = array(
		'name' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name'),
		),
		'email' => array('email' => array('rule' => array('email'),'message' => 'email'),
		),
		'password' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'password','on'=>"create"),
		),
		'role' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'role'),
		),
	);


	public $belongsTo = array(
		'Shop' => array('className' => 'Shop','foreignKey' => 'shop_id',),
		'Customer' => array('className' => 'Customer','foreignKey' => 'customer_id',),
		'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'shop_commerce_id',)
	);

	// public $hasMany = array(
	// 	'Customer' => array('className' => 'Customer','foreignKey' => 'user_id','dependent' => false,),
	// 	'ShopCommerce' => array('className' => 'ShopCommerce','foreignKey' => 'user_id','dependent' => false,),
	// 	'Shop' => array('className' => 'Shop','foreignKey' => 'user_id','dependent' => false,)
	// );


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(User.name) LIKE'=>"%{$params['q']}%",
				'LOWER(User.email) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

	public function generateHashChangePassword()
	{
		$salt = Configure::read('Security.salt');
		$salt_v2 = Configure::read('Security.cipherSeed');
		$rand = mt_rand(1,999999999);
		$rand_v2 = mt_rand(1,999999999);	
		$hash = hash('sha256',$salt.$rand.$salt_v2.$rand_v2);
		return $hash;
	}

	public function generate(){
		$this->recursive = -1;
		$code 	= rand(100000,999999);
		$exists = $this->findByCode($code,0);
		if(!empty($exists)){
			return $this->generate();
		}else{
			return $code;
		}

	}

}
