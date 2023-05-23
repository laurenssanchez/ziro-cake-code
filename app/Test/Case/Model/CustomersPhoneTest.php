<?php
App::uses('CustomersPhone', 'Model');

/**
 * CustomersPhone Test Case
 */
class CustomersPhoneTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.customers_phone',
		'app.customer',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_commerce',
		'app.credit'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CustomersPhone = ClassRegistry::init('CustomersPhone');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomersPhone);

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
