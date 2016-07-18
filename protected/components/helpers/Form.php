<?php
namespace app\components\helpers;

use app\components\Model;

/**
 * Class Form
 * @package app\components\helpers
 */
class Form
{
    /**
     * @param Model $model
     * @return string
     */
    public static function getFormName(\app\components\Model $model)
    {
        return md5(get_class($model));
    }

    /**
     * @param $action
     * @param string $method
     * @param array $options
     * @return string
     */
    public static function begin($action = null, $method = 'post', $options = [])
    {
        if (!$action) {
            $action = [''] + $_GET;
        }
        $options['method'] = $method;
        $options['action'] = Url::to($action);
        return Html::openTag('form', $options);
    }

    /**
     * @param Model $model
     * @param $attribute
     * @param array $options
     * @return string
     */
    public static function activeLabel(Model $model, $attribute, $options = [])
    {
        if (!isset($options['for'])) {
            $options['for'] = self::activeId($model, $attribute);
        }

        return implode([
            Html::openTag('label', $options),
            $model->getAttributeLabel($attribute),
            Html::closeTag('label')
        ]);
    }

    /**
     * @param Model $model
     * @param $attribute
     * @param $type
     * @param array $options
     * @return string
     */
    public static function activeInput(Model $model, $attribute, $type, $options = [])
    {
        if (!isset($options['id'])) {
            $options['id'] = self::activeId($model, $attribute);

        }
        return self::input(
            self::activeName($model, $attribute),
            $model->$attribute,
            $type,
            $options
        );
    }

    public static function input($name, $value, $type, $options = [])
    {
        $options = array_merge(
            [
                'name'  => $name,
                'value' => $value,
            ],
            $options
        );
        $options['type'] = $type;
        return Html::openTag('input', $options);
    }

    /**
     * @param Model $model
     * @param $attribute
     * @param array $options
     * @return string
     */
    public static function activeTextInput(Model $model, $attribute, $options = [])
    {
        return self::activeInput($model, $attribute, 'text', $options);
    }

    public static function activeTextArea(Model $model, $attribute, $options = [])
    {
        if (!isset($options['id'])) {
            $options['id'] = self::activeId($model, $attribute);
        }
        $options['name'] = self::activeName($model, $attribute);
        return Html::tag('textarea', htmlspecialchars($model->$attribute), $options);
    }

    /**
     * @param Model $model
     * @param $attribute
     * @param array $options
     * @return string
     */
    public static function activePasswordInput(Model $model, $attribute, $options = [])
    {
        return self::activeInput($model, $attribute, 'password', $options);
    }

    /**
     * @param Model $model
     * @param $attribute
     * @param array $options
     * @return string
     */
    public static function activeHiddenInput(Model $model, $attribute, $options = [])
    {
        return self::activeInput($model, $attribute, 'hidden', $options);
    }

    public static function hiddenInput($name, $value, $options = [])
    {
        return self::input($name, $value, 'hidden', $options);
    }


    /**
     * @param Model $model
     * @param $attribute
     */
    public static function activeCheckbox(Model $model, $attribute, $options = [])
    {
        if ($model->$attribute) {
            $options['checked'] = true;
        }
        $options['value'] = 1;

        return implode([
            self::hiddenInput(self::activeName($model, $attribute), 0),
            self::activeInput($model, $attribute, 'checkbox', $options)
        ]);
    }


    /**
     * @param $model
     * @param $attribute
     * @return string
     */
    public static function activeName($model, $attribute)
    {
        return self::getFormName($model) . '[' . $attribute . ']';
    }

    /**
     * @param $model
     * @param $attribute
     * @return string
     */
    public static function activeId($model, $attribute)
    {
        return 'id_' . self::getFormName($model) . '_' . $attribute;
    }

    public static function activeError(Model $model, $attribute)
    {
        if ($model->hasErrors($attribute)) {
            return Html::tag('p', $model->getAttributeErrors($attribute), ['class' => 'text-danger']);
        }
    }

    /**
     * @return string
     */
    public static function end()
    {
        return Html::closeTag('form');
    }
}