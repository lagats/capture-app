<?php

/* ---------------------------- *
 * Middleware
 * ---------------------------- */

class Middleware {
    public function before($params) {
        // $session = Flight::session();
        // print_r($session->id());
    }
    public function after($params) {
    }
}
$Middleware = new Middleware();


/* ---------------------------- *
 * Routes
 * ---------------------------- */

Flight::route('/', function () {
    require(Flight::get('app.views.path') . 'capture.php');
})->addMiddleware($Middleware);

Flight::route('/gallery', function () {
    require(Flight::get('app.views.path') . 'gallery.php');
})->addMiddleware($Middleware);

Flight::route('GET /media', function () {
    require(Flight::get('app.views.path') . 'media.php');
});

Flight::route('POST /upload', function () {
    require(Flight::get('app.views.path') . 'upload.php');
});
