<?php

namespace app\components\db;


abstract class Repository
{
    protected $id;

    protected $connection;

    public function __construct(Db $connection, $id)
    {
        $this->connection = $connection;
        $this->id = $id;
    }

    public function findAllBySql($sql, $params = [])
    {
        $entityClass = 'app\\models\\' . ucfirst($this->id);

        if (!class_exists($entityClass)) {
            throw new \Exception('Cannot find entity class: ' . $entityClass);
        }

        return $this->connection->createQuery($sql)->fetchAllObject($entityClass, $params, [$this]);
    }

    public function findAll()
    {
        return $this->findAllBySql('select * from `' . $this->tableName() . '`');
    }

    public function findBySql($sql, $params = [])
    {
        $result = $this->findAllBySql($sql, $params);
        return count($result) ? $result[0] : false;
    }

    public function findByPk($id)
    {
        $tableName = $this->tableName();
        $pk = $this->primaryKey();
        $q = "select * from `{$tableName}` where `{$pk}` = :id";

        return $this->findBySql($q, [':id' => $id]);
    }

    public function save(Entity $entity)
    {
        if ($entity->isNewRecord)
            return $this->insert($entity);
        else
            return $this->update($entity);
    }

    public function insert(Entity $entity)
    {
        $tableName = $this->tableName();
        $attributes = $entity->getAttributes();
        unset($attributes['id']);

        $columns = [];
        $values = [];
        $parts = [];
        $i = 0;
        foreach ($attributes as $column => $value) {
            $columns[] = "`{$column}`";
            $placeholder = ":v{$i}";
            $parts[] = $placeholder;
            $values[$placeholder] = $value;
            $i++;
        }
        $columns = '(' . implode(', ', $columns) . ')';
        $parts = '(' . implode(', ', $parts) . ')';

        $query = "insert into `{$tableName}` {$columns} values {$parts}";
        $result = $this->connection->createQuery($query)->execute($values);
        if (!$result) {
            return false;
        }
        $entity->{$this->primaryKey()} = $this->connection->lastInsertId();
        return $result;
    }

    public function update(Entity $entity)
    {
        $tableName = $this->tableName();
        $attributes = $entity->getAttributes();

        $values = [];
        $parts = [];
        $i = 0;
        foreach ($attributes as $column => $value) {
            $placeholder = ":v{$i}";
            $parts[] = "{$column}={$placeholder}";
            $values[$placeholder] = $value;
            $i++;
        }
        $values[':id'] = $attributes[$this->primaryKey()];
        $parts = implode(', ', $parts);

        $query = "update `{$tableName}` set {$parts} where " . $this->primaryKey() . '=:id';
        return $this->connection->createQuery($query)->execute($values);
    }

    /**
     * @return Entity
     */
    public function create()
    {
        $entityClass = 'app\\models\\' . ucfirst($this->id);
        return new $entityClass($this);
    }

    public function deleteByPk($pk)
    {
        $primary = $this->primaryKey();
        $tableName = $this->tableName();
        $query = "delete from `{$tableName}` where {$primary} = :id";
        return $this->connection->createQuery($query)->execute([':id' => $pk]);
    }

    /**
     * @return string
     */
    abstract public function tableName();

    public function primaryKey()
    {
        return 'id';
    }

}