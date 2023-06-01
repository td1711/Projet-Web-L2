<?php


require_once "../class" . DIRECTORY_SEPARATOR . "autoloader.php";

session_start();

use Marmi\Template;

ob_start();
?>

<form id="AdvancedSearchForm" method="POST" action="recherche.php">
    <h2>Recherche avancée</h2>
    <input class="searchbar" type="text" name="search" placeholder="Je cherche une recette">

    <?php $RecetteCreator = new \Marmi\RecetteCreator();
    $RecetteCreator->generateIngredientForm(array(),array());

    $RecetteCreator->generateTagsForm(array());
    ?>

    <button id ="BoutonAvancedSearch" type="submit" name="IsAdvancedSearch" value="true">
        Rechercher
    </button>


</form>
<?php

$content = ob_get_clean();

Template::render($content);