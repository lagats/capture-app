<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Flight::get('app.sitename') . Flight::get('app.page.name'); ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'reset.css'; ?>"></style>
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'global.css' . '?ver=' . uniqid(); ?>"></style>
    <link rel="stylesheet" type="text/css" href="<?php echo Flight::get('public.css.url') . 'gallery.css' . '?ver=' . uniqid(); ?>"></style>
    
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'fslightbox.min.js' . '?ver=' . uniqid(); ?>"></script>
    <script acync defer src="<?php echo Flight::get('public.js.url') . 'gallery.js' . '?ver=' . uniqid(); ?>"></script>
</head>
<body class="<?php echo Flight::get('app.page.classnames'); ?>">
    <!-- Loader -->
    <div class="loader" id="loader">
      <img src="loader.svg" alt="Loading" />
    </div>
    <!-- Image Container -->
    <div class="image-container masonry" id="image-container"></div>
</body>
</html>