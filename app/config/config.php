<?php

// Set the default timezone
date_default_timezone_set('Australia/Sydney');

// Set the error reporting level
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/error.log');

// Set the default character encoding
if(function_exists('mb_internal_encoding') === true) {
	mb_internal_encoding('UTF-8');
}

// Set the default locale
if(function_exists('setlocale') === true) {
	setlocale(LC_ALL, 'en_US.UTF-8');
}

// Get the $app var to use below
if(empty($app)) {
	$app = Flight::app();
}

// This autoloads your code in the app directory so you don't have to require_once everything
$app_root = realpath(__DIR__ . $ds . '..' . $ds . 'app');
$app->path($app_root);

// app env
$app->set('env', parse_ini_file($app_root . '.env'));

// Flight config variables. 
$app->set('flight.base_url', 'https://capture.lagats.com/'); // if this is in a subdirectory, you'll need to change this
$app->set('flight.case_sensitive', false); // if you want case sensitive routes, set this to true
$app->set('flight.log_errors', true); // if you want to log errors, set this to true
$app->set('flight.handle_errors', true); // if you want flight to handle errors, set this to true, otherwise Tracy will handle them
$app->set('flight.content_length', true); // if flight should send a content length header

// dev mode
$app->set('app.devmode', true);

// app paths
$app->set('app.path', realpath(__DIR__ . $ds . '..' . $ds) . $ds);
$app->set('app.config.path', __DIR__ . $ds);
$app->set('app.views.path', Flight::get('app.path') . 'views' . $ds);
$app->set('app.icon.path', Flight::get('app.path') . 'icons' . $ds);

// public paths
$app->set('public.path', realpath(Flight::get('app.path') . '..' . $ds . 'public' . $ds) . $ds);
$app->set('public.upload.path', Flight::get('public.path') . 'uploads' . $ds);
$app->set('public.thumbnail.path', Flight::get('public.path') . 'thumbnails' . $ds);
$app->set('public.js.path', Flight::get('public.path') . 'js' . $ds);
$app->set('public.css.path', Flight::get('public.path') . 'css' . $ds);

// public urls
$app->set('public.url', Flight::get('flight.base_url') . 'public/');
$app->set('public.upload.url', Flight::get('public.url') . 'uploads/');
$app->set('public.thumbnail.url', Flight::get('public.url') . 'thumbnails/');
$app->set('public.js.url', Flight::get('public.url') . 'js/');
$app->set('public.css.url', Flight::get('public.url') . 'css/');

// other app vars
$date = new DateTime();
$app->set('app.date', $date);
$app->set('app.timestamp', $date->getTimestamp());
$app->set('app.sitename', 'Snap-a-Lagat');
$app->set('app.allow.media', ['jpg', 'jpeg', 'png']);

// set turnstile var
$app->set('config.turnstile.enabled', (
	(Flight::get('env')['TURNSTILE_KEY'] ?? false)
	&& (Flight::get('env')['TURNSTILE_SECRET'] ?? false)
));

/* 
 * This is where you will store database credentials, api credentials
 * and other sensitive information. This file will not be tracked by git
 * as you shouldn't be pushing sensitive information to a public or private
 * repository.
 * 
 * What you store here is totally up to you.
 */
return [
	'database' => [
		// uncomment the below 4 lines for mysql
		// 'host' => 'localhost',
		// 'dbname' => 'dbname',
		// 'user' => 'user',
		// 'password' => 'password'

		// uncomment the following line for sqlite
		// 'file_path' => __DIR__ . $ds . '..' . $ds . 'database.sqlite'
	],
];