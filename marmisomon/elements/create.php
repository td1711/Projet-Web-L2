<?php

use Marmi\Ingredient;
use Marmi\Tag;

require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";
error_reporting(0);
session_start();

$RecetteCreator = new \Marmi\RecetteCreator();
$MarmiDB = new \Marmi\MarmiDB();
$listeIngredients = $MarmiDB->returnArrayIngredients();

ob_start();

if(isset($_POST["name"])){ // Si le formulaire a été rempli
    $tabIngs = $_POST["ingredients"];
    $tabTags = $_POST["tags"];
    $tabSaisons = $_POST["saison"];
    $tabImageIng = $_POST["imageIng"];
    $tabTags = $_POST["tags"];
    $key = array_search("", $tabTags, true);
    if ($key !== false) {
        unset($tabTags[$key]);
    }
    $tabIngsManquants = array();

    // On instancie chaque ingrédient à partir des noms récoltés
    $indice = 0;
    if(!empty($_POST["ingredients"])){
        for($i=0; $i<count($tabIngs); $i++){
            $saison = "";
            // Si l'ingrédient n'existe pas
            if (!$MarmiDB->testIngredient($tabIngs[$i])) {
                $tabIngsManquants[] = $tabIngs[$i];
                $saison = htmlspecialchars($tabSaisons[$indice]);
                $indice++;
            }
            $tabIngs[$i] = new Ingredient(htmlspecialchars($tabIngs[$i]), "", $saison, 1);
        }
    }
    if(!empty($_POST["tags"])){
        for($i=0; $i<count($tabTags); $i++){
            $tabTags[$i] = new Tag(htmlspecialchars($tabTags[$i]),1);
        }
    }

    if(!empty($_POST["saison"])){
        for($i=0; $i<count($tabSaisons); $i++){
            $tabSaisons[$i] = htmlspecialchars($tabSaisons[$i]);
        }
    }

    // On vérifie les informations fournies par l'utilisateur et on récupère l'erreur à afficher ou pas
    $error = $RecetteCreator->verify($_POST["name"], $_FILES['image'], $_POST["description"], $tabIngs, $_POST["instruction"]);
}

if (!isset($error)) : // Si le formulaire n'a pas encore été rempli (l'utilisateur vient d'arriver sur la page de création)
    $RecetteCreator->generateForm("Nouvelle Recette", "create.php","","","",array(),array(),"", "");

elseif ($error != "") : // Si le formulaire vient d'être rempli et qu'il y a une erreur
    // On affiche l'erreur
    echo "<div class='marmi-error' id='error'>" . $error . "</div>";
    // On génère le formulaire avec les informations remplies
    $RecetteCreator->generateForm("Nouvelle Recette","create.php",$_POST["name"], $_POST["image"], $_POST["description"], $tabIngs, $tabTags,$_POST["instruction"], $tabSaisons);

else : // Si le formulaire vient d'être rempli et qu'il n'y a pas d'erreur
    $title = htmlspecialchars($_POST["name"]);
    $description = htmlspecialchars($_POST["description"]);
    $instruction = htmlspecialchars($_POST["instruction"]);

    $Upload = new \Marmi\Upload($_FILES['image']);

    $id = $MarmiDB->returnNewId(); // Renvoie l'id de la prochaine recette créée de la BDD
    $image = $Upload->move("Recette", $id); // Enregistre l'image de la recette avec son id comme nom

    $Recette = new \Marmi\Recette($title,$image,$description,$instruction,$id);

    $Recette->addIngredients($tabIngs);
    if(!empty($tabTags))
        $Recette->addTags($tabTags);

    if(isset($_FILES["imageIng"]))
        $Upload->setFile($_FILES["imageIng"]);


    $i=0;
    $IngsManquants = $MarmiDB->testIngredients($Recette);
    foreach($IngsManquants as $ing){ // Pour chaque ingrédient manquant
        if($ing->getNom() != "") {
            // on enregistre son image si elle a été donnée
            $imageIng = $Upload->moveIng("Ingredients", $ing->getNom(), $i);
            $ing->setImage($imageIng);
            // on l'ajoute dans la BDD
            $MarmiDB->addIngredient($ing);
        }
        $i++;
    }

    $TagsManquants = $MarmiDB->testTags($Recette);
    foreach($TagsManquants as $tag){
        $MarmiDB->addTag($tag);
    }

    $MarmiDB->addRecette($Recette);

    echo "<span id='recetteCree' >Recette créée avec succès !</span>";

endif;

$content = ob_get_clean();

Marmi\Template::render($content);