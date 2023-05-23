<?php
/**
 * CreditsPlan Fixture
 */
class CreditsPlanFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'credit_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'capital_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'interest_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'others_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'deadline' => array('type' => 'date', 'null' => false, 'default' => null),
		'value_pending' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'credit_id' => 1,
			'capital_value' => 1,
			'interest_value' => 1,
			'others_value' => 1,
			'deadline' => '2020-07-30',
			'value_pending' => 1,
			'state' => 1,
			'created' => '2020-07-30 20:14:21',
			'modified' => '2020-07-30 20:14:21'
		),
	);

}
