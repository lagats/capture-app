// -------------------
// Turnstile helper functions
// -------------------

(function(){

    // Create capture object if it doesn't exist
    window.capture = window.capture || {};
    const capture = window.capture || {};

    // elements
    const deleteButton = document.getElementById('deleteButton');

    // Function to sent request to validate user
    async function deleteSelected() {
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
                window.location.reload();
            }
        };
        xhr.onerror = function() {
            console.error('Delete error:', xhr.statusText);
            window.location.reload();
        };
        // submit to the upload endpoint
        xhr.open('POST', '/media-delete', true);
        formData.append('delete_files', getCheckboxValuesAsJSON());
        // add tokens to the request
        const tokens = await capture.getTokens();
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