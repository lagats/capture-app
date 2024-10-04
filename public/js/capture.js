// -------------------
// Photo capture app
// -------------------

(function(){

    // Get references to HTML elements
    const video = document.getElementById('video');
    const cameraSelect = document.getElementById('cameraSelect');
    const takePhotoButton = document.getElementById('takePhotoButton');
    const uploadProgress = document.getElementById('uploadProgress');
    const uploadPreview = document.getElementById('uploadPreview');

    // Camera facing mode
    let cameraFacingIndex = 0;
    const cameraFacingModes = [
        'environment',
        'user',
    ];

    // State
    let videoActive = false;

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

    // Function to start the video stream using the selected camera
    function startVideo(facingMode = cameraFacingModes[cameraFacingIndex]) {
        if(!video){
            return
        }
        navigator.mediaDevices.getUserMedia({ video: {
            facingMode,
            width: { ideal: 4096 },
            height: { ideal: 2160 }
        } })
        .then(stream => {
            console.log("Video stream started successfully");
            // Set the video source to the stream
            video.srcObject = stream;
            video.setAttribute('autoplay', '');
            video.setAttribute('muted', '');
            video.setAttribute('playsinline', '');
            // Set class and value to track state
            cameraView.classList.remove('video-error');
            videoActive = true;
        })
        .catch(error => {
            console.error('Error starting video:', error);
            // Set class and value to track state
            cameraView.classList.add('video-error');
            videoActive = false;
        });
    }    
    
    // Function to stop the video stream
    function stopVideo() {
        if(!video){
            return
        }
        const tracks = video.srcObject && video.srcObject.getTracks();
        if(tracks && tracks.length > 0) {
            tracks.forEach(track => track.stop());
        }
    }

    // Update image preview
    function previewImage(dataURL) {
        uploadPreview.innerHTML = `<img src="${dataURL}">`;
    }

    // Function to upload the image to the server
    function uploadFormData(formData, reload = false) {
        const xhr = new XMLHttpRequest();
        // submit to the upload endpoint
        xhr.open('POST', '/upload', true);
        // add request callbacks
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const progress = (event.loaded / event.total) * 100;
                uploadProgress.textContent = `Uploading: ${progress.toFixed(2)}%`;
            }
        };
        xhr.onload = function() {
            if (xhr.status === 200) {
                // const response = JSON.parse(xhr.responseText);
                uploadProgress.textContent = 'Upload complete!';
                if(reload) {
                    window.location.reload();
                }
            } else {
                console.error('Upload error:', xhr.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Upload error:', xhr.statusText);
        };
        xhr.onabort = function() {
            console.log('Upload canceled');
        };
        // add tokens to the request
        const tokens = getTokens();
        Object.keys(tokens).forEach(function(key) {
            formData.append(key, tokens[key]);
        });
        // submit the request
        xhr.send(formData);
    }

    // Check if canvas is black
    function isCanvasCompletelyBlack(canvas) {
        const context = canvas.getContext('2d');
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const data = imageData.data;
        for (let i = 0; i < data.length; i += 4) {
            if (data[i] !== 0 || data[i + 1] !== 0 || data[i + 2] !== 0) {
                return false; // At least one pixel is not black
            }
        }
        return true; // All pixels are black
    }

    // Event listener for camera selection change
    if(cameraSelect) {
        cameraSelect.addEventListener('click', () => {
            cameraFacingIndex = (cameraFacingIndex + 1) % cameraFacingModes.length;
            startVideo(cameraFacingModes[cameraFacingIndex]);
        });
    }

    // Event listener for take photo button click
    if(takePhotoButton) {
        takePhotoButton.addEventListener('click', () => {
            // Check if video is active
            if(!videoActive) {
                document.querySelector('#manualCapture').click();
                return;
            }

            // Create a canvas element to draw the video frame
            const canvas = document.createElement('canvas');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            const context = canvas.getContext('2d');
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Check if the canvas is completely black
            if (isCanvasCompletelyBlack(canvas)) {
                return;
            }
            
            // Convert the canvas to a data URL (image/jpeg)
            const dataURL = canvas.toDataURL('image/jpeg', 0.5); // Adjust quality as needed
        

            // Create form data
            const formData = new FormData();
            formData.append('file', dataURL);
        
            // Show preview of last photo
            previewImage(dataURL);

            // Do zoomy effect
            galleryZoomyEffect();
        
            // Start asynchronous upload process
            uploadFormData(formData);
        });
    }

    // Listen for window focus/blur events to pause/resume video
    window.addEventListener('focus', () => {
        startVideo(cameraFacingModes[cameraFacingIndex]);
    });
    window.addEventListener('blur', () => {
        stopVideo();
    });

    // Listen turnstile popup
    window.addEventListener('turnstileOverlayHide', () => {
        startVideo(cameraFacingModes[cameraFacingIndex]);
    });
    window.addEventListener('turnstileOverlayShow', () => {
        stopVideo();
    });

    // Start the video stream with the first camera by default
    startVideo(cameraFacingModes[cameraFacingIndex]);



    // -------------------
    // Upload field
    // -------------------

    // Send pic
    function bindFileUploadField(selector) {
        const element = document.querySelector(selector);
        if(!element) {
            return;
        }
        element.addEventListener('change', function() {
            const file = element.files[0];
            const formData = new FormData();
                  formData.append('file', file);
    
            // Call uploadFormData function to handle upload
            uploadFormData(formData, true);
        }, false);
    }
    // Add listener
    bindFileUploadField('#manualFileUpload');
    bindFileUploadField('#manualCapture');



    // -------------------
    // Photo capture gallery zoomy effect
    // -------------------

    function galleryZoomyEffect() {
        const targetEl = document.querySelector('.camera-frame');
        const absoluteEl = document.querySelector('.gallery-preview__frame');
        const absoluteElParent = document.querySelector('.gallery-preview');

        // Get target element dimensions relative to the viewport
        const targetRect = targetEl.getBoundingClientRect();

        // Get relative parent offset relative to the viewport (if necessary)
        const absoluteElParentRect = absoluteElParent.getBoundingClientRect();

        // Set absolute element dimensions and offsets
        absoluteEl.classList.add('image-taken');
        absoluteEl.style.width = targetRect.width + 'px';
        absoluteEl.style.height = targetRect.height + 'px';
        absoluteEl.style.top = (targetRect.top - absoluteElParentRect.top) + 'px';
        absoluteEl.style.left = (targetRect.left - absoluteElParentRect.left) + 'px';
    
        // Remove class quickly to trigger animation
        setTimeout(function() {
            absoluteEl.classList.remove('image-taken');
            absoluteEl.style = '';
        }, 10);
    }
})();