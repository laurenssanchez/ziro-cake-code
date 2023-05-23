<?php
/**
 * CustomersCode Fixture
 */
class CustomersCodeFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'code' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'customer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'deadline' => array('type' => 'biginteger', 'null' => false, 'default' => null, 'unsigned' => false),
		'type_code' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
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
			'code' => 1,
			'customer_id' => 1,
			'deadline' => 1,
			'type_code' => 1,
			'state' => 1,
			'created' => '2020-07-16 13:25:16',
			'modified' => '2020-07-16 13:25:16'
		),
	);

}
