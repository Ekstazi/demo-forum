<?php
use app\components\helpers\Html;

/** @var $this \app\components\View */
/** @var $threads \app\models\Thread[] */
$this->setTitle('threads');
?>
<h2>Active threads</h2>
<?php if (!\app\components\App::instance()->getUser()->isGuest()) { ?>
    <?= Html::a('Добавить тему', ['thread/create']); ?>
<?php } ?>
<?php foreach ($threads as $thread) { ?>
    <div class="blog-post">
        <h2 class="blog-post-title"><?= Html::a($thread->title, ['thread/view', 'thread' => $thread->id]); ?></h2>
        <p class="blog-post-meta"><?= date('d-m-Y h:i:s', $thread->created_at) ?> by <a
                href="#"><?= $thread->getOwner()->email; ?></a>
        </p>
        <p>
            <?php if ($thread->canUpdate()) { ?>
                <?= Html::a('Изменить тему', ['thread/update', 'thread' => $thread->id]); ?>
            <?php } ?>
            <?php if ($thread->canDelete()) { ?>
                <?= Html::a('Удалить тему', ['thread/delete', 'thread' => $thread->id]); ?>
            <?php } ?>
        </p>
    </div><!-- /.blog-post -->

<?php } ?>
