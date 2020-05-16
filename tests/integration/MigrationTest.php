<?php

use Codeception\Test\Unit;
use Filebase\Database;
use Tribe\SquareOne\Migrations\Migrator;


class MigrationTest extends Unit {
	/**
	 * @var \IntegrationTester
	 */
	protected $tester;

	protected $migrationFile;

	public function setUp(): void {
		parent::setUp();

		$this->migrationFile = codecept_data_dir( 'migrations/2020_05_16_265481_test_migration.php' );
	}

	protected function _after() {
		parent::_after();

		$this->tester->deleteDir( codecept_output_dir( 'migrations' ) );
	}

	public function testMigratorRunsAMigration() {
		$db        = new Database( [
			'dir' => codecept_output_dir( 'migrations' ),
		] );
		$container = new League\Container\Container();
		$migrator  = new Migrator( $db, $container );

		$migrations = [
			$this->migrationFile,
		];

		$results = $migrator->run( $migrations );

		$this->assertCount( 1, $results );

		$result = current( $results );

		$this->assertTrue( $result->success );
		$this->assertEquals( '2020_05_16_265481_test_migration.php', $result->migration );

		$file = codecept_output_dir( 'migrations' ) . '/2020_05_16_265481_test_migration.json';
		$this->tester->assertFileExists( $file );
		$this->tester->openFile( $file );
		$this->tester->seeInThisFile( '__created_at' );
	}
}