<?php
App::uses('Simulator', 'Model');

/**
 * Simulator Test Case
 */
class SimulatorTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.simulator',
		'app.credits_line',
		'app.collection_fee',
		'app.credit',
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
		'app.credits_plan',
		'app.receipt',
		'app.payment',
		'app.shops_debt',
		'app.shop_payment_request',
		'app.disbursement'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Simulator = ClassRegistry::init('Simulator');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Simulator);

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
