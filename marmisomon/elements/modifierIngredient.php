<?php

require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";

session_start();

ob_start();

$id = $_GET["ingredient"];

$MarmiDB = new \Marmi\MarmiDB();
$IngredientCreator = new \Marmi\IngredientCreator();
$result = $MarmiDB->returnIngredient($id)[0];
$ingredient = new \Marmi\Ingredient($result->nom,$result->image,$result->saison,$id);
$action = "modifierIngredient.php?ingredient=".$id;
if(!isset($_POST["name"])) {
    // Si l'utilisateur vient de cliquer sur modifier, génère le formulaire pré-remplie avec les informations actuelles
    $IngredientCreator->generateForm($ingredient->getNom(),$ingredient->getImage(),$ingredient->getSaison(), $action);
}
else{
    // Sinon, il a déjà validé le formulaire une fois
    $name = htmlspecialchars($_POST["name"]);
    $saison = htmlspecialchars($_POST["saison"]);
    $error = $IngredientCreator->verify($name,$_FILES["imageIng"], $id);
    if($error != ""){
        // Si le formulaire n'a pas été rempli correctement, on le regénère avec les informations entrées
        $action = "modifierIngredient.php?ingredient=".$id;
        $IngredientCreator->generateForm($name,$ingredient->getImage(),$saison, $action);
        echo "<span  style='color:red; text-align:center; font-size:1.5em;'>".$error."</span>";
    }
    else {
        // Sinon aucune erreur, on peut modifier dans la BDD
        $Upload = new \Marmi\Upload($_FILES["imageIng"]);
        $image = $Upload->move("Ingredients", $name);

        $MarmiDB->modifierIngredient($id, "nom", $name);
        $MarmiDB->modifierIngredient($id, "saison", $saison);
        $MarmiDB->modifierIngredient($id, "image", $image);

        echo "<span id='recetteCree' >Ingrédient modifié avec succès !</span>";
    }
}
$content = ob_get_clean();
\Marmi\Template::render($content);