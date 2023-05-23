<?php
App::uses('AppModel', 'Model');

class Simulator extends AppModel {

	public $validate = array(
		'name' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name'),
		),
		'credits_line_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_line_id'),
		),
		'website' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'website'),
		),
	);


	public $belongsTo = array(
		'CreditsLine' => array('className' => 'CreditsLine','foreignKey' => 'credits_line_id',)
	);

	public $hasMany = array(
		'CreditsRequest' => array('className' => 'CreditsRequest','foreignKey' => 'simulator_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Simulator.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
