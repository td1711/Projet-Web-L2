<?php

use Marmi\Ingredient;
use Marmi\Tag;

require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";
error_reporting(0);
session_start();

$RecetteCreator = new \Marmi\RecetteCreator();
$MarmiDB = new \Marmi\MarmiDB();

ob_start();
$id = htmlspecialchars($_GET["recette"]);
$recette = $MarmiDB->returnRecette($id);
$image = $recette->getPhoto();
if(!isset($_POST["name"])) { // Si l'utilisateur vient de cliquer sur modifier

    $saisons = array();
    foreach($recette->getIngredients() as $ing){
        $saisons[] = $ing->getSaison();
    }

    // génère le formulaire pré-remplie avec les informations initiales
    $RecetteCreator->generateForm("Modifier la Recette", "modifier.php?recette=".$id, $recette->getTitre(), $recette->getPhoto(),
        $recette->getDescription(), $recette->getIngredients(),$recette->getTags(), $recette->getInstruction(), $saisons);
}
else { // Sinon, l'utilisateur a déjà validé le formulaire une fois

    $tabIngs = $_POST["ingredients"];

    $key = array_search("", $tabIngs, true);
    if ($key !== false) {
        unset($tabIngs[$key]);
    }

    $saisons = $_POST["saison"];
    for ($i = 0; $i < count($tabIngs); $i++) {
        if($saisons[$i] == null)
            $saisons[$i] = "";
        $tabIngs[$i] = new Ingredient(htmlspecialchars($tabIngs[$i]),"",$saisons[$i],1);
    }
    $tabTags = $_POST["tags"];
    for ($i = 0; $i < count($tabTags); $i++) {
        $tabTags[$i] = new Tag(htmlspecialchars($tabTags[$i]),1);
    }
    $key = array_search("", $tabTags, true);
    if ($key !== false) {
        unset($tabTags[$key]);
    }

    // On vérifie les informations fournies par l'utilisateur et on récupère l'erreur à afficher ou pas
    $error = $RecetteCreator->verify($_POST["name"], $_FILES['image'], $_POST["description"], $tabIngs, $_POST["instruction"]);


    if ($error != "" && $error != "L'image est vide") { // Si il y a une erreur, alors on l'affiche et on recrée le formulaire avec les anciennes informations remplies
        echo "<div class='marmi-error' id='error'>" . $error . "</div>";
        $RecetteCreator->generateForm("Modifier la Recette", "modifier.php?recette=".$id, $_POST["name"], $_FILES["image"], $_POST["description"], $tabIngs, $tabTags, $_POST["instruction"], $_POST["saisons"]);
    }
    else{ // Si il n'y a pas d'erreur

        $title = htmlspecialchars($_POST["name"]);
        $description = htmlspecialchars($_POST["description"]);
        $instruction = htmlspecialchars($_POST["instruction"]);

        // Si l'image de la recette a été entrée, on l'enregistre dans le dossier des images des recettes
        $Upload = new \Marmi\Upload($_FILES['image']);
        if($error != "L'image est vide") {
            $image = $Upload->move("Recette", $id);
        }


        $Recette = new \Marmi\Recette($title,$image,$description,$instruction,$id);

        // Si un tag n'est pas dans la BDD, on l'ajoute
        $Tags = array();
        $TagsManquant = array();
        if(!empty($tabTags)) {
            foreach ($tabTags as $tag) {
                if (!$MarmiDB->testTag($tag->getNom())) {
                    $MarmiDB->addTag($tag);
                }
            }
        }

        $Recette->addIngredients($tabIngs);
        if(!empty($Tags))
            $Recette->addTags($Tags);


        $IngsManquants = $MarmiDB->testIngredients($Recette);

        if($_FILES["imageIng"] != null)
            $Upload->setFile($_FILES["imageIng"]);

        $i=0;
        //Pour chaque ingrédient pas dans la BDD
        foreach($IngsManquants as $ing){
            // On déplace l'image dans le dossier IMG/Ingredients
            $imageIng = $Upload->moveIng("Ingredients", $ing->getNom(), $i);
            $ing->setImage($imageIng);
            $MarmiDB->addIngredient($ing);
            $i++;
        }


        $MarmiDB->modifierRecette($Recette->getId(), "titre", $title);
        $MarmiDB->modifierRecette($Recette->getId(), "description", $description);
        $MarmiDB->modifierRecette($Recette->getId(), "instruction", $instruction);
        $MarmiDB->modifierRecette($Recette->getId(), "image", $image);
        $MarmiDB->modifierRecette($Recette->getId(), "titre", $title);

        $MarmiDB->modifierIngredientRecette($Recette, $tabIngs);
        $MarmiDB->modifierTagRecette($Recette,  $Tags);


        echo "<span id='recetteCree' >Recette modifiée avec succès !</span>";
    }
}

$content = ob_get_clean();

Marmi\Template::render($content);