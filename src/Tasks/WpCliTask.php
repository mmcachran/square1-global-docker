<?php declare( strict_types=1 );

namespace Tribe\Sq1\Tasks;

use Robo\Robo;
use Tribe\Sq1\Models\LocalDocker;

/**
 * WP CLI Commands.
 *
 * @package Tribe\Sq1\Tasks
 */
class WpCliTask extends LocalDockerTask {

	/**
	 * Run WP CLI commands in the Local Container.
	 *
	 * @command wp
	 *
	 * @param  array  $args  The WP CLI command and arguments.
	 *
	 */
	public function wp( array $args ) {
		$this->taskDockerComposeExecute()
		     ->files( Robo::config()->get( LocalDocker::CONFIG_DOCKER_COMPOSE ) )
		     ->projectName( Robo::config()->get( LocalDocker::CONFIG_PROJECT_NAME ) )
		     ->setContainer( 'php-fpm' )
		     ->envVariable( 'WP_CLI_PHP_ARGS', '' )
		     ->exec( sprintf( 'wp --allow-root %s', trim( implode( ' ', $args ) ) ) )
		     ->run();
	}

	/**
	 * Run WP CLI commands in the Local Container with Xdebug enabled.
	 *
	 * @command wpx
	 *
	 * @param  array  $args  The WP CLI command and arguments.
	 *
	 */
	public function wpx( array $args ) {
		$projectName = Robo::config()->get( LocalDocker::CONFIG_PROJECT_NAME );

		$this->taskDockerComposeExecute()
		     ->files( Robo::config()->get( LocalDocker::CONFIG_DOCKER_COMPOSE ) )
		     ->projectName( $projectName )
		     ->setContainer( 'php-fpm' )
		     ->envVariable( 'PHP_IDE_CONFIG', "serverName=${projectName}.tribe" )
		     ->exec( sprintf( 'php -dxdebug.remote_autostart=1 -dxdebug.remote_host=host.tribe -dxdebug.remote_enable=1 /usr/local/bin/wp --allow-root %s',
			     trim( implode( ' ', $args ) ) ) )
		     ->run();
	}

}
