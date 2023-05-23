<?php
App::uses('AppModel', 'Model');

class CollectionFee extends AppModel {

	public $validate = array(
		'credits_line_id' => array('numeric' => array('rule' => array('numeric'),'message' => 'credits_line_id'),
		),
		'day_ini' => array('numeric' => array('rule' => array('numeric'),'message' => 'day_ini'),
		),
		'day end' => array('numeric' => array('rule' => array('numeric'),'message' => 'day end'),
		),
		'rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'rate'),
		),
	);


	public $belongsTo = array(
		'CreditsLine' => array('className' => 'CreditsLine','foreignKey' => 'credits_line_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CollectionFee.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
