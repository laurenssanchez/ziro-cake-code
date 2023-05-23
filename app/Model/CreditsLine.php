<?php
App::uses('AppModel', 'Model');

class CreditsLine extends AppModel {

	public $validate = array(
		'name' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'name'),
		),
		'interest_rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'interest_rate'),
		),
		'others_rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'others_rate'),
		),
		'debt_rate' => array('numeric' => array('rule' => array('numeric'),'message' => 'debt_rate'),
		),
		'min_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'min_value'),
		),
		'max_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'max_value'),
		),
		'min_month' => array('numeric' => array('rule' => array('numeric'),'message' => 'min_month'),
		),
		'max_month' => array('numeric' => array('rule' => array('numeric'),'message' => 'max_month'),
		)

	);




	public $hasMany = array(
		'CollectionFee' => array('className' => 'CollectionFee','foreignKey' => 'credits_line_id','dependent' => false,),
		'Credit' => array('className' => 'Credit','foreignKey' => 'credits_line_id','dependent' => false,)
	);


	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CreditsLine.name) LIKE'=>"%{$params['q']}%",
				'LOWER(CreditsLine.description) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
