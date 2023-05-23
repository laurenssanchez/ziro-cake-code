<?php
/**
 * Repayment Fixture
 */
class RepaymentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'credits_plan_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'value' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shop_commerce_id' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false),
		'shop_payment_request_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
		'juridic' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'state_credishop' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'date_credishop' => array('type' => 'biginteger', 'null' => true, 'default' => null, 'unsigned' => false),
		'receipt_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
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
			'credits_plan_id' => 1,
			'value' => 1,
			'user_id' => 1,
			'shop_commerce_id' => 1,
			'shop_payment_request_id' => 1,
			'type' => 1,
			'juridic' => 1,
			'state' => 1,
			'state_credishop' => 1,
			'date_credishop' => 1,
			'receipt_id' => 1,
			'created' => '2020-11-18 22:34:25',
			'modified' => '2020-11-18 22:34:25'
		),
	);

}
