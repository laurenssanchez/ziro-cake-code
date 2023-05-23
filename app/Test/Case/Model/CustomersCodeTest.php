<?php
App::uses('CustomersCode', 'Model');

/**
 * CustomersCode Test Case
 */
class CustomersCodeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.customers_code',
		'app.customer',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_commerce',
		'app.credit',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomersCode = ClassRegistry::init('CustomersCode');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomersCode);

		parent::tearDown();
	}

/**
 * testBuildConditions method
 *
 * @return void
 */
	public function testBuildConditions() {
		$this->markTestIncomplete('testBuildConditions not implemented.');
	}

}
