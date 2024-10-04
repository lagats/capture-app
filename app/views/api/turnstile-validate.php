<?php


/* ---------------------------- *
 * Validate turnstile token
 * ---------------------------- */


 if(Flight::request()->method == 'POST') {
    // varify against Cloudflare's turnstile
    $result = turnstileVarify();
    if($result['success'] === true) {
        // Add status that user was validated by turnstile
        $session = Flight::session();
        $session->set('turnstile_validatated', Flight::get('app.timestamp'));
        $session->commit();
    }
}