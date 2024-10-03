<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Flight::get('app.sitename') . Flight::get('app.page.name'); ?></title>

    <!-- shared -->
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'reset.css'; ?>"></style>
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'global.css' . '?ver=' . uniqid(); ?>"></style>
    
    <!-- gallery css/js -->
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'gallery.css' . '?ver=' . uniqid(); ?>"></style>
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'fslightbox.min.js' . '?ver=' . uniqid(); ?>"></script>
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'gallery.js' . '?ver=' . uniqid(); ?>"></script>

    <!-- capture css/js -->
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'capture.css' . '?ver=' . uniqid(); ?>"></style>
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'capture.js' . '?ver=' . uniqid(); ?>"></script>
</head>
<body class="<?php echo Flight::get('app.page.classnames'); ?>">
    <div class="camera-container">
        <div class="camera-frame" id="cameraView">
          <!-- Loader -->
          <div class="loader" id="loader">
            <?php echo Flight::get('icon.error'); ?>
          </div>
          <!-- Image Container -->
          <div class="image-container masonry" id="image-container"></div>
        </div>
        <div class="camera-toolbar">
            <div class="toolbar toolbar-main">
                <div class="camera-btn__group gallery-preview">
                    <div class="gallery-preview__frame" id="uploadPreview"></div>
                    <a href="/" class="camera-btn camera-btn--small" id="viewGallery" aria-label="View Gallery">
                        <?php echo Flight::get('icon.camera'); ?>
                    </a>
                </div>
                <button disabled class="camera-btn camera-take-photo" aria-label="Take Photo">
                    <!-- this will be styled with CSS -->
                </button>
                <button disabled class="camera-btn camera-btn--small" aria-label="Swap Camera View">
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
    <?php googleAnalytics();  ?>
</body>
</html>