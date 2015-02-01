<!doctype html>
<html>
    <head>
        <title><?php echo $data['album'].' - '.$data['artist'] ?></title>
        <meta name="description" content="<?php echo $data['cat'].' - '.$data['info_en'] ?>" />
        <meta property="og:title" content="<?php echo $data['album'].' - '.$data['artist'] ?>" />
        <meta property="og:description" content="<?php echo $data['cat'].' - '.$data['info_en'] ?>" />
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo $data['url'] ?>" />
        <meta property="og:image" content="<?php echo $data['cover'] ?>" />
        <link rel="image_src" href="<?php echo $data['cover'] ?>">
    </head>
    <body>
       <?php

        echo $data['info_en']; 
        ?>
    </body>
</html>
