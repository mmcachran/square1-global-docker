<?php declare( strict_types=1 );

use Tribe\SquareOne\Migrations\Migration;

final class FixCertShPermissions extends Migration {

	/**
	 * Force cert.sh to +x for 2.0.0-beta users
	 *
	 * @return bool
	 */
	public function up(): bool {
		$config = $this->container->get( 'config' );

		return chmod( $config->get( 'docker.config-dir' ) . '/cert.sh', 0755 );
	}
}