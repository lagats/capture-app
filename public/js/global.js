// -------------------
// Global functions
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
        return {
            csrf_token,
            cf_turnstile,
        };
    };
    
    // debounce wrapper (is actually using a throttle instead)
    // src -> https://dev.to/jeetvora331/throttling-in-javascript-easiest-explanation-1081
    capture.debounce = function(mainFunction, delay = 300) {
        let timerFlag = null; // Variable to keep track of the timer
      
        // Returning a throttled version 
        return (...args) => {
          if (timerFlag === null) { // If there is no timer currently running
            mainFunction(...args); // Execute the main function 
            timerFlag = setTimeout(() => { // Set a timer to clear the timerFlag after the specified delay
              timerFlag = null; // Clear the timerFlag to allow the main function to be executed again
            }, delay);
          }
        };
    }

})();