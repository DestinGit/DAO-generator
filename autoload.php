<?php
function autoloader($class) {
    $path = 'classes/' . $class . '.php';
    if(file_exists($path)){
        require_once $path;
    } else {
        throw new Exception("le fichier n'existe pas");
    }

}

spl_autoload_register('autoloader');