<?php

use Codeception\Test\Unit;
use Filebase\Database;
use Tribe\SquareOne\Migrations\Migrator;


class MigrationTest extends Unit {
	/**
	 * @var \IntegrationTester
	 */
	protected $tester;

	protected $migrationFiles;

	public function setUp(): void {
		parent::setUp();

		// Purposely add these in the wrong order to make sure they're sorted later
		$this->migrationFiles = [
			codecept_data_dir( 'migrations/2020_05_16_285500_test_second_migration.php' ),
			codecept_data_dir( 'migrations/2020_05_16_265481_test_migration.php' ),
		];
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

		$migrations = $this->migrationFiles;

		$results = $migrator->run( $migrations );

		$this->assertCount( 2, $results );

		// The earlier migration should have run first
		$this->assertTrue( $results[0]->success );
		$this->assertEquals( '2020_05_16_265481_test_migration.php', $results[0]->migration );

		$this->assertTrue( $results[1]->success );
		$this->assertEquals( '2020_05_16_285500_test_second_migration.php', $results[1]->migration );

		$migration_1 = codecept_output_dir( 'migrations' ) . '/2020_05_16_265481_test_migration.json';
		$this->tester->assertFileExists( $migration_1 );
		$this->tester->openFile( $migration_1 );
		$this->tester->seeInThisFile( '__created_at' );

		$migration_2 = codecept_output_dir( 'migrations' ) . '/2020_05_16_285500_test_second_migration.json';
		$this->tester->assertFileExists( $migration_2 );
		$this->tester->openFile( $migration_2 );
		$this->tester->seeInThisFile( '__created_at' );
	}
}