<?php
/** @var $this \app\components\View */
/** @var $model \app\models\Message */
/** @var $thread \app\models\Thread */

$this->setTitle('Добавление сообщения в тему: ' . $thread->title);
?>
<div>
    <h3>Добавление сообщения в тему: <?= $thread->title ?></h3>
        <?= $this->render('_form', ['model' => $model]); ?>
</div>