<?php

/*
 * Set validated value
 */
function turnstileSetValidated() {
    // Add status that user was validated by turnstile
    $session = Flight::session();
    $session->set('turnstile_validatated', Flight::get('app.timestamp'));
    $session->commit();
}

/**
 * Validate cookie timestamp when we validate the user last completed the turnstile challenge, 
 * if it's older than 1 hour, we'll invalidate the session and redirect to the turnstile challenge
 **/
function turnstileValidated() {
    $session = Flight::session();
    $turnstile_validatated = $session->getOrDefault('turnstile_validatated', 0);
    if($turnstile_validatated) {
        $time_difference = Flight::get('app.timestamp') - $turnstile_validatated;
        $valid_duration = 60 * 60;
        if($time_difference < $valid_duration) {
            return true;
        }
    }
    return false;
}

/**
 * Checks if the Turnstile feature is enabled and if the user has already validated the Turnstile challenge.
 */
function turnstileEnabled() {
    if(!Flight::get('config.turnstile.enabled') || turnstileValidated()) {
        return false;
    }
    return true;
}   

/**
 * Add turnstile script to the page
 **/
function turnstileScript() {
    if(!turnstileEnabled()) {
        return;
    }
    ?>
        <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'turnstile.css' . '?ver=' . uniqid(); ?>"></style>

        <script async defer src="https://challenges.cloudflare.com/turnstile/v0/api.js"></script>
        <script acync defer src="<?php echo Flight::get('public.js.url') . 'turnstile.js' . '?ver=' . uniqid(); ?>"></script>
    <?php
}

/**
 * Add turnstile element to the page
 **/
function turnstileElement() {
    if(!turnstileEnabled()) {
        return;
    }
    ?>
        <div class="cf-turnstile" data-sitekey="<?php echo (Flight::get('env')['TURNSTILE_KEY'] ?? ''); ?>"></div>
    <?php
}

/**
 * Verifies the Cloudflare Turnstile token submitted by the client.
 * (src -> https://clifford.io/blog/implement-cloudflare-turnstile-with-php/)
 * (doc -> https://developers.cloudflare.com/turnstile/get-started/server-side-validation/)
 *
 * This function checks if the Turnstile feature is enabled, and if so, it sends the
 * submitted token to the Cloudflare API for verification. It returns the response
 * from the Cloudflare API, which includes information about the verification status.
 *
 * @return array The Cloudflare API response, which includes a 'success' key indicating
 *               whether the verification was successful or not.
 */
function turnstileVarify() {
    if(!turnstileEnabled()) {
        return [
            "success" => true,
        ];
    }

    $secret = Flight::get('env')['TURNSTILE_SECRET']; /* Store this somewhere secure */
    $remote_addr = $_SERVER['REMOTE_ADDR'];
    $cf_url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    $token = Flight::request()->data->cf_turnstile;

    // Request data
    $data = array(
        "secret" => $secret,
        "response" => $token,
        "remoteip" => $remote_addr
    );

    // Initialize cURL
    $curl = curl_init();

    // Set the cURL options
    curl_setopt($curl, CURLOPT_URL, $cf_url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Execute the cURL request
    $response = curl_exec($curl);

    // Check for errors
    if (curl_errno($curl)) {
        $error_message = curl_error($curl);
        // Handle the error the way you like it
        echo 'cURL Error: ' . $error_message.'<br>';
    }else{
        /* Parse Cloudflare's response and check if there are any validation errors */
        $response = json_decode($response,true);
        if ($response['error-codes'] && count($response['error-codes']) > 0){
            return $response;
        }else{
            return $response;
        }
    }

    // Close cURL
    curl_close($curl);
}