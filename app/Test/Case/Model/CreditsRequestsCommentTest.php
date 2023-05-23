<?php
App::uses('CreditsRequestsComment', 'Model');

/**
 * CreditsRequestsComment Test Case
 */
class CreditsRequestsCommentTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.credits_requests_comment',
		'app.credits_request',
		'app.customer',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_payment',
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
		$this->CreditsRequestsComment = ClassRegistry::init('CreditsRequestsComment');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CreditsRequestsComment);

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
