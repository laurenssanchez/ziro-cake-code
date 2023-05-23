<?php
App::uses('AppModel', 'Model');

class Signature extends AppModel {

	public $validate = array(
		'initial' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'initial'),
		),
		'full_text' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'full_text'),
		),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Signature.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
