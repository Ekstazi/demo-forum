<?php
/** @var $this \app\components\View */
/** @var $model \app\models\Thread */
$this->setTitle('Редактирование темы');
?>
<div>
    <h3>Редактирование темы</h3>
    <?=$this->render('_form', ['model' => $model]);?>
</div>