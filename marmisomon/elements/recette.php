<?php

require_once "../class" . DIRECTORY_SEPARATOR . "autoloader.php";

session_start();

use Marmi\Template;

ob_start();

if(isset($_GET["recette"])) { //
    $IdRecette = htmlspecialchars($_GET["recette"]);
    $MarmiDB = new \Marmi\MarmiDB();
    $recette = $MarmiDB->returnRecette($IdRecette);
}
if(isset($_GET["recette"]) && $recette != null){
    ?>
    <form id="RecetteComplete" action="recherche.php" method="POST">
        <div id="titre">
            <?php echo $recette->getTitre();?>
        </div>

        <div id="IngEtImage">
            Ingredients
            <div id="Ingredients">
                <?php
                foreach ($recette->getIngredients() as $ing) {
                    ?><div class='Ing'>
                        <?=$ing->getNom()." (".$ing->getSaison().")"?>
                        <img src="..<?= DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR."Ingredients".DIRECTORY_SEPARATOR.$ing->getImage()?>">
                    </div><?php
                }
                ?>
            </div>
            <div id="imageRecette">
                <img src="..<?= DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR."Recette".DIRECTORY_SEPARATOR.$recette->getPhoto()?>">
            </div>
        </div>
        Tags :
        <div id="Tags">
            <?php
            foreach ($recette->getTags() as $tag):?>
                <input class='Tag' value="<?=$tag->getNom()?>" name="tags[]" type="submit">
                <input name="ingredients[]" hidden="hidden">
                <input name="search" hidden="hidden">
                <input name="IsAdvancedSearch" hidden="hidden">
            <?php endforeach;

            ?>
        </div>
        Description :
        <div class="BlocRecette">

            <?= $recette->getDescription()?>
        </div>

        Instruction :

        <div class="BlocRecette">

            <?= $recette->getInstruction()?>
        </div>

    </form>

    <?php
}
// Si l'utilisateur tente d'accéder à une recette qui n'existe pas
else{?>
    <div class='marmi-error' id='error'>Pas de recette trouvée...</div>
<?php }
$content = ob_get_clean();

Template::render($content);