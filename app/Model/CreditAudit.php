<?php
App::uses('AppModel', 'Model');

class CreditAudit extends AppModel {

	public $belongsTo = array(
		'CreditAudit' => array('className' => 'Credit','foreignKey' => 'credit_id',)
	);

}
