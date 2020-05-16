<?php declare( strict_types=1 );

namespace Tribe\SquareOne\Contracts;

use Tribe\SquareOne\Migrations\MigrationCreator;

/**
 * Interface MigrationCreatorAwareInterface
 *
 * @package Tribe\SquareOne\Contracts
 */
interface MigrationCreatorAwareInterface {


	/**
	 * Set the Migration Creator via inflection.
	 *
	 * @param   MigrationCreator  $migrationCreator
	 */
	public function setMigrationCreator( MigrationCreator $migrationCreator ): void;

}
