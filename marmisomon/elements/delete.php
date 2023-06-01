<?php
require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";

$MarmiDB = new \Marmi\MarmiDB();
if(isset($_GET["recette"])) { // Si on supprime une recette
    $id = $_GET["recette"];

    $recette = $MarmiDB->returnRecette($id);

    $cheminImage = ".." . DIRECTORY_SEPARATOR . "IMG" . DIRECTORY_SEPARATOR
        . "Recette" . DIRECTORY_SEPARATOR . $recette->getPhoto();

    unlink($cheminImage);

    $MarmiDB->supprimerRecette($id);
}
else if(isset($_GET["ingredient"])){ // Si on supprime un ingrédient
    $id = $_GET["ingredient"];

    $image = $MarmiDB->recupererImageIngredient($id);

    $cheminImage = ".." . DIRECTORY_SEPARATOR . "IMG" . DIRECTORY_SEPARATOR
        . "Ingredients" . DIRECTORY_SEPARATOR . $image[0]->image;

    unlink($cheminImage);

    $MarmiDB->supprimerIngredient($id);
}
else if(isset($_GET["tag"])){ // Si on supprime un tag
    $id = $_GET["tag"];
    $MarmiDB->supprimerTag($id);
}
?>

<?php
header("Location: recherche.php");