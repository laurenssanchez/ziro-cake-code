<?php
App::uses('AppModel', 'Model');

class CreditsRequestsComment extends AppModel {

	public $validate = array(
		'type' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'type'),
		),
		'comment' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'comment'),
		),
		'credits_request_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_request_id'),
		),
	);


	public $belongsTo = array(
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'credits_request_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CreditsRequestsComment.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
