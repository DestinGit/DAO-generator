<?php

class CodeGenerator
{
    /**
     * @var \PDO
     */
    private $pdo;

    private $outputPath;

    private $templatePath;

    private $nameSpace;

    /**
     * CodeGenerator constructor.
     * @param PDO $pdo
     * @param $outputPath
     * @param $templatePath
     * @param $nameSpace
     */
    public function __construct(PDO $pdo, $outputPath, $templatePath, $nameSpace)
    {
        $this->pdo = $pdo;
        $this->outputPath = $outputPath;
        $this->templatePath = $templatePath;
        $this->nameSpace = $nameSpace;
    }

    public function run(){

        $DTOTemplate = $this->getTemplate('DTO.tpl');
        $DAOTemplate = $this->getTemplate('DAO.tpl');
        $InterfaceTemplate = $this->getTemplate('IDAO.tpl');

        $placeHolders = [
            '{{attributes}}',
            '{{fieldsList}}',
            '{{methods}}',
            '{{tableName}}',
            '{{DTOName}}',
            '{{DAOName}}',
            '{{interfaceName}}',
            '{{pkValues}}',
            '{{placeholders}}',
            '{{valuesList}}',
            '{{updateList}}',
            '{{pkNames}}',
            '{{pkWhereClause}}',
            '{{dtoVarName}}',
            '{{updateValues}}',
            '{{columnMap}}',
            '{{nameSpace}}',
        ];


        foreach ($this->getTables() as $row){
            $table = new DBTable($row[0], $this->pdo);
            $vars = $table->getVarsForTemplate();
            $vars['{{nameSpace}}'] = $this->nameSpace;

            $dtoCode = str_replace($placeHolders,$vars,$InterfaceTemplate);
            file_put_contents($this->outputPath."/".$table->getInterfaceName().".php", $dtoCode);

            $dtoCode = str_replace($placeHolders,$vars,$DTOTemplate);
            file_put_contents($this->outputPath."/".$table->getDTOClassName().".php", $dtoCode);

            $daoCode = str_replace($placeHolders,$vars,$DAOTemplate);
            file_put_contents($this->outputPath."/".$table->getDAOClassName().".php", $daoCode);

        }
    }



    private function getTables(){
        $sql = "SHOW FULL TABLES WHERE Table_type='BASE TABLE';";
        $rs = $this->pdo->query($sql);
        $data = $rs->fetchAll(PDO::FETCH_NUM);
        return $data;
    }

    private function getTemplate($fileName){
        return file_get_contents($this->templatePath."/$fileName");
    }


}