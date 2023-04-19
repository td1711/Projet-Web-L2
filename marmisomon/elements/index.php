<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../class" . DIRECTORY_SEPARATOR . "autoloader.php";

session_start();

use Marmi\Template;

ob_start();

<h1>Projet en construction</h1>


$content = ob_get_clean();

Template::render($content);
