<?php
App::uses('CreditLimit', 'Model');

/**
 * CreditLimit Test Case
 */
class CreditLimitTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.credit_limit',
		'app.credit',
		'app.credits_line',
		'app.collection_fee',
		'app.customer',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_payment',
		'app.shops_debt',
		'app.shop_commerce',
		'app.credits_request',
		'app.credits_requests_comment',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.credits_plan'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CreditLimit = ClassRegistry::init('CreditLimit');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CreditLimit);

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
