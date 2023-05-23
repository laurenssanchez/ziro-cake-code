<?php
App::uses('AppModel', 'Model');

class Document extends AppModel {

	public $actsAs = array(
	   'Upload.Upload' => array(
	         'file' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}documents{DS}',
	           'deleteOnUpdate' => true,
	           // 'deleteFolderOnDelete' => true,
	           // 'maxSize' => 3000000,
	         ),
	     )
	);

	public $belongsTo = array(
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'credits_request_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Document.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
