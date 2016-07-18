<?php
use app\components\helpers\Html;

/** @var $this \app\components\View */
/** @var $thread \app\models\Thread */
/** @var $messages \app\models\Message[] */
$this->setTitle('Сообщения темы:' . $thread->title);
?>
<h2>Сообщения темы: <?= $thread->title ?></h2>
<div>
    <?php if ($thread->canUpdate()) { ?>
        <?= Html::a('Изменить тему', ['thread/update', 'thread' => $thread->id]); ?>
    <?php } ?>
    <?php if ($thread->canDelete()) { ?>
        <?= Html::a('Удалить тему', ['thread/delete', 'thread' => $thread->id]); ?>
    <?php } ?>
</div>
<?php if (!\app\components\App::instance()->getUser()->isGuest()) { ?>
    <?= Html::a('Добавить сообщение', ['message/create', 'thread' => $thread->id]); ?>
<?php } ?>
<?php foreach ($messages as $message) { ?>
    <div class="blog-post">
        <p class="blog-post-meta"><?= date('d-m-Y h:i:s', $message->created_at) ?> by <a
                href="#"><?= $message->getOwner()->email; ?></a>
        </p>
        <div>
            <?= htmlspecialchars($message->message) ?>
        </div>
        <p>
            <?php if ($message->canUpdate()) { ?>
                <?= Html::a('Изменить сообщение', ['message/update', 'message' => $message->id]); ?>
            <?php } ?>
            <?php if ($message->canDelete()) { ?>
                <?= Html::a('Удалить сообщение', ['message/delete', 'message' => $message->id]); ?>
            <?php } ?>
        </p>
    </div><!-- /.blog-post -->

<?php } ?>
