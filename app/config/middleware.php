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
        if(turnstileValidated()) {
            return;
        }
        // varify against Cloudflare's turnstile
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