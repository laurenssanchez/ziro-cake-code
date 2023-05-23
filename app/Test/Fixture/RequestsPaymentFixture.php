<?php
/**
 * RequestsPayment Fixture
 */
class RequestsPaymentFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'request_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'comision_percentaje' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'comision_value' => array('type' => 'float', 'null' => false, 'default' => null, 'unsigned' => false),
		'date_payment' => array('type' => 'date', 'null' => true, 'default' => null),
		'note' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8mb4_general_ci', 'charset' => 'utf8mb4'),
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
			'request_id' => 1,
			'value' => 1,
			'comision_percentaje' => 1,
			'comision_value' => 1,
			'date_payment' => '2021-01-31',
			'note' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'state' => 1,
			'created' => '2021-01-31 21:37:42',
			'modified' => '2021-01-31 21:37:42'
		),
	);

}
