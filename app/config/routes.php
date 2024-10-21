<?php


/* ---------------------------- *
 * Menu Items & Actions
 * ---------------------------- */

// Menu Items
Flight::set('app.menuItems', [
    'camera'  => [
        'label'    => 'Camera',
        'menuLabel'=> 'Capture a photo',
        'icon'     => 'camera',
        'attrs'    => [
            'href'    => '/',
            'class'   => 'nav-btn',
        ]
    ],
    'gallery' => [
        'label'    => 'Gallery',
        'menuLabel'=> 'View the gallery',
        'icon'     => 'image',
        'attrs'    => [
            'href'    => '/gallery',
            'class'   => 'nav-btn',
        ]
    ],
    'mypics'  => [
        'label'    => 'My Pics',
        'menuLabel'=> 'View my photos',
        'icon'     => 'mypics',
        'attrs'    => [
            'href'    => '/mypics',
            'class'   => 'nav-btn',
        ]
    ],
]);

// Menu Actions
Flight::set('app.menuActions', [
    'upload'  => [
        'label'    => 'Upload',
        'menuLabel'=> 'Upload an image',
        'icon'    => 'upload',
        'attrs'   => [
            'onClick' => 'manualFileUpload.click()',
            'class'   => 'nav-btn',
        ],
        'htmlBefore' => function() {
            // only add the hidden input if we haven't already added it
            if(Flight::get('app.has.manualFileUpload')) {
                return '';
            }
            // add a hidden input to the page to trigger the file upload
            Flight::set('app.has.manualFileUpload', true);
            return '<input hidden id="manualFileUpload" type="file" accept="' . implode(
                ', ', 
                array_map(
                    function($i){ return '.' . $i; }, 
                    Flight::get('app.allow.media')
                )
            ) . '">';
        },
    ],
]);



/* ---------------------------- *
 * Page Routes
 * ---------------------------- */

Flight::group('', function() {
    
    /* ---------------------------- *
     * Camera Page
     * ---------------------------- */
    $cameraPage = Flight::get('app.menuItems')['camera'];
    Flight::route($cameraPage['attrs']['href'], function () use ($cameraPage) {
        // enqueue styles
        Flight::set('enqueue.stylesheets', [
            [ 'file' => 'capture.css' ],
        ]);
        // enqueue scripts
        Flight::set('enqueue.scripts', [
            [ 'file' => 'image-resize.js', 'defer' => true ],
            [ 'file' => 'capture.js', 'defer' => true ],
        ]);
        // get template
        Flight::set('app.page.template', Flight::get('app.views.path') . 'capture.php');
        // vars
        Flight::set('app.page.name', ' | ' . $cameraPage['label']);
        Flight::set('app.page.classnames', 'camera-page');
    });
    
    /* ---------------------------- *
     * Gallery Pages
     * ---------------------------- */
    Flight::group('', function() {
        // enqueue styles
        Flight::set('enqueue.stylesheets', [
            [ 'file' => 'capture.css' ],
            [ 'file' => 'gallery.css' ],
        ]);
        // enqueue scripts
        Flight::set('enqueue.scripts', [
            [ 'file' => 'image-resize.js', 'defer' => true ],
            [ 'file' => 'capture.js', 'defer' => true ],
            [ 'file' => 'vendor/fslightbox.min.js', 'defer' => true ],
            [ 'file' => 'gallery.js', 'defer' => true ],
            [ 'file' => 'delete.js', 'defer' => true ],
        ]);
        // get template
        Flight::set('app.page.template', Flight::get('app.views.path') . 'gallery.php');
        // gallery route
        $galleryPage = Flight::get('app.menuItems')['gallery'];
        Flight::route($galleryPage['attrs']['href'], function () use ($galleryPage) {
            // vars
            Flight::set('app.page.name', ' | ' . $galleryPage['label']);
            Flight::set('app.page.classnames', 'gallery-page gallery--all');
        });
        // my photos route
        $mypicsPage = Flight::get('app.menuItems')['mypics'];
        Flight::route($mypicsPage['attrs']['href'], function () use ($mypicsPage) {
            // vars
            Flight::set('app.page.name', ' | ' . $mypicsPage['label']);
            Flight::set('app.page.classnames', 'gallery-page gallery--my_captures');
        }); 
    });
    
}, [ new AddCsrfMiddleware(), new HeaderFooterMiddleware() ]);



/* ---------------------------- *
 * API Routes
 * ---------------------------- */

Flight::group('', function() {
    Flight::route('POST /upload', function () {
        require(Flight::get('app.views.path') . 'api/upload.php');
    });
    Flight::route('GET /media', function () {
        require(Flight::get('app.views.path') . 'api/media.php');
    });
    Flight::route('POST /media-delete', function () {
        require(Flight::get('app.views.path') . 'api/media-delete.php');
    });
}, [ new CheckCsrfMiddleware(), new DebounceMiddleware(), new CheckTurnstileMiddleware() ]);



/* ---------------------------- *
 * Turnstile Routes
 * ---------------------------- */

Flight::group('', function() {
    Flight::route('POST /turnstile-validate', function () {
        require(Flight::get('app.views.path') . 'api/turnstile-validate.php');
    });
}, [ new CheckCsrfMiddleware(), new DebounceMiddleware() ]); /* ENSURE WE DONT USE 'CheckTurnstileMiddleware()' HERE */
