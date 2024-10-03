<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Flight::get('app.sitename') . Flight::get('app.page.name'); ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'reset.css'; ?>"></style>
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'global.css' . '?ver=' . uniqid(); ?>"></style>
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'capture.css' . '?ver=' . uniqid(); ?>"></style>
   
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'capture.js' . '?ver=' . uniqid(); ?>"></script>
</head>
<body class="<?php echo Flight::get('app.page.classnames'); ?>">
    <div class="camera-container">
        <div class="camera-frame" id="cameraView">
            <!-- use video element to show camera feed -->
            <video class="camera-video"  id="video" autoplay></video>
            <!-- hidden input that gest griggered by #takePhotoButton if we cant get camera feed directly -->
            <input id="manualCapture" type="file" capture="environment" accept="image/png, image/gif, image/jpeg">
        </div>
        <div class="camera-toolbar">
            <div class="toolbar toolbar-main">
                <div class="camera-btn__group gallery-preview">
                    <div class="gallery-preview__frame" id="uploadPreview"></div>
                    <button class="camera-btn camera-btn--small" id="viewGallery" aria-label="View Gallery">
                        <?php echo Flight::get('icon.gallery'); ?>
                    </button>
                </div>
                <button class="camera-btn camera-take-photo" id="takePhotoButton" aria-label="Take Photo">
                    <!-- this will be styled with CSS -->
                </button>
                <button class="camera-btn camera-btn--small" id="cameraSelect" aria-label="Swap Camera View">
                    <?php echo Flight::get('icon.swap'); ?>
                </button>
            </div>
            <div class="toolbar toolbar-secondary">
                <input id="manualFileUpload" type="file" accept="image/png, image/gif, image/jpeg">
                <div id="uploadProgress"></div>
            </div>
        </div>
    </div>

</body>
</html>