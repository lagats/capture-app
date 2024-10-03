<?php

/* ---------------------------- *
 * Middleware
 * ---------------------------- */

class PageMiddleware {
    public function before($params) {
        // $session = Flight::session();
        // print_r($session->id());
    }
    public function after($params) {
    }
}
$PageMiddleware = new PageMiddleware();



/* ---------------------------- *
 * Routes
 * ---------------------------- */

Flight::route('/', function () {
    // vars
    Flight::set('app.page.name',  ' | ' . 'Home');
    Flight::set('app.page.classnames',  'camera-page');
    
    // get template
    require(Flight::get('app.views.path') . 'capture.php');
})->addMiddleware($PageMiddleware);

Flight::route('/gallery', function () {
    // vars
    Flight::set('app.page.name',  ' | ' . 'Gallery');
    Flight::set('app.page.classnames',  'gallery-page gallery--all');
    
    // get template
    require(Flight::get('app.views.path') . 'gallery.php');
})->addMiddleware($PageMiddleware);

Flight::route('/mypics', function () {
    // vars
    Flight::set('app.page.name',  ' | ' . 'My Photos');
    Flight::set('app.page.classnames',  'gallery-page gallery--me');

    // get template
    require(Flight::get('app.views.path') . 'gallery.php');
})->addMiddleware($PageMiddleware);



/* ---------------------------- *
 * API Routes
 * ---------------------------- */

Flight::route('POST /upload', function () {
    require(Flight::get('app.views.path') . 'api/upload.php');
});

Flight::route('GET /media', function () {
    require(Flight::get('app.views.path') . 'api/media.php');
});
