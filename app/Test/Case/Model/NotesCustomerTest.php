<?php
App::uses('NotesCustomer', 'Model');

/**
 * NotesCustomer Test Case
 */
class NotesCustomerTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.notes_customer',
		'app.credits_request',
		'app.customer',
		'app.credit',
		'app.credits_line',
		'app.collection_fee',
		'app.credit_limit',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_payment',
		'app.shop_commerce',
		'app.credits_plan',
		'app.shops_debt',
		'app.shop_payment_request',
		'app.disbursement',
		'app.payment',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.credits_requests_comment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->NotesCustomer = ClassRegistry::init('NotesCustomer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->NotesCustomer);

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
