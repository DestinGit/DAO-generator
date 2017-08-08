<?php
namespace m2i\ecommerce\DAO;

use m2i\ecommerce\Entity\AuteurDTO;

interface IAuteurDAO {

    public function findAll();

    public function findOneById(array $pk);

    public function find(array $search, array $orderBy, array $limit);

    public function delete(AuteurDTO $auteur);

    public function save (AuteurDTO $auteur);

}