// -------------------
// Turnstile helper functions
// -------------------

(function(){

    // Create capture object if it doesn't exist
    window.capture = window.capture || {};
    const capture = window.capture || {};

    // Validate on inital pageload
    const validateOnPageload = false;

    // Function to sent request to validate user
    async function validateUser() {
        const formData = new FormData();
        const xhr = new XMLHttpRequest();
        // submit to the upload endpoint
        xhr.open('POST', '/turnstile-validate', true);
        // add tokens to the request
        const tokens = await capture.getTokens();
        Object.keys(tokens).forEach(function(key) {
            formData.append(key, tokens[key]);
        });
        // submit the request
        xhr.send(formData);
    }
    
    // Function to validate an action 
    // (src => https://community.cloudflare.com/t/using-turnstile-within-a-form-loaded-via-ajax/606667)
    capture.validateAction = async function() {
        // get the widget validated token
        return new Promise((resolve, reject) => {
            const widget = document.querySelector('.cf-turnstile');
            window.turnstile.render(widget, {
                sitekey: widget.getAttribute('data-sitekey'),
                callback: function(token) {
                    // return token
                    resolve(token);
                    // reset key so we dont reuse duplicate values
                    window.turnstile.reset(widget);
                },
            });
        });
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
    capture.checkTurnstileResponse = async function() {
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
                    if(validateOnPageload) {
                        validateUser();
                    }
                    hasValidated = true;
                }
            }
        } catch (error) {
            if (typeof turnstile !== 'undefined') {
                console.error("Error checking turnstile value:", error);
            }
        }
    }
    setInterval(capture.checkTurnstileResponse, 1000); // Check every 10 seconds

})();