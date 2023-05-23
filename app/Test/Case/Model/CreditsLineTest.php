<?php
App::uses('CreditsLine', 'Model');

/**
 * CreditsLine Test Case
 */
class CreditsLineTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.credits_line',
		'app.collection_fee',
		'app.credit'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CreditsLine = ClassRegistry::init('CreditsLine');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CreditsLine);

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
