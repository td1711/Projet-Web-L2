<?php

use Marmi\Ingredient;
use Marmi\Tag;


require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";
error_reporting(0);
session_start();

$MarmiDB = new \Marmi\MarmiDB();

ob_start();

if(isset($_POST["IsAdvancedSearch"])){ // Si on fait une recherche avancée (uniquement pour les recettes)
    $search = $_POST["search"];
    $tabIngs = $_POST["ingredients"];
    $tabTags = $_POST["tags"];
    $key = array_search("", $tabIngs, true);
    if ($key !== false) {
        unset($tabIngs[$key]);
    }
    $key = array_search("", $tabTags, true);
    if ($key !== false) {
        unset($tabTags[$key]);
    }

    $tabRecettes = $MarmiDB->getRechercheAvance($search, $tabIngs, $tabTags);

    if (empty($tabRecettes)):
        echo "<span class='error404'>Pas de recettes trouvées...</span>";
    else :
        ?>
        <div id="Resultats">
            <div class="Resultats-Categorie">
                <?php if(!empty($tabTags) && count($tabTags) == 1):?>
                    <h2>Recherche par tag : <span style="color:#60ec60;"><?=" ".$tabTags[0]?></span></h2>
                <?php else:?>
                    <h2>Résultats de la recherche avancée</h2>
                <?php endif;
                foreach($tabRecettes as $recette){
                    $recette->render();
                }?>
            </div>
        </div>
    <?php
    endif;


}
else{ // Recherche normale (pour recettes et ingrédients)
    ?>
    <form id="SortSearchForm" method="POST" action="recherche.php">
        <h3>Afficher :</h3>
        <div id="BoutonsRecherche">
            <input id="rechercheTout" type="submit" name="sort" value="Tout">
            <input id="rechercheRecettes" type="submit" name="sort" value="Recettes">
            <input id="rechercheIngredients" type="submit" name="sort" value="Ingredients">
            <input id="rechercheTags" type="submit" name="sort" value="Tags">
            <input hidden="hidden" name="search" value="<?= $_POST["search"]?>">
        </div>
    </form>


    <?php

    if(isset($_POST["search"])){
        $search = trim(htmlspecialchars($_POST["search"]));
        $tri = $_POST["sort"];
    }
    else { // Si on arrive sur recherche.php sans passer par la barre de recherche
        $search = "";
        $tri = "Tout";
    }

    // On affiche en fonction du tri sélectionné ou par défaut Tout
        ?>
        <div id="Resultats">
                <?php
                if($tri == "Recettes" || $tri == "Tout") {
                    ?>
                    <div class="Resultats-Categorie">
                        <h2>Recettes</h2><?php
                        $tabRecettes = $MarmiDB->recherche($search);
                        foreach ($tabRecettes as $recette) {
                            $recette->render();
                        }
                        if(empty($tabRecettes))
                            echo "<span class='error404'>Pas de recettes trouvées...</span>";
                        ?>

                    </div>
                <?php
                }

                if($tri == "Ingredients" || $tri == "Tout") {
                    ?>
                    <div class="Resultats-Categorie">
                        <h2>Ingredients</h2><?php
                        $tabIngredients = $MarmiDB->getAllIngredient($search);
                        foreach ($tabIngredients as $ingr) {
                            $ingr->render();
                        }
                        if(empty($tabIngredients))
                            echo "<span class='error404'>Pas d'ingrédients trouvés...</span>";
                        ?>
                    </div>
                <?php
                }

                if($tri == "Tags" || $tri == "Tout") {
                    ?>
                    <div class="Resultats-Categorie">
                        <h2>Tags</h2>
                        <div id="DisabledTags"><?php
                            $tabTags = $MarmiDB->getAllTags($search);
                            foreach ($tabTags as $tag) {
                                $tag->render();
                            }
                            if(empty($tabTags))
                                echo "<span class='error404'>Pas de tags trouvés...</span>";
                        ?></div>
                    </div>
                <?php
                }
                ?>

        </div>
    <?php

}

$content = ob_get_clean();

Marmi\Template::render($content);