<?php
App::uses('Disbursement', 'Model');

/**
 * Disbursement Test Case
 */
class DisbursementTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.disbursement',
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
		'app.credit_limit',
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
		$this->Disbursement = ClassRegistry::init('Disbursement');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Disbursement);

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
