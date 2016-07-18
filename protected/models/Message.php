<?php

namespace app\models;


use app\components\App;
use app\components\db\Entity;

class Message extends Entity
{
    public $id;

    public $owner_id;

    public $message;

    public $created_at;

    public $thread_id;

    protected function checkValid()
    {
        if (mb_strlen($this->message) < 5) {
            $this->addError('message', 'Слишком короткое сообщение');
            return false;
        }

        if (!$this->thread_id) {
            $this->addError('message', 'Такой темы не существует');
            return false;
        }

        $thread = App::instance()->getDb()->getRepository('thread')->findByPk($this->thread_id);
        if (!$thread) {
            $this->addError('message', 'Такой темы не существует');
            return false;
        }

        return true;
    }

    public function beforeSave()
    {
        if (!$this->isNewRecord) {
            return true;
        }
        $this->created_at = time();
        if (!isset($this->owner_id)) {
            $this->owner_id = App::instance()->getUser()->getIdentity()->id;
        }
        return true;
    }

    public function canDelete()
    {
        return App::instance()->getUser()->getId() == $this->owner_id;
    }

    public function canUpdate()
    {
        return App::instance()->getUser()->getId() == $this->owner_id;
    }

    public function getOwner()
    {
        return App::instance()->getDb()->getRepository('user')->findByPk($this->owner_id);
    }

    public function getThread()
    {
        return App::instance()->getDb()->getRepository('thread')->findByPk($this->thread_id);
    }

}