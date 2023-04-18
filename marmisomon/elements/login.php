<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";


$Logger = new \Marmi\Logger();

ob_start();

?>  <?php
if(isset($_POST["username"]) and isset($_POST["password"])){
    $tab = $Logger->log(trim($_POST["username"]), $_POST["password"]);
    if($tab["granted"])
        $nick = $tab["nick"];
}

if (!isset($tab)) :
    $Logger->generateLoginForm("login.php");
elseif (!$tab['granted']) :
    echo "<div class='marmi-card' id='error'>" . $tab['error'] . "</div>";
    $Logger->generateLoginForm("login.php");
else :
    session_start();
    $_SESSION["login"] = $tab["nick"];
    header("Location: index.php");
endif;  ?>
 <?php

$content = ob_get_clean();

Marmi\Template::render($content);