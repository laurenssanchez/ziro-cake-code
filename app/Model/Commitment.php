<?php
App::uses('AppModel', 'Model');

class Commitment extends AppModel {

	public $validate = array(
		'credits_plan_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_plan_id'),
		),
		'user_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'user_id'),
		),
		'commitment' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'commitment'),
		),
		'deadline' => array('date' => array('rule' => array('date'),'message' => 'deadline'),
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
				'LOWER(Commitment.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
