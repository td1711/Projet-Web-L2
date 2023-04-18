<?php
require_once "..".DIRECTORY_SEPARATOR."class".DIRECTORY_SEPARATOR."autoloader.php";

$id = $_GET["recette"];
$MarmiDB = new \Marmi\MarmiDB();

$MarmiDB->supprimerRecette($id);
header("Location : index.php");