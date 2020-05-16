<?php declare( strict_types=1 );

namespace Tribe\SquareOne\Hooks;

use Robo\Robo;
use Filebase\Database;
use Composer\Semver\Comparator;
use League\Container\ContainerAwareInterface;
use League\Container\ContainerAwareTrait;
use Consolidation\AnnotatedCommand\AnnotationData;
use Symfony\Component\Console\Input\InputInterface;
use Tribe\SquareOne\Commands\UpdateCommands;
use Tribe\SquareOne\Migrations\Migrator;

/**
 * Update Hooks
 *
 * @package Tribe\SquareOne\Hooks
 */
class Update implements ContainerAwareInterface {

	use ContainerAwareTrait;

	public const INSTALLED_VERSION_FILE = '.version';

	/**
	 * The current phar version
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * The migrations Flat-file database
	 *
	 * @var Database
	 */
	protected $migrations;

	/**
	 * The root script path
	 *
	 * @var string
	 */
	protected $scriptPath;

	/**
	 * Run migrations
	 *
	 * @hook pre-init *
	 *
	 * @param   \Symfony\Component\Console\Input\InputInterface  $input
	 * @param   \Consolidation\AnnotatedCommand\AnnotationData   $data
	 */
	public function migrate( InputInterface $input, AnnotationData $data ): void {
		$command = $data->get( 'command' );

		if ( 'self:update-check' !== $command && 'self:update' !== $command ) {
			$versionFile = sprintf( '%s/%s', Robo::config()->get( 'squareone.config-dir' ), self::INSTALLED_VERSION_FILE );

			if ( ! file_exists( $versionFile ) ) {
				$shouldUpdate = true;
			} else {
				$version      = file_get_contents( $versionFile );
				$shouldUpdate = Comparator::greaterThan( $this->version, $version );
			}

			if ( $shouldUpdate ) {

				$output     = Robo::output();
				$migrations = [];

				foreach ( glob( "{$this->scriptPath}/migrations/*.php" ) as $file ) {
					$migrations[] = $file;
				}

				if ( $migrations ) {
					$migrator = new Migrator( $this->migrations, $this->container );
					$results  = $migrator->run( $migrations );

					if ( ! empty( $results ) ) {
						$output->writeln( '<info>Running migrations...</info>' );

						foreach ( $results as $result ) {
							if ( $result->success ) {
								$output->writeln( sprintf( '<info>Completed migration: %s</info>', $result->migration ) );
							} else {
								$output->writeln( sprintf( '<error>Migration failed: %s</error>', $result->migration ) );
							}
						}
					}

					$this->writeVersion( $versionFile );

				} else {
					$this->writeVersion( $versionFile );
				}
			}
		}
	}

	/**
	 * Write the current version to the .version file
	 *
	 * @param   string  $versionFile  The path to the .version file
	 *
	 * @return bool
	 */
	protected function writeVersion( string $versionFile ): bool {
		return (bool) file_put_contents( $versionFile, $this->version );
	}

	/**
	 * Check for Updates when commands are run
	 *
	 * @hook init *
	 *
	 * @param   \Symfony\Component\Console\Input\InputInterface  $input
	 * @param   \Consolidation\AnnotatedCommand\AnnotationData   $data
	 */
	public function check( InputInterface $input, AnnotationData $data ): void {
		$command = $data->get( 'command' );

		if ( 'self:update-check' !== $command && 'self:update' !== $command ) {
			/** @var UpdateCommands $update */
			$update = $this->container->get( UpdateCommands::class . 'Commands' );
			$update->updateCheck( [ 'show-existing' => false ] );
		}
	}

	/**
	 * Set the current version, set via inflection
	 *
	 * @param   string  $version  The current phar version
	 *
	 * @return \Tribe\SquareOne\Hooks\Update
	 */
	public function setVersion( string $version ): self {
		$this->version = $version;

		return $this;
	}

	/**
	 * Set the Migrations flat-file database via inflection
	 *
	 * @param   Database  $migrations  The migrations flat-file database.
	 *
	 * @return $this
	 */
	public function setMigrations( Database $migrations ): self {
		$this->migrations = $migrations;

		return $this;
	}

	/**
	 * Set the script path via inflection
	 *
	 * @param   string  $scriptPath
	 *
	 * @return $this
	 */
	public function setScriptPath( string $scriptPath ): self {
		$this->scriptPath = $scriptPath;

		return $this;
	}

}
