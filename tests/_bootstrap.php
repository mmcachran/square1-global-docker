<?php

$loader = require( __DIR__ . '/../vendor/autoload.php' );
$loader->add( 'AspectMock', __DIR__ . '/../src' );
$loader->register();

$kernel = \AspectMock\Kernel::getInstance();
$kernel->init( [
	'cacheDir'           => __DIR__ . '/_data/cache',
	'includePaths'       => [ __DIR__ . '/_data/demo', __DIR__ . '/../src' ],
	'interceptFunctions' => true
] );