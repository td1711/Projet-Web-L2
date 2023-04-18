<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";

session_start();

$Recherche = new \Marmi\Recherche();
$MarmiDB = new \Marmi\MarmiDB();

ob_start();

if(isset($_POST["search"])){
    $tab = $MarmiDB->getAllRecettes(trim(htmlspecialchars($_POST["search"])));
}

if (empty($tab)):
    echo "<span id='error404' style='color:red;margin:100px;'>Pas de recettes trouvées...</span>";
else :
    ?>
    <div id="Resultats">
        <div id="Resultats-Recette">
            <?php
            foreach($tab as $recette){
                $recette->render();
            }?>
        </div>
    </div>
<?php
endif;


$content = ob_get_clean();

Marmi\Template::render($content);