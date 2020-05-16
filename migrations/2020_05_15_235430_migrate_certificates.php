<?php declare( strict_types=1 );

use Tribe\SquareOne\Migrations\Migration;
use Symfony\Component\Filesystem\Filesystem;


final class MigrateCertificates extends Migration {

	/**
	 * Run the Migration
	 *
	 * @return bool
	 */
	public function up(): bool {
		$config         = $this->container->get( 'config' );
		$newCertsFolder = $config->get( 'docker.certs-folder' );
		$oldCertsFolder = str_replace( 'squareone', 'sq1', $newCertsFolder );

		// Copy SSL certificates to new config directory
		if ( is_dir( $oldCertsFolder ) && ! is_dir( $newCertsFolder ) ) {
			$filesystem = new Filesystem();
			$filesystem->mirror( $oldCertsFolder, $newCertsFolder );
		}

		return true;
	}
}