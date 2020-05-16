<?php

use Codeception\Test\Unit;
use Filebase\Database;
use Tribe\SquareOne\Migrations\Migrator;


class MigrationTest extends Unit {
	/**
	 * @var \IntegrationTester
	 */
	protected $tester;

	/**
	 * An array of paths to migration files.
	 *
	 * @var array
	 */
	protected $migrationFiles;

	/**
	 * The Migrator.
	 *
	 * @var Migrator
	 */
	protected $migrator;

	public function setUp(): void {
		parent::setUp();

		// Purposely add these in the wrong order to make sure they're sorted later
		$this->migrationFiles = [
			codecept_data_dir( 'migrations/2020_05_16_285500_test_second_migration.php' ),
			codecept_data_dir( 'migrations/2020_05_16_265481_test_migration.php' ),
		];

		$db             = new Database( [
			'dir' => codecept_output_dir( 'migrations' ),
		] );
		$container      = new League\Container\Container();
		$this->migrator = new Migrator( $db, $container );
	}

	protected function _after() {
		parent::_after();

		$this->tester->deleteDir( codecept_output_dir( 'migrations' ) );
	}

	public function testMigratorRunsMigrations() {
		$migrations = $this->migrationFiles;
		$results    = $this->migrator->run( $migrations );

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

	public function testMigratorDoesNotRunExistingMigrations() {
		// Mock one of the test files
		$this->tester->writeToFile( codecept_output_dir( 'migrations' ) . '/2020_05_16_265481_test_migration.json', 'test' );

		$results = $this->migrator->run( $this->migrationFiles );

		$this->assertCount( 1, $results );

		$result = current( $results );

		$this->assertTrue( $result->success );
		$this->assertEquals( '2020_05_16_285500_test_second_migration.php', $result->migration );

		$migration = codecept_output_dir( 'migrations' ) . '/2020_05_16_285500_test_second_migration.json';
		$this->tester->assertFileExists( $migration );
		$this->tester->openFile( $migration );
		$this->tester->seeInThisFile( '__created_at' );

	}
}