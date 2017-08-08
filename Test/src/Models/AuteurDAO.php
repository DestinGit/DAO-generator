<?php
namespace m2i\ecommerce\DAO;

use m2i\ecommerce\Entity\AuteurDTO;

class AuteurDAO implements IAuteurDAO {

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
        $sql = "SELECT * FROM auteurs";
        $this->selectStatement = $this->pdo->query($sql);
        return $this;
    }

    /**
    * @param array $pk
    * @return $this
    */
    public function findOneById(array $pk){
        $sql = "SELECT * FROM auteurs WHERE id_auteur=? ";
        $statement = $this->pdo->prepare($sql);
        $statement->execute($pk);
        $this->selectStatement = $statement;
        return $this;
    }

    /**
     * @param array $search
     * @param array $orderBy
     * @param array $limit
     * @return $this
     */
    public function find(array $search = [], array $orderBy = [], array $limit = []){
        $sql = "SELECT * FROM auteurs ";

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
                }, array_keys($orderBy)
            );
            $sql .= implode(", ", $cols);
        }

        if(count($limit) >0){
            $sql .= " LIMIT ".$limit[0];
            if(isset($limit[1])){
                $sql .= " OFFSET ". $limit[1];
            }

        }

        var_dump($sql);

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
        $data = array_map(function ($item){
           return $item[0];
        }, $data);
        var_dump($data);
        return $data;
    }

    /**
    * @return array
    */
    public function getOneAsArray(){
        return $this->selectStatement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
    * @return array
    */
    public function getAllAsEntity(){
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, AuteurDTO::class);
        return $this->selectStatement->fetchAll();
    }

    /**
    * @return AuteurDTO
    */
    public function getOneAsEntity(){
        $this->selectStatement->setFetchMode(\PDO::FETCH_CLASS, AuteurDTO::class);
        return $this->selectStatement->fetch();
    }

    /**
    * @param AuteurDTO $auteur
    */
    public function save(AuteurDTO $auteur){
        if($auteur->getId() == null){
            $pk = $this->insert($auteur);
            $auteur->setId($pk);
        } else {
            $this->update($auteur);
        }
    }

    /**
    * @param AuteurDTO $auteur
    * @return int
    */
    private function insert(AuteurDTO $auteur){
        $sql = "INSERT INTO auteurs (nom_auteur, prenom_auteur, biographie) VALUES ( ?, ?, ? )";
        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            $auteur->getNomAuteur(), $auteur->getPrenomAuteur(), $auteur->getBiographie()
        ]);
        return $this->pdo->lastInsertId();
    }

    /**
    * @param AuteurDTO $auteur
    */
    private function update(AuteurDTO $auteur){
        $sql = "UPDATE auteurs SET nom_auteur=? , prenom_auteur=? , biographie=?  WHERE id_auteur=? ";
        $data = array(
            $auteur->getNomAuteur(),
$auteur->getPrenomAuteur(),
$auteur->getBiographie(),
$auteur->getId()
        );
        $statement = $this->pdo->prepare($sql);
    }

    /**
    * @param AuteurDTO $auteur
    * @return bool
    */
    public function delete(AuteurDTO $auteur){
        if($auteur->getId() != null){
            $sql = "DELETE FROM auteurs WHERE id_auteur=? ";
            $statement = $this->pdo->prepare($sql);
            return $statement->execute([$auteur->getId()]);
        }
    }

}