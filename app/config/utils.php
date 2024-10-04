<?php

/**
 * Add CSRF Token to the page
 **/
function csrfTokenElement() {
    $token = Flight::session()->getOrDefault('csrf_token', null);
    if($token === null) {
        return;
    }
    echo '<div class="csrf-token" data-sitekey="' . $token . '"></div>';
}

/**
 * Generates a thumbnail for the specified image file.
 **/
function generateThumbnail($imageFile) {
    // Check if the original image file exists
    if (!file_exists($imageFile)) {
        return [
            'name' => '',
            'path' => '',
            'url'  => '',
        ];
    }

    // Paths
    $thumbnailDir = Flight::get('public.thumbnail.path');
    $thumbnailUrl = Flight::get('public.thumbnail.url');

    // Get the original image filename and extension
    $originalFilename = basename($imageFile);
    $imageExtension = pathinfo($imageFile, PATHINFO_EXTENSION);

    // Create the thumbnail filename and path
    $thumbnailFilename = 'thumb__' . $originalFilename;
    $thumbnailFilepath = $thumbnailDir . '/' . $thumbnailFilename;

    // Make thumbnail folder if it doesn't exist
    if (!file_exists($thumbnailDir)) {
        mkdir($thumbnailDir, 0755, true);
    }

    // Check if the thumbnail already exists
    if (!file_exists($thumbnailFilepath)) {
        $image = Flight::imageResize($imageFile);
        $image->resizeToWidth(300);
        $image->save($thumbnailFilepath);
    }

    return [
        'name' => $thumbnailFilename,
        'path' => $thumbnailFilepath,
        'url'  => $thumbnailUrl . $thumbnailFilename,
    ];
}

/**
 * Add console log to inline
 **/
function debugInline() {
    ?>
        <div id="debugDiv" style="background: #ddd; padding: 0.75rem 1rem;"></div>
        <script>
        if (typeof console  != "undefined") 
            if (typeof console.log != 'undefined')
                console.olog = console.log;
            else
                console.olog = function() {};

            console.log = function(message) {
                console.olog(message);
                document.querySelector('#debugDiv').innerHTML += '<div>' + message + '</div>';
            };
            console.error = console.debug = console.info =  console.log;
        </script>
    <?php
}