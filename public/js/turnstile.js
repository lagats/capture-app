// -------------------
// Turnstile helper functions
// -------------------

(function(){
    
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
            }
        } catch (error) {
            if (typeof turnstile !== 'undefined') {
                console.error("Error checking turnstile value:", error);
            }
        }
    }
    setInterval(checkTurnstileResponse, 1000); // Check every 10 seconds

})();