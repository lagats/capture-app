<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Flight::get('app.sitename') . Flight::get('app.page.name'); ?></title>

    <!-- CloudFlare Turnstile -->
    <?php turnstileScript(); ?>

    <!-- shared -->
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'reset.css'; ?>"></style>
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'global.css' . '?ver=' . uniqid(); ?>"></style>
    
    <!-- capture css/js -->
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'capture.css' . '?ver=' . uniqid(); ?>"></style>
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'capture.js' . '?ver=' . uniqid(); ?>"></script>
        
    <!-- Google Analytics -->
    <?php googleAnalytics();  ?>
</head>
<body class="<?php echo Flight::get('app.page.classnames'); ?>">
    <div class="camera-container">
        <div class="camera-frame" id="cameraView">
            <!-- use video element to show camera feed -->
            <video class="camera-video"  id="video" autoplay></video>
            <!-- hidden input that gest griggered by #takePhotoButton if we cant get camera feed directly -->
            <span class="warning">
                <?php echo Flight::get('icon.error'); ?>
                Check camera permissions
            </span>
            <input hidden id="manualCapture" type="file" capture="environment" accept="image/png, image/gif, image/jpeg">
        </div>
        <div class="camera-toolbar">
            <div class="toolbar toolbar-main">
                <div class="camera-btn__group gallery-preview">
                    <div class="gallery-preview__frame" id="uploadPreview"></div>
                    <a href="gallery" class="camera-btn camera-btn--small" id="viewGallery" aria-label="View Gallery">
                        <?php echo Flight::get('icon.gallery'); ?>
                    </a>
                </div>
                <button class="camera-btn camera-take-photo" id="takePhotoButton" aria-label="Take Photo">
                    <!-- this will be styled with CSS -->
                </button>
                <button class="camera-btn camera-btn--small" id="cameraSelect" aria-label="Swap Camera View">
                    <?php echo Flight::get('icon.swap'); ?>
                </button>
            </div>
            <div class="toolbar toolbar-secondary">
                <!-- manual upload -->
                <input hidden id="manualFileUpload" type="file" accept="image/png, image/gif, image/jpeg">
                <button class="nav-btn" onClick="manualFileUpload.click()">
                    <?php echo Flight::get('icon.upload'); ?>
                    Upload
                </button>
                <!-- link to my uploaded images -->
                <a href="mypics" class="nav-btn">
                    <?php echo Flight::get('icon.my-gallery'); ?>
                    My Pics
                </a>
            </div>
            <div id="uploadProgress"></div>
        </div>
    </div>

    <?php turnstileElement(); ?>
    <?php csrfTokenElement(); ?>
</body>
</html>