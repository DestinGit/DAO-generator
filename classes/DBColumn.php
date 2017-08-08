<?php

class DBColumn
{

    private $columnName;

    private $varName;

    private $sqlType;

    private $primary = false;

    private $nullable = false;
    
    private $autoIncrement = false;

    /**
     * DBColumns constructor.
     * @param $columnName
     * @param $sqlType
     * @param bool $primary
     * @param bool $nullable
     */
    public function __construct($columnName, $sqlType, $nullable, $primary, $autoIncrement)
    {
        $this->columnName = $columnName;
        $this->sqlType = $sqlType;
        $this->primary = $primary;
        $this->nullable = $nullable;
        $this->autoIncrement = $autoIncrement;

        $this->varName = Utils::camelize($this->columnName);
    }

    public function getClassAttributeDeclaration(){
        return "private \$". $this->varName.";";
    }

    public function getMapElement(){
        return "'".$this->columnName."' => '".$this->varName."'";
    }

    public function getSetterMethod(){
        //$methodName = "set". ucfirst($this->varName);
        $methodName = $this->getSetterName();

        $str = "public function $methodName(\${$this->varName}){
            \$this->{$this->varName} = \${$this->varName};
            return \$this;
        }";

        return $str;
    }

    public function getGetterName(){
        $methodName = "";
        if($this->isPrimary()){
            $methodName = "getId";
        }else {
            $methodName = "get". ucfirst($this->varName);
        }

        return $methodName;

    }

    public function getSetterName(){
        $methodName = "";
        if($this->isPrimary()){
            $methodName = "setId";
        }else {
            $methodName = "set". ucfirst($this->varName);
        }

        return $methodName;
    }

    public function getGetterMethod(){
        //$methodName = "get". ucfirst($this->varName);
        $methodName = $this->getGetterName();

        $str = "public function $methodName(){
            return \$this->{$this->varName};
        }";

        return $str;
    }

    public function getGetterInvocation(){
        //return "\$this->get". ucfirst($this->varName). "()";
        return "\$this->". $this->getGetterName()."()";
    }
    
    /**
     * @return mixed
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @param mixed $columnName
     * @return DBColumn
     */
    public function setColumnName($columnName)
    {
        $this->columnName = $columnName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVarName()
    {
        return $this->varName;
    }

    /**
     * @param mixed $varName
     * @return DBColumn
     */
    public function setVarName($varName)
    {
        $this->varName = $varName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSqlType()
    {
        return $this->sqlType;
    }

    /**
     * @param mixed $sqlType
     * @return DBColumn
     */
    public function setSqlType($sqlType)
    {
        $this->sqlType = $sqlType;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isPrimary()
    {
        return $this->primary;
    }

    /**
     * @param boolean $primary
     * @return DBColumn
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isNullable()
    {
        return $this->nullable;
    }

    /**
     * @param boolean $nullable
     * @return DBColumn
     */
    public function setNullable($nullable)
    {
        $this->nullable = $nullable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isAutoIncrement()
    {
        return $this->autoIncrement;
    }

    /**
     * @param boolean $autoIncrement
     * @return DBColumn
     */
    public function setAutoIncrement($autoIncrement)
    {
        $this->autoIncrement = $autoIncrement;

        return $this;
    }

    
    

}