<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script></script>
    </head>
    <body>
        <div id="slider-<?= $hash ?>"></div>
        <script type="text/javascript" charset="utf-8">
            (function() {
                var sc = document.createElement('script'); sc.type = 'text/javascript'; sc.async = true;
                sc.src = "<?= $url ?>"; sc["charset"] = 'utf-8';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(sc, s);
            }());
        </script>
    </body>
</html>