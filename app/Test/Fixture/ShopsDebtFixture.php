<?php
/**
 * ShopsDebt Fixture
 */
class ShopsDebtFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'shop_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'credit_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'credit_payments_shop_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'reason' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
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
			'user_id' => 1,
			'shop_id' => 1,
			'credit_id' => 1,
			'credit_payments_shop_id' => 1,
			'value' => 1,
			'reason' => 'Lorem ipsum dolor sit amet',
			'state' => 1,
			'created' => '2020-07-30 04:16:01',
			'modified' => '2020-07-30 04:16:01'
		),
	);

}
