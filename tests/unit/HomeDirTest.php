<?php

use Codeception\Test\Unit;
use Tribe\SquareOne\Util\HomeDir;

class HomeDirTest extends Unit {

	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testItFindsAHomeDir() {
		$home = putenv( 'HOME=/tmp' );

		$this->assertTrue( $home );

		$homeDir = ( new HomeDir() )->get();

		$this->assertEquals( '/tmp', $homeDir );
	}

}
