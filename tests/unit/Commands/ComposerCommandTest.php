<?php

namespace SquareOne;

use Tribe\SquareOne\Commands\ComposerCommands;

class ComposerCommandTest extends Command {

	/**
	 * Prepare to test our commandfile
	 */
	public function setUp(): void {
		// Store the command classes we are going to test
		$this->commandClasses = [ ComposerCommands::class ];
		$this->setupCommandTester( 'SquareOneTestApp', '1.0.1' );
	}

	/**
	 * Data provider for testComposerCommand.
	 *
	 * Return an array of arrays, each of which contains the data for one test.
	 * The parameters in each array should be:
	 *
	 *   - Expected output (actual output must CONTAIN this string)
	 *   - Expected function status code
	 *   - argv
	 *
	 * All of the remaining parameters after the first two are interpreted
	 * to be the argv value to pass to the command. The application name
	 * is automatically unshifted into argv[0] first.
	 */
	public function composerCommandParameters() {
		return [
			[
				"[Simulator] Simulating Droath\RoboDockerCompose\Task\Execute(null)
    ->files(array ( ... ))
    ->projectName('test-project')
    ->setContainer('php-fpm')
    ->exec('composer 'install' -d /application/www')",
				self::STATUS_OK,
				'composer', '--simulate', '--', 'install'
			],
		];
	}

	/**
	 * Test our example commandfile class. Each time this function is called,
	 * it will be passed the expected output and expected status code; the
	 * remainder of the arguments passed will be used as $argv.
	 *
	 * @dataProvider composerCommandParameters
	 *
	 * @param $expectedOutput
	 * @param $expectedStatus
	 * @param $variable_args
	 */
	public function testComposerCommand( $expectedOutput, $expectedStatus, $variable_args ) {
		// Create our argv array and run the command
		$argv = $this->argv( func_get_args() );

		list( $actualOutput, $statusCode ) = $this->execute( $argv, $this->commandClasses, $this->configurationFile );

		// Confirm that our output and status code match expectations
		$this->assertEquals( $expectedOutput, $actualOutput );
		$this->assertEquals( $expectedStatus, $statusCode );
	}
}