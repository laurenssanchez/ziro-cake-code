<?php
App::uses('AppModel', 'Model');

class RequestsDetail extends AppModel {

	public $validate = array(
		'request_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'request_id'),
		),
		'state_payment' => array('numeric' => array('rule' => array('numeric'),'message' => 'state_payment'),
		),
		'value' => array('numeric' => array('rule' => array('numeric'),'message' => 'value'),
		),
		'response' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'response'),
		),
	);


	public $belongsTo = array(
		'Request' => array('className' => 'Request','foreignKey' => 'request_id',)
	);

	public $hasMany = array(
		'RequestData' => array('className' => 'Request','foreignKey' => 'requests_detail_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(RequestsDetail.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
