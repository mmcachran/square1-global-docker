<?php

use Codeception\Test\Unit;
use League\Container\Container;

class MigrationTest extends Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testMigrationFactory() {
		$app = new class() {
			public function getVersion() {
				return '2.0.0-beta';
			}
		};

		$container = new Container();
		$container->add( 'application', $app );
	}
}