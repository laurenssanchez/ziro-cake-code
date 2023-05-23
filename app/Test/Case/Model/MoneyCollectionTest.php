<?php
App::uses('MoneyCollection', 'Model');

/**
 * MoneyCollection Test Case
 */
class MoneyCollectionTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.money_collection',
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
		'app.shop_payment'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MoneyCollection = ClassRegistry::init('MoneyCollection');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MoneyCollection);

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
