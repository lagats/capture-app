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

// Add Header/Footer
class HeaderFooterMiddleware
{
    public function after(array $params): void 
    {
        require(Flight::get('app.views.path') . 'partial/head.php');
        $template = Flight::get('app.page.template');
        if ($template) {
            require($template);
        }
        require(Flight::get('app.views.path') . 'partial/footer.php');
    }
}


/* ---------------------------- *
 * Page Routes
 * ---------------------------- */
Flight::group('', function() {
    
    /* ---------------------------- *
     * Capture Page
     * ---------------------------- */
    
    Flight::route('', function () {
        // styles
        Flight::set('enqueue.stylesheets', [
            [ 'file' => 'reset.css' ],
            [ 'file' => 'global.css' ],
            [ 'file' => 'capture.css' ],
        ]);
        
        // scripts
        Flight::set('enqueue.scripts', [
            [ 'file' => 'vendor/fslightbox.min.js', 'defer' => true ],
            [ 'file' => 'gallery.js', 'defer' => true ],
            [ 'file' => 'delete.js', 'defer' => true ],
            [ 'file' => 'capture.js', 'defer' => true ],
        ]);
        
        // get template
        Flight::set('app.page.template', Flight::get('app.views.path') . 'capture.php');
        
        // vars
        Flight::set('app.page.name', ' | ' . 'Home');
        Flight::set('app.page.classnames', 'camera-page');
    });
    
    /* ---------------------------- *
     * Gallery Pages
     * ---------------------------- */
    
    Flight::group('', function() {
        // styles
        Flight::set('enqueue.stylesheets', [
            [ 'file' => 'reset.css' ],
            [ 'file' => 'global.css' ],
            [ 'file' => 'capture.css' ],
            [ 'file' => 'gallery.css' ],
        ]);
        
        // scripts
        Flight::set('enqueue.scripts', [
            [ 'file' => 'vendor/fslightbox.min.js', 'defer' => true ],
            [ 'file' => 'gallery.js', 'defer' => true ],
            [ 'file' => 'delete.js', 'defer' => true ],
            [ 'file' => 'capture.js',  'defer' => true ],
        ]);
        
        // get template
        Flight::set('app.page.template', Flight::get('app.views.path') . 'gallery.php');
        
        // gallery routes
        Flight::route('/gallery', function () {
            // vars
            Flight::set('app.page.name', ' | ' . 'Gallery');
            Flight::set('app.page.classnames', 'gallery-page gallery--all');
        });
        Flight::route('/mypics', function () {
            // vars
            Flight::set('app.page.name', ' | ' . 'My Photos');
            Flight::set('app.page.classnames', 'gallery-page gallery--my_captures');
        }); 
    });
    
}, [ new AddCsrfMiddleware(), new HeaderFooterMiddleware() ]);



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
