<?php
require 'autoload.php';

$pdo = new PDO(
    'mysql:host=localhost;dbname=bibliotheque',
    'root',
    ''
);

$path = __DIR__;

$nameSpace = "m2i\\bibliotheque";

$codeGenerator = new CodeGenerator(
    $pdo,
    __DIR__."/output",
    __DIR__."/templates",
    $nameSpace
);

$codeGenerator->run();
