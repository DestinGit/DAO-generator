<?php

class DBTable
{

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var string
     */
    private $pkName;

    /**
     * @var PDO
     */
    private $pdo;
    

    public function __construct($tableName, $pdo)
    {
        $this->tableName = $tableName;
        $this->pdo = $pdo;
        
        $this->loadColumns();
    }

    /**
     * Chargement des informations sur les colonnes de la table
     */
    private function loadColumns(){
        $sql = "DESCRIBE ".$this->tableName;
        $rs = $this->pdo->query($sql);
        $data = $rs->fetchAll(PDO::FETCH_ASSOC);
        $rs = null;

        foreach ($data as $item){
            if($item['Key'] == 'PRI'){
                $this->pkName = $item['Field'];
            }
            $col = new DBColumn(
                $item['Field'],
                $item['Type'],
                $item['Null']=='NO'?false:true,
                $item['Key']=='PRI'?true:false,
                $item['Extra']=='auto_increment'?true:false
            );
            
            array_push($this->columns, $col);
        }

        var_dump($this->columns);
    }

    public function getBaseClassName(){
        $className = ucfirst(Utils::camelize($this->tableName));
        $wordLength = mb_strlen($className);
        if(substr($className,$wordLength-1,1)=='s'){
            $className = substr($className,0,$wordLength-1);
        }

        return $className;
    }

    /**
     * Le nom de la classe DTO
     * @return string
     */
    public function getDTOClassName(){
        return $this->getBaseClassName()."DTO";
    }

    /**
     * Le nom de la classe DAO
     * @return string
     */
    public function getDAOClassName(){
        return $this->getBaseClassName()."DAO";
    }

    public function getInterfaceName(){
        return "I".$this->getBaseClassName()."DAO";
    }

    public function getDTOVarName(){
        return "\$".lcfirst($this->getBaseClassName());
    }

    /**
     * 
     * @return string
     */
    public function getVarsForTemplate(){
        $fieldsList = [];
        $attributesList = [];
        $methodsList = [];
        $placeHolders = [];
        $valuesList = [];
        $updateList = [];
        $pkNames = [];
        $pkValues = [];
        $pkWhereClause = [];
        $columnMap = [];


        foreach ($this->columns as $item){
            if(! $item->isPrimary()){
                array_push($fieldsList, $item->getColumnName());
                array_push($placeHolders, '?');
                array_push($valuesList, $this->getDTOVarName()."->". $item->getGetterName()."()");
                array_push($updateList, $item->getColumnName()."=? ");
            } else {
                array_push($pkNames, $item->getColumnName());
                array_push($pkValues, $this->getDTOVarName()."->". $item->getGetterName()."()");
                array_push($pkWhereClause, $item->getColumnName()."=? ");
                if(! $item->isAutoIncrement()){
                    array_push($placeHolders, '?');
                    array_push($updateList, $item->getColumnName()."=? ");
                    array_push($fieldsList, $item->getColumnName());
                    array_push($valuesList, $this->getDTOVarName()."->". $item->getGetterName()."()");
                }
            }

            array_push($attributesList, $item->getClassAttributeDeclaration());
            array_push($methodsList,
                $item->getSetterMethod(). "\n". $item->getGetterMethod()
            );
            array_push($columnMap, $item->getMapElement());

        }
        return [
            '{{attributes}}'    => implode("\n", $attributesList),
            '{{fieldsList}}'    => implode(', ', $fieldsList),
            '{{methods}}'       => implode("\n", $methodsList),
            '{{tableName}}'     => $this->tableName,
            '{{DTOName}}'       => $this->getDTOClassName(),
            '{{DAOName}}'       => $this->getDAOClassName(),
            '{{interfaceName}}' => $this->getInterfaceName(),
            '{{pkValues}}'      => implode(", \n", $pkValues),
            '{{placeholders}}'  => implode(', ', $placeHolders),
            '{{valuesList}}'    => implode(', ', $valuesList),
            '{{updateList}}'    => implode(', ', $updateList),
            '{{pkNames}}'       => implode("\n", $pkNames),
            '{{pkWhereClause}}' => implode(' AND ', $pkWhereClause),
            '{{dtoVarName}}'    => $this->getDTOVarName(),
            '{{updateValues}}'  => implode(",\n",array_merge($valuesList, $pkValues)),
            '{{columnMap}}'    => implode(", \n", $columnMap),
            //'{{updateValues}}'    => implode(', ', $valuesList),

        ];
    }
    

    public function getPKGetterInvocation(){
        return $this->getDTOVarName()."->get". ucfirst(Utils::camelize($this->pkName)). "()";
    }


    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     * @return DBTable
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @param array $columns
     * @return DBTable
     */
    public function setColumns($columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * @param PDO $pdo
     * @return DBTable
     */
    public function setPdo($pdo)
    {
        $this->pdo = $pdo;

        return $this;
    }

    /**
     * @return string
     */
    public function getPkName()
    {
        return $this->pkName;
    }

    /**
     * @param string $pkName
     * @return DBTable
     */
    public function setPkName($pkName)
    {
        $this->pkName = $pkName;

        return $this;
    }


}