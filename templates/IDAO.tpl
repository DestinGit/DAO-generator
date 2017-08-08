<?php
namespace {{nameSpace}}\DAO;

use {{nameSpace}}\Entity\{{DTOName}};

interface {{interfaceName}} {

    public function findAll();

    public function findOneById(array $pk);

    public function find(array $search);

    public function delete({{DTOName}} {{dtoVarName}});

    public function save ({{DTOName}} {{dtoVarName}});

}