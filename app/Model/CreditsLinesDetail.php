<?php
App::uses('AppModel', 'Model');

class CreditsLinesDetail extends AppModel {



	public $belongsTo = array(
		'CreditsLine' => array('className' => 'CreditsLine','foreignKey' => 'credit_line_id',)
	);

	public function buildConditions($params=array()) {
		$conditions = array();
		if(!empty($params['q'])) {
			$params['q'] = trim(strtolower($params['q']));
			$conditions['OR'] = array(
				'LOWER(CreditsLinesDetail.name) LIKE'=>"%{$params['q']}%",
			);
		}
		return $conditions;
	}

}
