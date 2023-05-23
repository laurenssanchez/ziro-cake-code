<?php
App::uses('AppModel', 'Model');

class History extends AppModel {

	public $validate = array(
		'credits_plan_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_plan_id'),
		),
		'user_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'user_id'),
		),
		'action' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'action'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);


	public $belongsTo = array(
		'CreditsPlan' => array('className' => 'CreditsPlan','foreignKey' => 'credits_plan_id',),
		'User' => array('className' => 'User','foreignKey' => 'user_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(History.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
