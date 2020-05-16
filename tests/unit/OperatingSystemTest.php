<?php

namespace Tribe\SquareOne\Models;

use Codeception\Test\Unit;
use AspectMock\Test as test;

class OperatingSystemTest extends Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testArchFlavor() {
		$os = test::double( new OperatingSystem, [ 'readOsRelease' => 'Arch' ] );
		$this->assertSame( 'Arch', $os->getLinuxFlavor() );
	}

	public function testManjaroFlavor() {
		// Manjaro should return arch
		$os = test::double( new OperatingSystem, [ 'readOsRelease' => 'Arch' ] );
		$this->assertSame( 'Arch', $os->getLinuxFlavor() );
	}

	public function testUbuntuFlavor() {
		$os = test::double( new OperatingSystem, [ 'readOsRelease' => 'Ubuntu' ] );
		$this->assertSame( 'Ubuntu', $os->getLinuxFlavor() );
	}

	public function testDebianFlavor() {
		$os = test::double( new OperatingSystem, [ 'readOsRelease' => 'Debian' ] );
		$this->assertSame( 'Debian', $os->getLinuxFlavor() );
	}

	public function testZorinFlavor() {
		$os = test::double( new OperatingSystem, [ 'readOsRelease' => 'Ubuntu' ] );
		$this->assertSame( 'Ubuntu', $os->getLinuxFlavor() );
	}

	public function testShellExecFallback() {
		$os = test::double( new OperatingSystem, [ 'readOsRelease' => '' ] );
		test::func( '\\', 'shell_exec', 'Description:	Manjaro Linux' );
		$this->assertSame( 'Manjaro', $os->getLinuxFlavor() );
	}

}