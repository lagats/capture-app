// -------------------
// Turnstile helper functions
// -------------------

(function(){

    // Get tokens
    function getTokens() {
        // get csrf_token
        const csrf_token_el = document.querySelector('.csrf-token');
        const csrf_token = csrf_token_el && csrf_token_el.getAttribute('data-sitekey');
        // generate turnstile response (https://developers.cloudflare.com/turnstile/get-started/client-side-rendering/)
        const cf_turnstile = typeof turnstile !== 'undefined' && turnstile.getResponse && turnstile.getResponse();
        // return tokens
        return {
            csrf_token,
            cf_turnstile,
        };
    }

    // Function to sent request to validate user
    function validateUser() {
        const formData = new FormData();
        const xhr = new XMLHttpRequest();
        // submit to the upload endpoint
        xhr.open('POST', '/turnstile-validate', true);
        // add tokens to the request
        const tokens = getTokens();
        Object.keys(tokens).forEach(function(key) {
            formData.append(key, tokens[key]);
        });
        // submit the request
        xhr.send(formData);
    }
    
    // Check if turnstile is valid, reload if not
    async function checkTurnstileExpiration() {
        try {
            const isExpired = await turnstile.isExpired();
            if (isExpired) {
                // Handle token expiration here (e.g., redirect to login page)
                console.log("Turnstile token has expired. Realoading page...");
                window.location.reload();
            } else {
                // console.log("Turnstile token is valid.");
            }
        } catch (error) {
            if (typeof turnstile !== 'undefined') {
                console.error("Error checking turnstile expiration:", error);
            }
        }
    }
    setInterval(checkTurnstileExpiration, 30000); // Check every 10 seconds
    
    // Check if turnstile has a response value avaliable for submit (and doesnt need us to interact with it)
    let hasValidated = false;
    let previousTurnstileEvent;
    async function checkTurnstileResponse() {
        try {
            const hasResponse = turnstile.getResponse();
            if (!hasResponse) {
                // Handle token expiration here (e.g., redirect to login page)
                // console.log("Turnstile value is required. Showing field...");
                document.querySelector('.cf-turnstile').classList.add('infront');
                // trigger event
                const thisEvent = "turnstileOverlayShow";
                if(previousTurnstileEvent !== thisEvent) {
                    const event = new Event(thisEvent);
                    window.dispatchEvent(event);
                    previousTurnstileEvent = thisEvent;
                }
            } else {
                // console.log("Turnstile value is valid.");
                document.querySelector('.cf-turnstile').classList.remove('infront');
                // trigger event
                const thisEvent = "turnstileOverlayHide";
                if(previousTurnstileEvent !== thisEvent) {
                    const event = new Event(thisEvent);
                    window.dispatchEvent(event);
                    previousTurnstileEvent = thisEvent;
                }
                // send validation when we have a valid response
                if(!hasValidated) {
                    validateUser();
                    hasValidated = true;
                }
            }
        } catch (error) {
            if (typeof turnstile !== 'undefined') {
                console.error("Error checking turnstile value:", error);
            }
        }
    }
    setInterval(checkTurnstileResponse, 1000); // Check every 10 seconds

})();