<?php

namespace app\components;


use app\components\helpers\Form;

abstract class Model extends Component
{
    public $scenario;

    protected $errors = [];

    public function load($params = [])
    {
        $formName = Form::getFormName($this);
        if (!isset($params[$formName])) {
            return false;
        }

        $this->setAttributes($params[$formName]);
        return true;
    }

    public function setAttributes($attributes = [])
    {
        $safe = $this->safeAttributes();
        foreach ($attributes as $name => $value) {
            if (!$this->canSetProperty($name) || !in_array($name, $safe)) {
                continue;
            }
            $this->$name = $value;
        }
    }

    public function getAttributes($safeOnly = false)
    {
        $values = [];
        $names = $this->getAttributeNames($safeOnly);
        foreach ($names as $name) {
            $values[$name] = $this->$name;
        }
        return $values;
    }

    public function safeAttributes()
    {
        $safe = [];
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $safe[] = $property->getName();
        }
        return $safe;
    }

    public function validate()
    {
        if (!$this->beforeValidate())
            return false;

        $result = $this->checkValid();
        $this->afterValidate();
        return $result;
    }

    /**
     * @return bool
     */
    abstract protected function checkValid();

    public function beforeValidate()
    {
        return true;
    }

    public function afterValidate()
    {

    }

    /**
     * @param $safeOnly
     * @return array
     */
    protected function getAttributeNames($safeOnly = false)
    {
        $properties = [];
        if ($safeOnly) {
            return $this->safeAttributes();
        }
        $reflection = new \ReflectionObject($this);
        foreach ($reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            if($name == 'scenario') {
                continue;
            }
            $properties[] = $name;
        }
        return $properties;
    }

    public function addError($attribute, $error)
    {
        $this->errors[$attribute][] = $error;
    }

    public function clearErrors()
    {
        $this->errors = [];
    }

    public function hasErrors($attribute = null)
    {
        return $attribute
            ? isset($this->errors[$attribute]) && count($this->errors[$attribute])
            : count($this->errors);
    }

    public function getAttributeErrors($attribute, $asString = true, $firstOnly = true)
    {
        if (!isset($this->errors[$attribute])) {
            return null;
        }

        $errors = $this->errors[$attribute];

        $errors = $firstOnly ? [$errors[0]] : $errors[0];
        return $asString ? implode('<br/>', $errors) : $errors;
    }


    public function getErrors($asString = true, $firstOnly = true)
    {
        $attributeNames = $this->getAttributeNames(true);
        $errors = [];
        foreach ($attributeNames as $attribute) {
            $error = $this->getAttributeErrors($attribute, $asString, $firstOnly);
            if (!$error) {
                continue;
            }
            $errors[] = $error;
        }

        return $asString ? implode('<br/<', $errors) : $errors;
    }

    public function attributeLabels()
    {
        return [];
    }

    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        return isset($labels[$attribute]) ? $labels[$attribute] : $attribute;
    }
}