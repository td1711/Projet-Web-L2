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
            <?php $MarmiDB = new \Marmi\MarmiDB();
            // Génère la liste des ingrédients présents dans la BDD pour le javascript
            $listeIngredients = $MarmiDB->returnArrayIngredients();?>
                <script>let ingredients = [<?php
                        for($i=0; $i<count($listeIngredients); $i++):
                            echo '"'.$listeIngredients[$i]->getNom().'"';
                            if($i < count($listeIngredients)-1)
                                echo ",";
                        endfor ?>]
                </script>
                <div id="main">
                    <div id="content">
                        <?= $content ?>
                    </div>

                </div>


            <?php include "footer.php" ?>
        </body>
        </html>
        <?php
    }
}