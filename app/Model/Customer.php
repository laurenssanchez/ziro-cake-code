<?php
App::uses('AppModel', 'Model');

class Customer extends AppModel {

	public function beforeSave($options = array()) {
	    if (isset($this->data[$this->alias]['identification']) &&
	        !empty($this->data[$this->alias]['identification'])
	    ) {
	    	$this->data[$this->alias]['identification'] = preg_replace('/[^0-9]/', '', $this->data[$this->alias]['identification']);
	    }

	    if (isset($this->data['identification']) &&
	        !empty($this->data['identification'])
	    ) {
	    	$this->data['identification'] = preg_replace('/[^0-9]/', '', $this->data['identification']);
	    }
	    return true;
	}

	public $virtualFields = array(
	    'celular' => '( SELECT phone_number from customers_phones WHERE customers_phones.customer_id = Customer.id order by id desc limit 1 )',
		'fullname' => 'CONCAT(Customer.user_id_commerce, " ", Customer.name, " ", Customer.last_name)'
	);



	public $actsAs = array(
	   'Upload.Upload' => array(
	         'document_file_up' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}customers{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'document_file_down' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}customers{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'image_file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}customers{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	     )
	);

	public $validate = array(
		'identification_type' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'identification_type'),
		),
		'identification' => array(
			'notBlank' => array('rule' => array('notBlank'),'message' => 'identification'),
			// 'unique' => array(
		 //        'rule' => 'isUnique',
		 //        'required' => 'create'
		 //    ),
		),
		// 'email' => array('email' => array('rule' => array('email'),'message' => 'email'),
		// ),
		'tyc' => array('numeric' => array('rule' => array('numeric'),'message' => 'tyc'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
		'data_full' => array('numeric' => array('rule' => array('numeric'),'message' => 'data_full'),
		),
	);


	public $belongsTo = array(
		// 'User' => array('className' => 'User','foreignKey' => 'user_id',)
	);

	public $hasMany = array(
		'Credit' => array('className' => 'Credit','foreignKey' => 'customer_id','dependent' => false,),
		'User' => array('className' => 'User','foreignKey' => 'customer_id','dependent' => false,),
		'CustomersAddress' => array('className' => 'CustomersAddress','foreignKey' => 'customer_id','dependent' => false,),
		'CustomersPhone' => array('className' => 'CustomersPhone','foreignKey' => 'customer_id','dependent' => false,),
		'CustomersReference' => array('className' => 'CustomersReference','foreignKey' => 'customer_id','dependent' => false,),
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'customer_id','dependent' => false,),
		'CreditLimit' => array('className' => 'CreditLimit','foreignKey' => 'customer_id','dependent' => false,),
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Customer.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
