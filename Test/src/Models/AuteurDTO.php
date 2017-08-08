<?php
namespace m2i\ecommerce\Entity;

class AuteurDTO {

    private static $columnMap = [
       'id_auteur' => 'idAuteur', 
'nom_auteur' => 'nomAuteur', 
'prenom_auteur' => 'prenomAuteur', 
'biographie' => 'biographie'
    ];

    private $idAuteur;
private $nomAuteur;
private $prenomAuteur;
private $biographie;

    public function __set($name, $value)
    {
        if(array_key_exists($name, self::$columnMap)){
            $attributeName = self::$columnMap[$name];
            $this->$attributeName = $value;
        }
    }

    public function hydrate(array $data)
    {
        foreach ($data as $key => $val) {
            $methodName = "set" . ucfirst($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($val);
            } else {
                if (array_key_exists($key, self::$columnMap)) {
                    $methodName = $methodName = "set" . ucfirst(self::$columnMap[$key]);
                    $this->$methodName($val);
                }
            }
        }
    }



    public function setId($idAuteur){
            $this->idAuteur = $idAuteur;
            return $this;
        }
public function getId(){
            return $this->idAuteur;
        }
public function setNomAuteur($nomAuteur){
            $this->nomAuteur = $nomAuteur;
            return $this;
        }
public function getNomAuteur(){
            return $this->nomAuteur;
        }
public function setPrenomAuteur($prenomAuteur){
            $this->prenomAuteur = $prenomAuteur;
            return $this;
        }
public function getPrenomAuteur(){
            return $this->prenomAuteur;
        }
public function setBiographie($biographie){
            $this->biographie = $biographie;
            return $this;
        }
public function getBiographie(){
            return $this->biographie;
        }



}