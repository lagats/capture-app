<?php


/* ---------------------------- *
 * Validate turnstile token
 * ---------------------------- */

if(Flight::request()->method == 'POST') {
    // varify against Cloudflare's turnstile
    $result = turnstileVarify();
    if($result['success'] === true) {
        turnstileSetValidated();
    }
}