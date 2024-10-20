// -------------------
// Turnstile helper functions
// -------------------

(function(){

    // Create capture object if it doesn't exist
    window.capture = window.capture || {};
    const capture = window.capture || {};

    // Get tokens
    capture.getTokens = async function() {
        // get csrf_token
        const csrf_token_el = document.querySelector('.csrf-token');
        const csrf_token = csrf_token_el && csrf_token_el.getAttribute('data-sitekey');
        // generate turnstile response (https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/)
        const cf_turnstile = typeof capture !== 'undefined' && capture.validateAction && await capture.validateAction();
        // return tokens
        console.log(cf_turnstile);
        return {
            csrf_token,
            cf_turnstile,
        };
    };

})();