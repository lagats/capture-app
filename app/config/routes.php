<?php

/* ---------------------------- *
 * Middleware
 * ---------------------------- */

// Middleware to run on regular pages
class AddCsrfMiddleware {
    public function before($params) {
        if(Flight::request()->method !== 'POST') {
            // You only need to generate a single token per session (so it works 
            // across multiple tabs and requests for the same user)
            $session = Flight::session();
            if($session->getOrDefault('csrf_token', null) === null) {
                $session->set('csrf_token', bin2hex(random_bytes(32)) );
                $session->commit();
            }
        }
    }
}

// This middleware checks if the request is a POST request and if it is, it checks if the CSRF token is valid
class CheckCsrfMiddleware 
{
    public function before(array $params): void 
    {
        if(Flight::request()->method == 'POST') {
            // capture the csrf token from the form values
            $token = Flight::request()->data->csrf_token;
            if($token !== Flight::session()->get('csrf_token')) {
                $result = [
                    "success" => false,
                    "error" => "Invalid CSRF token",
                ];
                Flight::jsonHalt($result, 403);
            }
        }
    }
}

// This middleware will check against Cloudflare's turnstile
class CheckTurnstileMiddleware
{
    public function before(array $params): void 
    {
        if(!turnstileEnabled()) {
            return;
        }
        if(Flight::request()->method == 'POST') {
            // varify against Cloudflare's turnstile
            $result = turnstileVarify();
            if($result['success'] !== true) {
                $result['error'] = 'Cloudflare Turnstile check failed';
                Flight::jsonHalt($result, 403);
            }
        }
    }
}



/* ---------------------------- *
 * Page Routes
 * ---------------------------- */
Flight::group('', function() {
    Flight::route('/', function () {
        // vars
        Flight::set('app.page.name',  ' | ' . 'Home');
        Flight::set('app.page.classnames',  'camera-page');
        
        // get template
        require(Flight::get('app.views.path') . 'capture.php');
    });
    Flight::route('/gallery', function () {
        // vars
        Flight::set('app.page.name',  ' | ' . 'Gallery');
        Flight::set('app.page.classnames',  'gallery-page gallery--all');
        
        // get template
        require(Flight::get('app.views.path') . 'gallery.php');
    });
    Flight::route('/mypics', function () {
        // vars
        Flight::set('app.page.name',  ' | ' . 'My Photos');
        Flight::set('app.page.classnames',  'gallery-page gallery--my_captures');

        // get template
        require(Flight::get('app.views.path') . 'gallery.php');
    });
}, [ new AddCsrfMiddleware() ]);



/* ---------------------------- *
 * API Routes
 * ---------------------------- */

Flight::group('', function() {
    Flight::route('POST /upload', function () {
        require(Flight::get('app.views.path') . 'api/upload.php');
    });
    Flight::route('GET /media', function () {
        require(Flight::get('app.views.path') . 'api/media.php');
    });
    Flight::route('POST /media-delete', function () {
        require(Flight::get('app.views.path') . 'api/media-delete.php');
    });
}, [ new CheckCsrfMiddleware(), new CheckTurnstileMiddleware() ]);



/* ---------------------------- *
 * Turnstile Routes
 * ---------------------------- */

Flight::group('', function() {
    Flight::route('POST /turnstile-validate', function () {
        require(Flight::get('app.views.path') . 'api/turnstile-validate.php');
    });
}, [ new CheckCsrfMiddleware() ]); /* ENSURE WE DONT USE 'CheckTurnstileMiddleware()' HERE */
