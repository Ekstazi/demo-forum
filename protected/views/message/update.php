<?php
/** @var $this \app\components\View */
/** @var $model \app\models\Message */
/** @var $thread \app\models\Thread */
$this->setTitle('Редактирование сообщения в теме: ' . $thread->title);
?>
<div>
    <h3>Редактирование сообщения в теме: <?= $thread->title ?></h3>
    <?= $this->render('_form', ['model' => $model]); ?>
</div>