<?php
namespace {{nameSpace}}\DAO;

use {{nameSpace}}\Entity\{{DTOName}};

class {{DAOName}} implements {{interfaceName}} {

    /**
    * @var \PDO
    */
    private $pdo;

    /**
     * @var \PDOStatement;
     */
    private $selectStatement;


    /**
    * DAOClient constructor.
    * @param \PDO $pdo
    */
    public function __construct(\PDO $pdo)
    {
    $this->pdo = $pdo;
    }

    /**
    * @return $this
    */
    public function findAll(){
        $sql = "SELECT * FROM {{tableName}}";
        $this->selectStatement = $this->pdo->query($sql);
        return $this;
    }

    /**
    * @param array $pk
    * @return $this
    */
    public function findOneById(array $pk){
        $sql = "SELECT * FROM {{tableName}} WHERE {{pkWhereClause}}";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($pk);
        $this->selectStatement = $statement;
        return $this;
    }

    /**
    * @param array $search
    * @return $this
    */
    public function find(array $search = [], array $orderBy = [], array $limit = []){
        $sql = "SELECT * FROM {{tableName}} ";

        if(count($search)>0){
            $sql .= " WHERE ";
            $cols = array_map(
                function($item){
                    return "$item=:$item";
                }, array_keys($search)
            );

            $sql .= implode(" AND ", $cols);
        }

        if(count($orderBy)>0){
            $sql .= "ORDER BY ";
            $cols = array_map(
                function($item) use($orderBy){
                    return "$item ". $orderBy[$item];
                },
                array_keys($orderBy)
            );
            $sql .= implode(", ", $cols);
        }

        if(count($limit) >0){
            $sql .= " LIMIT ".$limit[0];
            if(isset($limit[1])){
                $sql .= " OFFSET ". $limit[1];
            }
        }

        $statement = $this->pdo->prepare($sql);
        $statement->execute($search);
        $this->selectStatement = $statement;
        return $this;
    }


    /**
    * @return array
    */
    public function getAllAsArray(){
        return $this->selectStatement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
    * @return array
    */
    public function getAllAsArrayGroupedById(){
        $data =  $this->selectStatement->fetchAll(\PDO::FETCH_ASSOC|\PDO::FETCH_GROUP);

        $data = array_map(
            function ($item){
                return $item[0];
            }
            , $data
        );
        
        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
        
    }

    /**
    * @return array
    */
    public function getOneAsArray(){
        $data = $this->selectStatement->fetch(\PDO::FETCH_ASSOC);

        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }

    /**
    * @return array
    */
    public function getAllAsEntity(){
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, {{DTOName}}::class);
        $data = $this->selectStatement->fetchAll();

        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }

    /**
    * @return {{DTOName}}
    */
    public function getOneAsEntity(){
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, {{DTOName}}::class);
        $data = $this->selectStatement->fetch();

        if($data){
            return $data;
        } else {
            throw new Exception("Ancun résultat pour cette requête");
        }
    }

    /**
    * @param {{DTOName}} {{dtoVarName}}
    */
    public function save({{DTOName}} {{dtoVarName}}){
        if({{dtoVarName}}->getId() == null){
            $pk = $this->insert({{dtoVarName}});
            {{dtoVarName}}->setId($pk);
        } else {
            $this->update({{dtoVarName}});
        }
    }

    /**
    * @param {{DTOName}} {{dtoVarName}}
    * @return int
    */
    private function insert({{DTOName}} {{dtoVarName}}){
        $sql = "INSERT INTO {{tableName}} ({{fieldsList}}) VALUES ( {{placeholders}} )";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            {{valuesList}}
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
    * @param {{DTOName}} {{dtoVarName}}
    */
    private function update({{DTOName}} {{dtoVarName}}){
        $sql = "UPDATE {{tableName}} SET {{updateList}} WHERE {{pkWhereClause}}";
        $data = array(
            {{updateValues}}
        );
        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            {{updateValues}}

        ]);

    }

    /**
    * @param {{DTOName}} {{dtoVarName}}
    * @return bool
    */
    public function delete({{DTOName}} {{dtoVarName}}){
        if({{dtoVarName}}->getId() != null){
            $sql = "DELETE FROM {{tableName}} WHERE {{pkWhereClause}}";
            $statement = $this->pdo->prepare($sql);
            return $statement->execute([{{pkValues}}]);
        }
    }

}