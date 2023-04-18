<?php

namespace Marmi;

class Template{
    public static function render($content): void{
        ?>
        <!doctype html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Marmisomon</title>
            <link rel="icon" href="../IMG/spatule.png">
            <link rel="stylesheet" href="../CSS/styles.css">
            <script src="../js/script.js"></script>
        </head>
        <body>
            <?php include "header.php" ?>

                <div id="main">

                <?= $content ?>

                </div>


            <?php include "footer.php" ?>
        </body>
        </html>
        <?php
    }
}