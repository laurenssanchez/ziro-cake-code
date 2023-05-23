<?php
/**
 * CreditsLinesDetail Fixture
 */
class CreditsLinesDetailFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'credit_line_id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'month' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'count' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'min_month' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'max_month' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'min_value' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'max_value' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'interest_rate' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'others_rate' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'debt_rate' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'credit_line_id' => 1,
			'month' => 1,
			'count' => 1,
			'min_month' => 1,
			'max_month' => 1,
			'min_value' => 1,
			'max_value' => 1,
			'interest_rate' => 1,
			'others_rate' => 1,
			'debt_rate' => 1
		),
	);

}
