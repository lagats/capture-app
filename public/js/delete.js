// -------------------
// Turnstile helper functions
// -------------------

(function(){

    const deleteButton = document.getElementById('deleteButton');

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
    function deleteSelected() {
        const formData = new FormData();
        const xhr = new XMLHttpRequest();
        xhr.onload = function() {
            if (xhr.status === 200) {
                uploadProgress.textContent = 'Files deleted!';
                setTimeout(() => {
                    uploadProgress.classList.remove('visible');
                }, 300);
                window.location.reload();
            } else {
                console.error('Delete error:', xhr.statusText);
            }
        };
        // submit to the upload endpoint
        xhr.open('POST', '/media-delete', true);
        formData.append('delete_files', getCheckboxValuesAsJSON());
        // add tokens to the request
        const tokens = getTokens();
        Object.keys(tokens).forEach(function(key) {
            formData.append(key, tokens[key]);
        });
        // submit the request
        xhr.send(formData);
    }

    // Function to get selected items
    function getCheckboxValues() {
        const checkboxes = document.querySelectorAll('.masonry-checkbox');
        const checkboxValues = [];
      
        checkboxes.forEach((checkbox) => {
          if (checkbox.checked) {
            checkboxValues.push(checkbox.value);
          }
        });

        return checkboxValues;
    }

    // Function to get selected items
    function getCheckboxValuesAsJSON() {
        return JSON.stringify(getCheckboxValues());
    }

    // add event each time checkbox is checked
    window.addEventListener('checkboxChange', function(event) {
        if(getCheckboxValues().length > 0) {
            deleteButton.disabled = false;
        } else {
            deleteButton.disabled = true;
        }
    });

    // add click event to delete button
    deleteButton && deleteButton.addEventListener('click', function(event) {
        event.preventDefault();
        // Prompt the user for confirmation
        if (confirm("Delete the selected photos?")) {
            deleteSelected();
        }
    });

})();