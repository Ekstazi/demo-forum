<?php
/** @var $this \app\components\View */
/** @var $model \app\models\Thread */
$this->setTitle('Добавление темы');
?>
<div>
    <h3>Добавление темы</h3>
    <?=$this->render('_form', ['model' => $model]);?>
</div>