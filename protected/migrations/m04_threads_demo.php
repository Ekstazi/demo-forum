<?php

class m04_threads_demo extends \app\components\db\Migration
{
    public function up()
    {
        $this->addThread(1, 'Simple theme');
        $this->addThread(2, 'Simple theme 2');
    }

    protected function addThread($userId, $title)
    {
        $repo = $this->connection->getRepository('thread');
        /** @var \app\models\Thread $thread */
        $thread = $repo->create();
        $thread->owner_id = $userId;
        $thread->title = $title;
        $thread->save();
    }

    public function down()
    {
        $this->connection->createQuery('delete from `threads`')->execute();
    }


}