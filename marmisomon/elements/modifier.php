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
$id = htmlspecialchars($_GET["recette"]);
if(!isset($_POST["name"])) {
    $MarmiDB = new \Marmi\MarmiDB();

    $recette = $MarmiDB->returnRecette($id);
    /*
    $ArrayRecettes = $MarmiDB->getAllRecettes($id);
    foreach($ArrayRecettes as $rec){
        if($rec->getTitre() == $recette)
            $recette = $rec;
    }*/


    $RecetteCreator->generateForm("Modifier la Recette", "modifier.php?recette=".$id, $recette->getTitre(), $recette->getPhoto(),
        $recette->getDescription(), $recette->getIngredients(),$recette->getTags(), $recette->getInstruction());
}
else {
    echo "ici";
    $tabIngs = $_POST["ingredients"];
    for ($i = 0; $i < count($tabIngs); $i++) {
        $tabIngs[$i] = htmlspecialchars($tabIngs[$i]);
    }
    //$tabTags = $_POST["tags"];
    $tabTags = array();

    $error = $RecetteCreator->verify($_POST["name"], $_FILES, $_POST["description"], $tabIngs, $_POST["instruction"]);
    if ($error != "") {
        echo "<div class='marmi-error' id='error'>" . $error . "</div>";
        $RecetteCreator->generateForm("Modifier la Recette", "modifier.php?recette=".$id, $_POST["name"], $_FILES["image"], $_POST["description"], $tabIngs, $tabTags, $_POST["instruction"]);
    }
    else{
        echo "Tout correct !";
        $title = htmlspecialchars($_POST["name"]);
        $description = htmlspecialchars($_POST["description"]);
        $instruction = htmlspecialchars($_POST["instruction"]);
        $Upload = new \Marmi\Upload($_FILES['image']);
        $image = $Upload->move("Recette", $title);
        echo $title."<br>".$description."<br>".$image;

        $Recette = new \Marmi\Recette($title,$image,$description,$instruction,1);

        $Ingredients = array();
        foreach($tabIngs as $nom){
            $Ingredients[] = new Ingredient($nom, "","",1);
        }
        $Recette->addIngredients($Ingredients);
        $Recette->addTags($tabTags);

        $MarmiDB->modifierRecette($Recette->getId(), "titre", $title);
        $MarmiDB->modifierRecette($Recette->getId(), "description", $description);
        $MarmiDB->modifierRecette($Recette->getId(), "instruction", $instruction);
        $MarmiDB->modifierRecette($Recette->getId(), "image", $image);
        $MarmiDB->modifierRecette($Recette->getId(), "titre", $title);
    }
}


$content = ob_get_clean();

Marmi\Template::render($content);