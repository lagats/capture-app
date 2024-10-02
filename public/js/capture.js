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
    const cancelUploadButton = document.getElementById('cancelUploadButton');

    // Function to enumerate available cameras and populate the dropdown
    function enumerateCameras() {
        navigator.mediaDevices.enumerateDevices()
            .then(devices => {
                devices.forEach(device => {
                    console.log(JSON.stringify(device));
                    if (device.kind === 'videoinput') {
                        const option = document.createElement('option');
                        option.value = device.deviceId;
                        option.text = device.label || `Camera ${cameraSelect.length + 1}`;
                        cameraSelect.appendChild(option);
                    }
                });
            })
            .catch(error => {
                console.error('Error enumerating cameras:', error);
            });
    }

    // Function to start the video stream using the selected camera
    function startVideo(deviceId) {
        navigator.mediaDevices.getUserMedia({ video: {
            deviceId,
            // facingMode:'user',
            // facingMode:'environment',
            width: { ideal: 4096 },
            height: { ideal: 2160 }
        } })
        .then(stream => {
            console.log("Video stream started successfully");
            video.srcObject = stream;
            video.setAttribute('autoplay', '');
            video.setAttribute('muted', '');
            video.setAttribute('playsinline', '');
        })
        .catch(error => {
            console.error('Error starting video:', error);
        });
    }

    // Update image preview
    function previewImage(dataURL) {
        uploadPreview.innerHTML = `<img src="${dataURL}">`;
    }

    // Function to upload the image to the server
    function uploadFormData(formData) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'upload', true); // Replace with your PHP upload script URL
        xhr.upload.onprogress = function(event) {
            if (event.lengthComputable) {
                const progress = (event.loaded / event.total) * 100;
                uploadProgress.textContent = `Uploading: ${progress.toFixed(2)}%`;
            }
        };
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                uploadProgress.textContent = 'Upload complete!';
                cancelUploadButton.disabled = true;
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
        xhr.send(formData);

        // Enable the cancel button while upload is in progress
        cancelUploadButton.disabled = false;
        cancelUploadButton.addEventListener('click', () => {
            xhr.abort();
        });
    }

    // Event listener for camera selection change
    cameraSelect.addEventListener('change', () => {
        startVideo(cameraSelect.value);
    });

    // Event listener for take photo button click
    takePhotoButton.addEventListener('click', () => {
        // Create a canvas element to draw the video frame
        const canvas = document.createElement('canvas');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
    
        // Convert the canvas to a data URL (image/jpeg)
        const dataURL = canvas.toDataURL('image/jpeg', 0.5); // Adjust quality as needed
    
        // Create form data
        const formData = new FormData();
        formData.append('file', dataURL);
    
        // Show preview of last photo
        previewImage(dataURL);
    
        // Start asynchronous upload process
        uploadFormData(formData);
    });

    // Call enumerateCameras to populate the dropdown on page load
    enumerateCameras();

    // Start the video stream with the first camera by default
    startVideo(cameraSelect.value);

    // Make functions avaliable in global scope
    window.captureApp = {
        uploadFormData
    };

})();



// -------------------
// Upload field
// -------------------

(function(){
    // Get element
    var manualFileUpload = document.getElementById('manualFileUpload');

    // Send pic
    function sendPic() {
        const file = manualFileUpload.files[0];
        const formData = new FormData();
            formData.append('file', file);

        // Call uploadFormData function to handle upload
        window.captureApp.uploadFormData(formData);
    }

    // Add listener
    manualFileUpload.addEventListener('change', sendPic, false);
})();