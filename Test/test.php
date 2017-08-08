<?php

use m2i\ecommerce\DAO\AuteurDAO;
use m2i\ecommerce\Entity\AuteurDTO;

$pdo = new PDO(
    'mysql:host=localhost;dbname=ecommerce',
    'root',
    ''
);

include 'vendor/autoload.php';

try {
    $post = [
        "nom_auteur" => "Herbert",
        "prenom_auteur" => "Frank",
    ];

    $dao = new AuteurDAO($pdo);
    $auteur = new AuteurDTO();

    $data = $dao->find([],["nom_auteur" => "ASC"])->getAllAsArray();

    var_dump($data);
/*
    $auteur = $dao->findOneById([2])->getOneAsEntity();


    $auteur->setId(null);

    $dao->save($auteur);

    $auteur->hydrate($post);

    $dao->save($auteur);

*/
//$auteur->hydrate($post);

    /*
    $auteur->setNom("Hugo")
        ->setPrenom("Victor");
    */
//$dao->save($auteur);
/*
    var_dump($auteur);

    $data = $dao->findAll()->getAllAsArrayGroupedById();

    var_dump($data);
*/
} catch (PDOException $e) {
    echo $e->getMessage();
}
