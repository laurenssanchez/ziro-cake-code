<?php
App::uses('History', 'Model');

/**
 * History Test Case
 */
class HistoryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.history',
		'app.credits_plan',
		'app.credit',
		'app.credits_line',
		'app.collection_fee',
		'app.customer',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_payment',
		'app.shop_commerce',
		'app.credits_request',
		'app.credit_limit',
		'app.credits_requests_comment',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.shops_debt',
		'app.shop_payment_request',
		'app.disbursement',
		'app.payment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->History = ClassRegistry::init('History');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->History);

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
