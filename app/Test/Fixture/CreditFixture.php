<?php
/**
 * Credit Fixture
 */
class CreditFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'value_request' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'value_aprooved' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'type_payment' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'deadlines' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'credits_line_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'interes_rate' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'others_rate' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'debt_rate' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'quota_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'customer_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'value_pending' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'value_request' => 1,
			'value_aprooved' => 1,
			'type_payment' => 1,
			'deadlines' => 1,
			'credits_line_id' => 1,
			'interes_rate' => 1,
			'others_rate' => 1,
			'debt_rate' => 1,
			'quota_value' => 1,
			'state' => 1,
			'customer_id' => 1,
			'created' => '2020-07-30 19:09:12',
			'modified' => '2020-07-30 19:09:12',
			'value_pending' => 1
		),
	);

}
