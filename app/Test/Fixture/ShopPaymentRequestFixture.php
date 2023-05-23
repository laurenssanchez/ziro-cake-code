<?php
/**
 * ShopPaymentRequest Fixture
 */
class ShopPaymentRequestFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'final_value' => array('type' => 'float', 'null' => true, 'default' => null, 'unsigned' => false),
		'request_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'iva' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'request_date' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'shop_commerce_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'notes' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			
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
			'final_value' => 1,
			'request_value' => 1,
			'iva' => 1,
			'request_date' => 1,
			'shop_commerce_id' => 1,
			'user_id' => 1,
			'notes' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'state' => 1,
			'created' => '2020-08-21 04:06:50',
			'modified' => '2020-08-21 04:06:50'
		),
	);

}
