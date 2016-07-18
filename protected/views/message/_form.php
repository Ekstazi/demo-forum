<?php
use app\components\helpers\Form;

/** @var $this \app\components\View */
/** @var $model \app\models\Message */
?>
<?= Form::begin(); ?>
<div class="form-group">
    <?= Form::activeLabel($model, 'message'); ?>
    <?= Form::activeTextArea($model, 'message', ['class' => 'form-control', 'placeholder' => 'Текст сообщения']); ?>
    <?= Form::activeError($model, 'message') ?>
</div>
<div class="form-group">
    <input type="submit" value="<?= $model->isNewRecord ? 'Добавить' : 'Сохранить' ?>" class="btn btn-primary">
</div>
<?= Form::end(); ?>

