<?php
use app\components\View;
use app\components\helpers\Form;

/** @var $this View */
/** @var $model \app\models\User */
?>
<div>
    <h3>Авторизация</h3>
    <?= Form::begin(); ?>
    <div class="form-group">
        <?= Form::activeLabel($model, 'email'); ?>
        <?= Form::activeTextInput($model, 'email', ['class' => 'form-control', 'placeholder' => 'email']); ?>
        <?= Form::activeError($model, 'email')?>
        <p class="help-block">На этот email мы отправим вам письмо с паролем и ссылку для подтверждения аккаунтв</p>
    </div>
    <div class="form-group">
        <input type="submit" value="Зарегистрироваться" class="btn btn-primary">
    </div>
    <?= Form::end(); ?>
</div>
