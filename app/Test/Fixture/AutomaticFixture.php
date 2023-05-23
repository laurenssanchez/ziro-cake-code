<?php
/**
 * Automatic Fixture
 */
class AutomaticFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary'),
		'min_value' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'max_value' => array('type' => 'float', 'null' => false, 'default' => '0', 'unsigned' => false),
		'score_min' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'aplica_cap' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'cap' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'min_oblig' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'min_mora' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'state' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'MyISAM')
	);

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'min_value' => 1,
			'max_value' => 1,
			'score_min' => 1,
			'aplica_cap' => 1,
			'cap' => 1,
			'min_oblig' => 1,
			'min_mora' => 1,
			'state' => 1,
			'created' => '2022-02-16 23:18:14',
			'modified' => '2022-02-16 23:18:14'
		),
	);

}
