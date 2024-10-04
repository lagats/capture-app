<?php

// Load PackageLoader (src -> https://github.com/Wilkins/composer-file-loader/tree/master)
include __DIR__ . $ds . 'composer-file-loader' . $ds . 'PackageLoader.php';
$loader = new PackageLoader\PackageLoader();

// Load Flight
require(__DIR__ . $ds . 'flight' . $ds . 'Flight.php');

// Load Packages
$loader->load(__DIR__ . $ds . 'session');
$loader->load(__DIR__ . $ds . 'php-image-resize');
