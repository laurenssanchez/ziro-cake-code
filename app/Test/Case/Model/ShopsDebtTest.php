<?php
App::uses('ShopsDebt', 'Model');

/**
 * ShopsDebt Test Case
 */
class ShopsDebtTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shops_debt',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_payment',
		'app.customer',
		'app.credit',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.shop_commerce',
		'app.credits_request',
		'app.credits_line',
		'app.collection_fee',
		'app.credits_requests_comment',
		'app.credit_payments_shop'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShopsDebt = ClassRegistry::init('ShopsDebt');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShopsDebt);

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
