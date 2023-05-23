<?php
App::uses('AppModel', 'Model');

class NotesCustomer extends AppModel {



	public $belongsTo = array(
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'credits_request_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(NotesCustomer.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
