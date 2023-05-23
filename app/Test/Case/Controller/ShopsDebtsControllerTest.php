<?php
App::uses('ShopsDebtsController', 'Controller');

/**
 * ShopsDebtsController Test Case
 */
class ShopsDebtsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.shops_debt',
		'app.user',
		'app.shop',
		'app.shop_reference',
		'app.shop_payment',
		'app.customer',
		'app.credit',
		'app.credits_line',
		'app.collection_fee',
		'app.credits_request',
		'app.shop_commerce',
		'app.credit_limit',
		'app.credits_requests_comment',
		'app.credits_plan',
		'app.customers_address',
		'app.customers_phone',
		'app.customers_reference'
	);

/**
 * testIndex method
 *
 * @return void
 */
	public function testIndex() {
		$this->markTestIncomplete('testIndex not implemented.');
	}

/**
 * testView method
 *
 * @return void
 */
	public function testView() {
		$this->markTestIncomplete('testView not implemented.');
	}

/**
 * testAdd method
 *
 * @return void
 */
	public function testAdd() {
		$this->markTestIncomplete('testAdd not implemented.');
	}

/**
 * testEdit method
 *
 * @return void
 */
	public function testEdit() {
		$this->markTestIncomplete('testEdit not implemented.');
	}

}
