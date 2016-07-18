<?php

namespace app\components\db;

use app\components\Component;

class Query extends Component
{
    protected $statement;

    public function __construct(\PDOStatement $statement)
    {
        $this->statement = $statement;
    }

    public function execute($params = [])
    {
        $this->statement->execute($params);
        return $this->statement->rowCount();
    }

    public function fetchAll($params = [])
    {
        $this->statement->execute($params);
        return $this->statement->fetchAll();
    }

    public function fetchAllObject($className, $params = [], $ctor = [])
    {
        $this->statement->execute($params);
        return $this->statement->fetchAll(\PDO::FETCH_CLASS, $className, $ctor);
    }

    public function fetchColumn($columnName, $params= [])
    {
        $this->statement->execute($params);
        return $this->statement->fetchAll(\PDO::FETCH_COLUMN, $columnName);
    }
    
    public function bindValues($values)
    {
        foreach ($values as $name => $value) {
            $this->bindValue($name, $value);
        }
        return $this;
    }
}