<div class="camera-container">
    <div class="camera-frame" id="cameraView">
        <?php /* Video element to show camera feed */ ?>
        <video class="camera-video" id="video" autoplay disablePictureInPicture></video>
        <?php /* Hidden input that gets triggered by #takePhotoButton if we cant get camera feed directly */ ?>
        <input hidden id="manualCapture" type="file" capture="environment" accept="<?php echo implode(
            ', ', 
            array_map(
                function($i){ return '.' . $i; }, 
                Flight::get('app.allow.media')
            )
        ); ?>">
        <?php /* No camrea feed error */ ?>
        <span class="toast error center">
            <?php echo Flight::get('icon.error'); ?>
            Check camera permissions
        </span>
        <?php /* Other status messages */ ?>
        <div class="toast-container">
            <?php /* Upload progress */ ?>
            <span class="toast" id="uploadProgress"></span>
        </div>
    </div>
    <div class="camera-toolbar">
        <div class="toolbar toolbar-main">
            <div class="camera-btn__group gallery-preview">
                <div class="gallery-preview__frame" id="uploadPreview"></div>
                <a href="gallery" class="camera-btn camera-btn--small" id="viewGallery" aria-label="View Gallery">
                    <?php echo Flight::get('icon.image'); ?>
                </a>
            </div>
            <button class="camera-btn camera-take-photo" id="takePhotoButton" aria-label="Take Photo">
                <?php /* this will be styled with CSS */ ?>
            </button>
            <button class="camera-btn camera-btn--small" id="cameraSelect" aria-label="Swap Camera View">
                <?php echo Flight::get('icon.swap'); ?>
            </button>
        </div>
        <div class="toolbar toolbar-secondary">
            <?php echo renderMenuItemByKey('upload'); ?>
            <?php echo renderNavMenu(); ?>
        </div>
    </div>
</div>