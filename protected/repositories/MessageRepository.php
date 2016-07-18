<?php

namespace app\repositories;


use app\components\db\Repository;
use app\models\Thread;

class MessageRepository extends Repository
{
    public function tableName()
    {
        return 'messages';
    }

    public function findAllByThread(Thread $thread)
    {
        return $this->findAllBySql('select * from messages where thread_id =:id', [
            ':id' => $thread->id
        ]);
    }
}