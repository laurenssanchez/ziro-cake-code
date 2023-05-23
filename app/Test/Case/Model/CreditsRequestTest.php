<?php
App::uses('CreditsRequest', 'Model');

/**
 * CreditsRequest Test Case
 */
class CreditsRequestTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.credits_request',
		'app.customer',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_commerce',
		'app.credit',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference',
		'app.credits_line',
		'app.collection_fee'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CreditsRequest = ClassRegistry::init('CreditsRequest');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CreditsRequest);

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
