<?php declare( strict_types=1 );

namespace Tribe\SquareOne\Migrations;

use Illuminate\Support\Str;
use stdClass;
use Tribe\SquareOne\Exceptions\SquareOneException;

/**
 * Class MigrationCreator
 *
 * @package Tribe\SquareOne\Migrations
 */
class MigrationCreator {

	/**
	 * Get the path and content to create a migration file
	 *
	 * @param   string  $name The snake_case'd name of the migration
	 * @param   string  $path The path to where migrations will be created
	 *
	 * @return object
	 *
	 * @throws SquareOneException
	 */
	public function getMigrationData( string $name, string $path ): object {
		$this->checkForExistingMigration( $name, $path );

		$content = $this->populateStub( $name, $this->getStub() );
		$path    = $this->getPath( $name, $path );

		$migration          = new stdClass();
		$migration->path    = $path;
		$migration->content = $content;

		return $migration;
	}

	/**
	 * Check if a migration exists.
	 *
	 * @param   string  $name
	 * @param   string  $path
	 *
	 * @throws SquareOneException
	 */
	protected function checkForExistingMigration( string $name, string $path ) {

		foreach ( glob( "{$path}/*.php" ) as $file ) {
			require_once $file;
		}

		if ( class_exists( $className = $this->getClassName( $name ) ) ) {
			throw new SquareOneException( "{$className} already exists." );
		}
	}

	/**
	 * Get the stub content for migration files
	 *
	 * @return string
	 */
	public function getStub(): string {
		return file_get_contents( $this->getStubPath() . '/migration.stub' );
	}

	/**
	 * Get the path to our stubs
	 *
	 * @return string
	 */
	protected function getStubPath(): string {
		return __DIR__ . '/stubs';
	}

	/**
	 * Get the migration file path
	 *
	 * @param   string  $name
	 * @param   string  $path
	 *
	 * @return string
	 */
	protected function getPath( string $name, string $path ): string {
		return $path . '/' . $this->getDatePrefix() . '_' . $name . '.php';
	}

	/**
	 * Populate the stub file
	 *
	 * @param   string  $name
	 * @param   string  $stub
	 *
	 * @return string|string[]
	 */
	public function populateStub( string $name, string $stub ) {
		return str_replace( '{{ class }}', $this->getClassName( $name ), $stub );
	}

	/**
	 * Get the class name of a migration name.
	 *
	 * @param   string  $name
	 *
	 * @return string
	 */
	protected function getClassName( $name ): string {
		return Str::studly( $name );
	}

	/**
	 * Get the date prefix for the migration.
	 *
	 * @return string
	 */
	protected function getDatePrefix(): string {
		return date( 'Y_m_d_His' );
	}
}