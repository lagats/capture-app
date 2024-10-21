<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?php echo Flight::get('app.sitename') . Flight::get('app.page.name'); ?></title>
    
    <?php /* try to disable zoom actions on mobile devices (src -> https://gist.github.com/andrewvaughan/8f6f892bdbc7d5d973879ed909a4aa1e) */ ?>
    <style>
        * {
            touch-action: manipulation !important;
        }
    </style>
    <script>
        // document.addEventListener('DOMContentLoaded', function(e) {
        //     document.querySelectorAll('*').forEach(el => {
        //         el.style["touch-action"] = "manipulation";
        //     });

        //     // Add the style to any DOM manipulations
        //     (new MutationObserver(elms => {
        //         elms.forEach(
        //             function(elm) {
        //                 for (var i = 0; i < elm.addedNodes.length; i++) {
        //                 elm.addedNodes[i].style["touch-action"] = "manipulation";
        //                 }
        //             }
        //         );
        //     }
        //     )).observe(document.body, { childList: true, subtree: true });
        // });
    </script>
    
    <?php /* global stylesheets */ ?>
    <?php stylesheets([
        [ 'file' => 'reset.css' ],
        [ 'file' => 'global.css' ],
        [ 'file' => 'menu.css' ],
    ]); ?>

    <?php /* all other stylesheets */ ?>
    <?php stylesheets(); ?>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:ital,wght@0,100..900;1,100..900&display=swap" />

    <?php /* global scripts */ ?>
    <?php scripts([
        [ 'file' => 'global.js', 'defer' => true ],
        [ 'file' => 'menu.js', 'defer' => true ],
    ]); ?>
    
    <?php /* all other scripts scripts */ ?>
    <?php scripts(); ?>
    
    <?php /* CloudFlare Turnstile */ ?>
    <?php turnstileScript(); ?>
    
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