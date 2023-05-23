<?php
App::uses('CreditsLinesDetail', 'Model');

/**
 * CreditsLinesDetail Test Case
 */
class CreditsLinesDetailTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.credits_lines_detail',
		'app.credit_line'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->CreditsLinesDetail = ClassRegistry::init('CreditsLinesDetail');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->CreditsLinesDetail);

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
