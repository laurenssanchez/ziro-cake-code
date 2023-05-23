<?php
App::uses('RequestsPayment', 'Model');

/**
 * RequestsPayment Test Case
 */
class RequestsPaymentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.requests_payment',
		'app.request',
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
		'app.receipt',
		'app.payment',
		'app.shops_debt',
		'app.shop_payment_request',
		'app.disbursement',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.shop_reference',
		'app.shop_payment',
		'app.requests_detail'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->RequestsPayment = ClassRegistry::init('RequestsPayment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->RequestsPayment);

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
