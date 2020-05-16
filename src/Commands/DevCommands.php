<?php declare( strict_types=1 );

namespace Tribe\SquareOne\Commands;

use Illuminate\Support\Str;
use Tribe\SquareOne\Contracts\MigrationCreatorAwareInterface;
use Tribe\SquareOne\Migrations\MigrationCreator;

/**
 * Development commands only visible when not running as a phar executable.
 *
 * @package Tribe\SquareOne\Commands
 */
class DevCommands extends SquareOneCommand implements MigrationCreatorAwareInterface {

	/**
	 * @var MigrationCreator
	 */
	protected $migrationCreator;

	/**
	 * Creates a new migration file
	 *
	 * @command dev:create-migration
	 */
	public function createMigration() {
		$name = $this->ask( 'Enter the name of this migration' );

		$name = Str::snake( trim( $name ) );

		$migration = $this->migrationCreator->getMigrationData( $name, "{$this->scriptPath}/migrations" );

		$result = $this->taskWriteToFile( $migration->path )
		               ->text( $migration->content )
		               ->run();

		if ( $result->wasSuccessful() ) {
			$this->say( sprintf( 'Created new migration: %s', $migration->path ) );
		} else {
			$this->say( sprintf( 'Unable to create migration: %s', $migration->path ) );
		}
	}

	/**
	 * Set the Migration Creator via inflection
	 *
	 * @param   MigrationCreator  $migrationCreator
	 */
	public function setMigrationCreator( MigrationCreator $migrationCreator ): void {
		$this->migrationCreator = $migrationCreator;
	}


}