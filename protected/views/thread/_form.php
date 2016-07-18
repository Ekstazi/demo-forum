<?php
use app\components\helpers\Form;

/** @var $this \app\components\View */
/** @var $model \app\models\Thread */
?>
<?= Form::begin(); ?>
<div class="form-group">
    <?= Form::activeLabel($model, 'title'); ?>
    <?= Form::activeTextInput($model, 'title', ['class' => 'form-control', 'placeholder' => 'Название темы']); ?>
    <?= Form::activeError($model, 'title') ?>
</div>
<div class="form-group">
    <input type="submit" value="<?= $model->isNewRecord ? 'Добавить' : 'Сохранить' ?>" class="btn btn-primary">
</div>
<?= Form::end(); ?>

