<?php
App::uses('AppModel', 'Model');

class Empresa extends AppModel {

	public $actsAs = array(
	   'Upload.Upload' => array(
	         'chamber_commerce_file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}empresas{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'rut_file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}empresas{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'account_file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}empresas{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'identification_up_file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}empresas{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'identification_down_file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}empresas{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	         'image_admin' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}empresas{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	     )
	);

	public $validate = array(
		'nit' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'nit'),
		),
		'social_reason' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'social_reason'),
		),
		'guild' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'guild'),
		),
		'department' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'department'),
		),
		'city' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'city'),
		),
		'address' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'address'),
		),
		'account_bank' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'account_bank'),
		),
		'account_number' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'account_number'),
		),
		'account_type' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'account_type'),
		),
		'identification_account' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'identification_account'),
		),
		'email' => array('email' => array('rule' => array('email'),'message' => 'email'),
		),
		'name_admin' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name_admin'),
		),
		'identification_admin' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'identification_admin'),
		),
		'plan' => array('numeric' => array('rule' => array('numeric'),'message' => 'plan'),
		),
		'payment_type' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'payment_type'),
		),
		'number_commerces' => array('numeric' => array('rule' => array('numeric'),'message' => 'number_commerces'),
		),
		'payment_total' => array('numeric' => array('rule' => array('numeric'),'message' => 'payment_total'),
		),
		'services_list' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'services_list'),
		),
		'products_lists' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'products_lists'),
		),
		'phone' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'phone'),
		),
		'adviser' => array('numeric' => array('rule' => array('numeric'),'message' => 'adviser'),
		),
		'cellpone_admin' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'cellpone_admin'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'User' => array('className' => 'User','foreignKey' => 'user_id',),
		'Adviser' => array('className' => 'User','foreignKey' => 'adviser',)
	);

	public $hasMany = array(
		'EmpresaReference' => array('className' => 'EmpresaReference','foreignKey' => 'empresa_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Empresa.social_reason) LIKE'=>"%{$params['q']}%",
				'LOWER(Empresa.nit) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
