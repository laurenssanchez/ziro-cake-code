<?php
App::uses('Request', 'Model');

/**
 * Request Test Case
 */
class RequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		'app.requests_detail',
		'app.requests_payment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Request = ClassRegistry::init('Request');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Request);

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
