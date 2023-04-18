<?php

function autoload($class_name) :void{
    $class_name = str_replace('\\', '/', $class_name) ;
    require $class_name . '.php' ;
}
spl_autoload_register('autoload') ;