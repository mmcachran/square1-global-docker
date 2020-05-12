<?php

use Codeception\Test\Unit;
use Tribe\SquareOne\Models\Certificate;

class CertificateTest extends Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected $cert;

	protected function _before() {
		$this->cert = ( new Certificate() )->setCertPath( codecept_data_dir( 'certs/test.crt' ) );
	}

	protected function _after() {
	}

	public function testCertExists() {
		$this->assertTrue( $this->cert->exists() );
	}

	public function testCertIsNotExpired() {
		$this->assertFalse( $this->cert->expired() );
	}

	public function testCertDoesNotExist() {
		$cert = ( new Certificate() )->setCertPath( codecept_data_dir( 'certs/doesnotexist.crt' ) );
		$this->assertFalse( $cert->exists() );
	}

	public function testCertIsExpired() {
		$cert = ( new Certificate() )->setCertPath( codecept_data_dir( 'certs/expired.crt' ) );
		$this->assertTrue( $cert->expired() );
	}
}