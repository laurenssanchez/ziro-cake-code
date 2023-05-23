<?php
App::uses('CustomersReference', 'Model');

/**
 * CustomersReference Test Case
 */
class CustomersReferenceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.customers_reference',
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
		$this->CustomersReference = ClassRegistry::init('CustomersReference');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CustomersReference);

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
