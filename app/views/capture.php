<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Camera</title>

    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'capture.css' . '?ver=' . uniqid(); ?>"></style>
   
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'capture.js' . '?ver=' . uniqid(); ?>"></script>
</head>
<body>
    <div id="cameraView">
        <video id="video" autoplay></video>
    </div>
    <select id="cameraSelect"></select>
    <button id="takePhotoButton">Take Photo</button>
    <input id="manualFileUpload" type="file" accept="image/*;capture=camera">
    <button id="cancelUploadButton" disabled>Cancel Upload</button>
    <div id="uploadProgress"></div>
    <div id="uploadPreview"></div>


    <div id="debugDiv"></div>
    <script>
    if (typeof console  != "undefined") 
        if (typeof console.log != 'undefined')
            console.olog = console.log;
        else
            console.olog = function() {};

    console.log = function(message) {
        console.olog(message);
        document.querySelector('#debugDiv').append('<p>' + message + '</p>');
    };
    console.error = console.debug = console.info =  console.log
    </script>


</body>
</html>