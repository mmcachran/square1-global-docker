<?php declare( strict_types=1 );

namespace Tribe\SquareOne\Migrations;

use stdClass;
use Filebase\Database;
use Illuminate\Support\Str;
use Psr\Container\ContainerInterface;

class Migrator {

	/**
	 * The migrations database
	 *
	 * @var Database
	 */
	protected $db;

	/**
	 * The container
	 *
	 * @var ContainerInterface
	 */
	protected $container;

	/**
	 * Migrator constructor.
	 *
	 * @param   Database            $db  The migrations database
	 * @param   ContainerInterface  $container The robo container
	 */
	public function __construct( Database $db, ContainerInterface $container ) {
		$this->db        = $db;
		$this->container = $container;
	}

	/**
	 * Run migrations
	 *
	 * @param   array  $migrations  An array of paths to migration files
	 *
	 * @return array An array of migration files that ran
	 */
	public function run( array $migrations = [] ): array {
		$results = [];

		// Migrations need to be run in order.
		sort( $migrations );

		foreach ( $migrations as $migration ) {
			$filename = basename( $migration );

			if ( ! $this->db->has( $filename ) ) {
				$this->require( $migration );
				$instance = $this->resolve( $migration );

				$result = new stdClass();

				if ( $instance->up() ) {
					$item = $this->db->get( $filename );
					$item->save();

					$result->success   = true;
					$result->migration = $filename;
				} else {
					$result->success   = false;
					$result->migration = $filename;
				}

				$results[] = $result;
			}
		}

		return $results;
	}

	/**
	 * Require a migration file
	 *
	 * @param   string  $file  The full path to the migration file
	 */
	public function require( string $file ): void {
		require $file;
	}

	/**
	 * Resolve a migration instance from a file.
	 *
	 * @param   string  $file
	 *
	 * @return Migration
	 */
	public function resolve( $file ): Migration {
		$file  = str_replace( '.php', '', $file );
		$class = Str::studly( implode( '_', array_slice( explode( '_', $file ), 4 ) ) );

		return new $class( $this->container );
	}
}