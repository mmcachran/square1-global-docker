<?php

use Tribe\SquareOne\Migrations\MigrationCreator;

class MigrationCreatorTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testMigrationData() {
		$creator = new MigrationCreator();

		$name = 'testing_migration';
		$path = codecept_output_dir( '/migrations' );

		$data = $creator->getMigrationData( $name, $path );

		$this->assertStringContainsString( '_testing_migration.php', $data->path );
		$this->assertStringContainsString( 'final class TestingMigration', $data->content );

		$classFile = $creator->populateStub( $name, $creator->getStub() );
		$this->assertEquals( $classFile, $data->content );
	}
}