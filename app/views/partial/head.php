<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Flight::get('app.sitename') . Flight::get('app.page.name'); ?></title>

    <?php /* stylesheets */ ?>
    <?php stylesheets(); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" />

    <?php /* CloudFlare Turnstile */ ?>
    <?php turnstileScript(); ?>

    <?php /* scripts */ ?>
    <?php scripts(); ?>
    
    <?php /* Google Analytics */ ?>
    <?php googleAnalytics(); ?>
    
    <?php /* Favicon */ ?>
    <link rel="icon" href="https://wedding.lagats.com/wp-content/uploads/2023/10/fav-150x150.png" sizes="32x32">
    <link rel="icon" href="https://wedding.lagats.com/wp-content/uploads/2023/10/fav.png" sizes="192x192">
    <link rel="apple-touch-icon" href="https://wedding.lagats.com/wp-content/uploads/2023/10/fav.png">
    <meta name="msapplication-TileImage" content="https://wedding.lagats.com/wp-content/uploads/2023/10/fav.png">
</head>
<body class="<?php echo Flight::get('app.page.classnames'); ?>">

    <?php /* START CONTENT */ ?>
    <div class="content">