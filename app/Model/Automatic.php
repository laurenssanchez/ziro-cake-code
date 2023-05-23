<?php
App::uses('AppModel', 'Model');

class Automatic extends AppModel {

	public $validate = array(
		'min_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'min_value'),
		),
		'max_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'max_value'),
		),
		'type_value' => array('numeric' => array('rule' => array('numeric'),'message' => 'type_value'),
		),
		'score_min' => array('numeric' => array('rule' => array('numeric'),'message' => 'score_min'),
		),
		'aplica_cap' => array('numeric' => array('rule' => array('numeric'),'message' => 'aplica_cap'),
		),
		'aplica_min_value_oblig' => array('numeric' => array('rule' => array('numeric'),'message' => 'aplica_min_value_oblig'),
		),
		'min_mora' => array('numeric' => array('rule' => array('numeric'),'message' => 'min_mora'),
		),
		'state' => array('numeric' => array('rule' => array('numeric'),'message' => 'state'),
		),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Automatic.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
