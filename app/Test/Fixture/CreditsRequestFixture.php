<?php
/**
 * CreditsRequest Fixture
 */
class CreditsRequestFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'customer_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'request_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'request_number' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'credits_line_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'customer_id' => 1,
			'request_value' => 1,
			'request_number' => 1,
			'credits_line_id' => 1,
			'state' => 1,
			'created' => '2020-07-16 15:58:18',
			'modified' => '2020-07-16 15:58:18'
		),
	);

}
