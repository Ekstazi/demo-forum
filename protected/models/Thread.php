<?php

namespace app\models;


use app\components\App;
use app\components\db\Entity;

class Thread extends Entity
{
    public $id;

    public $owner_id;

    public $title;

    public $created_at;

    protected function checkValid()
    {
        if (mb_strlen($this->title) < 5) {
            $this->addError('title', 'Слишком короткое название темы');
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

    /**
     * @return User
     * @throws \Exception
     */
    public function getOwner()
    {
        return App::instance()->getDb()->getRepository('user')->findByPk($this->owner_id);
    }
    
    public function getMessages()
    {
        return App::instance()->getDb()->getRepository('message')->findAllByThread($this);
    }

}