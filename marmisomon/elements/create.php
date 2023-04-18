<?php

use Marmi\Ingredient;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";

session_start();

$RecetteCreator = new \Marmi\RecetteCreator();
$MarmiDB = new \Marmi\MarmiDB();

ob_start();

if(isset($_POST["name"])){
    $tabIngs = $_POST["ingredients"];
    for($i=0; $i<count($tabIngs); $i++){
        $tabIngs[$i] = htmlspecialchars($tabIngs[$i]);
        /*echo $tabIngs[$i]." ";*/
    }

    //$tabTags = $_POST["tags"];
    $tabTags = array();
    $error = $RecetteCreator->verify($_POST["name"], $_FILES, $_POST["description"], $tabIngs, $_POST["instruction"]);
}

if (!isset($error)) :
    $RecetteCreator->generateForm("Nouvelle Recette", "create.php","","","",array(),array(),"");
elseif ($error != "") :
    echo "<div class='marmi-error' id='error'>" . $error . "</div>";
    $RecetteCreator->generateForm("Nouvelle Recette","create.php",$_POST["name"], $_POST["image"], $_POST["description"], $tabIngs, $tabTags,$_POST["instruction"]);
else :
    $title = htmlspecialchars($_POST["name"]);
    $description = htmlspecialchars($_POST["description"]);

    $Upload = new \Marmi\Upload($_FILES['image']);
    $image = $Upload->move("Recette", $title);

    $Recette = new \Marmi\Recette($title,$image,$description,"",1);

    $Ingredients = array();
    foreach($tabIngs as $nom){
        $Ingredients[] = new Ingredient($nom, "","",1);
    }
    $Recette->addIngredients($Ingredients);
    $Recette->addTags($tabTags);

    $MarmiDB->addRecette($Recette);


    echo "<span id='recetteCree' >Recette créé avec succès !</span>";

endif;


$content = ob_get_clean();

Marmi\Template::render($content);