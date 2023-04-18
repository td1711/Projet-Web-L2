<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../class" . DIRECTORY_SEPARATOR . "autoloader.php";

session_start();

use Marmi\Template;

$recette = htmlspecialchars($_GET["recette"]);
$MarmiDB = new \Marmi\MarmiDB();
$ArrayRecettes = $MarmiDB->getAllRecettes($recette);
foreach($ArrayRecettes as $rec){
    if($rec->getTitre() == $recette)
        $recette = $rec;
}
ob_start();


$modif = false;
if(isset($_GET["modif"]) && isset($_SESSION["login"]) && $_GET["modif"] == "oui")
    $modif = true;
$div = "div";
if($modif)
    $div = "input";

?>
<form id="RecetteComplete" action="recette.php?recette=<?= $recette->getTitre()?>" method="POST">
    <div id="titre">
        <?php echo $recette->getTitre();?>
    </div>

    <div id="IngEtImage">
        Ingredients
        <div id="Ingredients">
            <?php
            foreach ($recette->getIngredients() as $ing) {
                ?><div class='Ing'><?=$ing->getNom()?> </div><?php
            }
            ?>
        </div>
        <div id="image">
            <img src="..<?= DIRECTORY_SEPARATOR."IMG".DIRECTORY_SEPARATOR."Recette".DIRECTORY_SEPARATOR.$recette->getPhoto()?>">
        </div>
    </div>
    Tags :
    <div id="Tags">
        <?php
        foreach ($recette->getTags() as $tag):
            ?><div class='Tag'><?=$tag->getNom()?> </div><?php endforeach;
        ?>
    </div>
    Description
    <div id="Description">

        <?= $recette->getDescription()?>
    </div>
</form>

<?php

$content = ob_get_clean();

Template::render($content);