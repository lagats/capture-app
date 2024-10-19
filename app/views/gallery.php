<div class="camera-container">
    <div class="camera-frame" id="cameraView">
        <!-- Image Container -->
        <div class="image-container masonry" id="image-container"></div>
        <!-- Loader -->
        <div class="loader" id="loader">
            <div class="masonry">
                <?php
                    for($i=0; $i<9; $i++) {
                        ?>
                            <div class="masonry-item masonry-placeholder">
                                <div class="masonry-content shimmer"></div>
                            </div>
                        <?php
                    }
                ?>
            </div>
        </div>
        <!-- Image Upload Status -->
        <div class="toast" id="uploadProgress"></div>
        <div class="toast" id="galleryStatus"></div>
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
            <!-- link to my uploaded images -->
            <?php if(str_contains(Flight::get('app.page.classnames'),'my_captures')) { ?>
                <!-- Delete button -->
                <button disabled class="nav-btn delete-btn" id="deleteButton">
                    <?php echo Flight::get('icon.delete'); ?>
                    Delete
                </button>
                <!-- Gallery button -->
                <a href="gallery" class="nav-btn">
                    <?php echo Flight::get('icon.gallery'); ?>
                    Gallery
                </a>
            <?php } else { ?>
                <!-- manual upload -->
                <input hidden id="manualFileUpload" type="file" accept="image/png, image/gif, image/jpeg">
                <button class="nav-btn" onClick="manualFileUpload.click()">
                    <?php echo Flight::get('icon.upload'); ?>
                    Upload
                </button>
                <!-- Gallery button -->
                <a href="mypics" class="nav-btn">
                    <?php echo Flight::get('icon.my-gallery'); ?>
                    My Pics
                </a>
            <?php } ?>
        </div>
    </div>
</div>