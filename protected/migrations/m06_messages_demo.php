<?php

class m06_messages_demo extends \app\components\db\Migration
{
    public function up()
    {
        $this->addMessage(1, 1,'Simple message');
        $this->addMessage(2, 1,'Simple message 2');
        $this->addMessage(1, 2,'Simple message 3');
        $this->addMessage(2, 2,'Simple message 4');
    }

    protected function addMessage($userId, $threadId, $text)
    {
        $repo = $this->connection->getRepository('message');
        /** @var \app\models\Message $message */
        $message = $repo->create();
        $message->owner_id = $userId;
        $message->thread_id = $threadId;
        $message->message = $text;
        $message->save();
    }

    public function down()
    {
        $this->connection->createQuery('delete from `messages`')->execute();
    }


}