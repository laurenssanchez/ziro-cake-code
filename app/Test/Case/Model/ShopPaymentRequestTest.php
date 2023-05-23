<?php
App::uses('ShopPaymentRequest', 'Model');

/**
 * ShopPaymentRequest Test Case
 */
class ShopPaymentRequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shop_payment_request',
		'app.shop_commerce',
		'app.shop',
		'app.user',
		'app.customer',
		'app.credit',
		'app.credits_line',
		'app.collection_fee',
		'app.credits_request',
		'app.credit_limit',
		'app.credits_requests_comment',
		'app.credits_plan',
		'app.shops_debt',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.shop_reference',
		'app.shop_payment',
		'app.disbursement'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->ShopPaymentRequest = ClassRegistry::init('ShopPaymentRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->ShopPaymentRequest);

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
