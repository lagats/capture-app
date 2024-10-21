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
 * Enqueues the stylesheets defined in the application configuration.
 */
function stylesheets(array $stylesheets = array()) {
    $stylesheets = count($stylesheets) > 0 ? $stylesheets : (Flight::get('enqueue.stylesheets') ?? array());
    $urlPrefix = Flight::get('public.css.url');
    $pathPrefix = Flight::get('public.css.path');
    $cachebuster = Flight::get('app.devmode') ? ("?ver=" . Flight::get('app.timestamp')) : "";

    foreach ($stylesheets as $stylesheet) {
        if (isset($stylesheet['file'])) {
            $filePath = $pathPrefix . $stylesheet['file'];
            if (file_exists($filePath)) {
                $lastEditTime = filemtime($filePath);
                $cachebuster = "?ver=" . $lastEditTime;
            }
        }

        $id = isset($stylesheet['id']) ? " id='{$stylesheet['id']}'" : "";
        $url = isset($stylesheet['file']) ? " href='{$urlPrefix}{$stylesheet['file']}{$cachebuster}'" : "";
        echo "<link rel='stylesheet'$id$url>";
    }
}

/**
 * Enqueues the scripts defined in the application configuration.
 */
function scripts(array $scripts = array()) {
    $scripts = count($scripts) > 0 ? $scripts : (Flight::get('enqueue.scripts') ?? array());
    $urlPrefix = Flight::get('public.js.url');
    $pathPrefix = Flight::get('public.js.path');
    $cachebuster = Flight::get('app.devmode') ? ("?ver=" . Flight::get('app.timestamp')) : "";
    
    foreach ($scripts as $script) {
        if (isset($script['file'])) {
            $filePath = $pathPrefix . $script['file'];
            if (file_exists($filePath)) {
                $lastEditTime = filemtime($filePath);
                $cachebuster = "?ver=" . $lastEditTime;
            }
        }
        
        $id    = isset($script['id']) ? " id='{$script['id']}'" : "";
        $url   = isset($script['file']) ? " src='{$urlPrefix}{$script['file']}{$cachebuster}'" : "";
        $async = isset($script['async']) ? " async" : "";
        $defer = isset($script['defer']) ? " defer" : "";
        echo "<script type='text/javascript'$id$url$async$defer></script>";
    }
}

/**
 * Generates a thumbnail for the specified image file.
 **/
function generateThumbnail($imageFile) {
    // Check if the original image file exists
    $defaultData = [
        'name' => '',
        'path' => '',
        'url'  => '',
    ];
    if (!file_exists($imageFile)) {
        return $defaultData;
    }
    try {
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

        // Return the thumbnail data
        return [
            'name' => $thumbnailFilename,
            'path' => $thumbnailFilepath,
            'url'  => $thumbnailUrl . $thumbnailFilename,
        ];
    } catch (\Exception $e) {
        return $defaultData;
    }
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