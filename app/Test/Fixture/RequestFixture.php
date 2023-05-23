<?php
/**
 * Request Fixture
 */
class RequestFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'identification' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shop_commerce_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'code' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'state_request_payment' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'requests_detail_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'requests_payment_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
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
			'identification' => 1,
			'shop_commerce_id' => 1,
			'code' => 1,
			'value' => 1,
			'state' => 1,
			'state_request_payment' => 1,
			'requests_detail_id' => 1,
			'requests_payment_id' => 1,
			'user_id' => 1,
			'created' => '2021-01-29 22:55:24',
			'modified' => '2021-01-29 22:55:24'
		),
	);

}
