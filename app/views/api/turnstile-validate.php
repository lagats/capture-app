<?php

/* ---------------------------- *
 * Set the response headers
 * ---------------------------- */

header('Content-Type: application/json');



/* ---------------------------- *
 * Validate turnstile token
 * ---------------------------- */

if(Flight::request()->method == 'POST') {
    if(!turnstileEnabled()) {
        return;
    }
    if(turnstileValidated()) {
        return;
    }
    // varify against Cloudflare's turnstile
    $result = turnstileVarify();
    if($result['success'] === true) {
        turnstileSetValidated();
    }
}