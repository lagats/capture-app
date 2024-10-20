<div class="camera-container">
    <div class="camera-frame" id="cameraView">
        <?php /* Image Container */ ?>
        <div class="image-container masonry" id="image-container"></div>
        <?php /* Loader */ ?>
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
        <?php /* No gallery items error */ ?>
        <span class="toast" id="galleryStatus"></span>
        <?php /* Other status messages */ ?>
        <div class="toast-container">
            <?php /* Upload progress */ ?>
            <span class="toast" id="uploadProgress"></span>
            <?php /* Delete button */ ?>
            <button disabled class="nav-btn delete-btn" id="deleteButton">
                <?php echo Flight::get('icon.delete'); ?>
                <span class="label">Delete</span>
            </button>
        </div>
    </div>
    <div class="camera-toolbar">
        <div class="toolbar toolbar-main">
            <a href="/" class="camera-btn camera-btn--small" aria-label="Back to Camera">
                <?php echo Flight::get('icon.camera'); ?>
            </a>
            <button disabled class="camera-btn camera-take-photo" aria-label="Take Photo">
                <?php /* this will be styled with CSS */ ?>
            </button>
            <button disabled class="camera-btn camera-btn--small" aria-label="Swap Camera View">
                <?php echo Flight::get('icon.swap'); ?>
            </button>
        </div>
        <div class="toolbar toolbar-secondary">
            <?php if(str_contains(Flight::get('app.page.classnames'),'my_captures')) { ?>
                <?php echo renderMenuItemByKey('gallery'); ?>
            <?php } else { ?>
                <?php echo renderMenuItemByKey('mypics'); ?>
            <?php } ?>
            <?php echo renderNavMenu(); ?>
        </div>
    </div>
</div>