<?php

function googleAnalytics() {
?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-K1Z7R8TKRL"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-K1Z7R8TKRL');
    </script>
<?php
}