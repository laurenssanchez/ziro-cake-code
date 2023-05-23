<?php
App::uses('ShopPayment', 'Model');

/**
 * ShopPayment Test Case
 */
class ShopPaymentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shop_payment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShopPayment = ClassRegistry::init('ShopPayment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShopPayment);

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
