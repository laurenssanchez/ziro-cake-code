<?php
/**
 * CreditLimit Fixture
 */
class CreditLimitFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'type_movement' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'reason' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'deadline' => array('type' => 'date', 'null' => false, 'default' => null),
		'credit_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'credits_request_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'customer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8mb4', 'collate' => 'utf8mb4_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'value' => 1,
			'type_movement' => 1,
			'state' => 1,
			'reason' => 'Lorem ipsum dolor sit amet',
			'deadline' => '2020-08-06',
			'credit_id' => 1,
			'credits_request_id' => 1,
			'user_id' => 1,
			'customer_id' => 1,
			'created' => '2020-08-06 03:29:00',
			'modified' => '2020-08-06 03:29:00'
		),
	);

}
