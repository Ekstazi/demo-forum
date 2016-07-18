<?php
use app\components\View;
use app\components\helpers\Form;

/** @var $this View */
/** @var $model \app\models\LoginForm */
?>
<div>
    <h3>Авторизация</h3>
    <?= Form::begin(); ?>
    <div class="form-group">
        <?= Form::activeLabel($model, 'email'); ?>
        <?= Form::activeTextInput($model, 'email', ['class' => 'form-control', 'placeholder' => 'email']); ?>
        <?= Form::activeError($model, 'email') ?>
    </div>
    <div class="form-group">
        <?= Form::activeLabel($model, 'password'); ?>
        <?= Form::activePasswordInput($model, 'password', ['class' => 'form-control', 'placeholder' => 'password']); ?>
        <?= Form::activeError($model, 'password') ?>
    </div>
    <div class="form-group">
        <label>
            <?= Form::activeCheckbox($model, 'rememberMe'); ?> Запомнить меня
        </label>
        <?= Form::activeError($model, 'rememberMe') ?>
    </div>

    <div class="form-group">
        <input type="submit" value="Войти" class="btn btn-primary">
    </div>
    <?= Form::end(); ?>
</div>
