<?php

namespace app\components\db;


use app\components\Component;

class Db extends Component
{
    public $dsn;

    public $username;

    public $password;

    public $options;

    protected $pdo;

    public function getPdoConnection()
    {

        if (isset($this->pdo)) {
            return $this->pdo;
        }

        $this->pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        $this->pdo->setAttribute(\PDO::ATTR_AUTOCOMMIT, true);
        $this->pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        return $this->pdo;
    }

    /**
     * @param $sql
     * @return Query
     */
    public function createQuery($sql)
    {
        return new Query($this->getPdoConnection()->prepare($sql));
    }

    /**
     * @param $name
     * @return Repository
     * @throws \Exception
     */
    public function getRepository($name)
    {
        $className = 'app\\repositories\\' . ucfirst($name) . 'Repository';
        if (!class_exists($className)) {
            throw new \Exception('Unknown repository: ' . $name . ', class used: ' . $className);
        }
        return new $className($this, $name);
    }
    
    public function lastInsertId()
    {
        return $this->getPdoConnection()->lastInsertId();
    }
}
