<?php

namespace app\components\db;


use app\components\Model;

abstract class Entity extends Model
{
    public $isNewRecord = true;
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_INSERT = 'insert';

    /**
     * @var Repository
     */
    protected $repository;

    public function __construct(Repository $repository)
    {
        $pk = $repository->primaryKey();

        if (isset($this->$pk)) {
            $this->scenario = self::SCENARIO_UPDATE;
            $this->isNewRecord = false;
        } else {
            $this->scenario = self::SCENARIO_INSERT;
        }

        $this->repository = $repository;
    }

    public function save()
    {
        if (!$this->validate())
            return false;
        if (!$this->beforeSave())
            return false;

        $result = $this->repository->save($this);
        $this->afterSave();
        return $result;
    }

    public function beforeSave()
    {
        return true;
    }

    public function afterSave()
    {

    }

    public function toArray()
    {

    }

    public function getAttributes($safeOnly = false)
    {
        $attributes = parent::getAttributes($safeOnly);
        unset($attributes['isNewRecord']);
        return $attributes;
    }

    public function delete()
    {
        return $this->repository->deleteByPk($this->id);
    }
}