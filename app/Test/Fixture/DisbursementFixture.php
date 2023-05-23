<?php
/**
 * Disbursement Fixture
 */
class DisbursementFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'unpaid_value' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'credit_id' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'shop_commerce_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '1', 'unsigned' => false),
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
			'unpaid_value' => 1,
			'credit_id' => 1,
			'shop_commerce_id' => 1,
			'state' => 1,
			'created' => '2020-08-13 03:32:52',
			'modified' => '2020-08-13 03:32:52'
		),
	);

}
