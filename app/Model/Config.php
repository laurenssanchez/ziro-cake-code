<?php
App::uses('AppModel', 'Model');

class Config extends AppModel {

	public $validate = array(
		'comision' => array('numeric' => array('rule' => array('numeric'),'message' => 'comision'),
		),
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(Config.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
