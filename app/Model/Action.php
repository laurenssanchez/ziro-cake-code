<?php
App::uses('AppModel', 'Model');

class Action extends AppModel {

	public $validate = array(
		'controller' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'controller'),
		),
		'action' => array('notBlank' => array('rule' => array('notBlank'),'message' => 'action'),
		),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Action.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
