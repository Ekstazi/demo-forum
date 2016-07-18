<?php
namespace app\models;

use app\components\App;
use app\components\db\Entity;
use app\repositories\UserRepository;

class User extends Entity
{
    public $id;

    public $email;

    public $password_hash;

    public $confirm_key;

    public $registered_at;

    public $identity_key;

    public $active = 0;

    protected $password;

    public function setPassword($password)
    {
        $hash = App::instance()->getHasher()->hash($password);
        $this->password_hash = $hash;
        $this->password = $password;
    }

    public function getPassword()
    {
        return $this->password;
    }

    protected function checkValid()
    {
        if (!$this->isNewRecord) {
            return true;
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Неверный email адрес');
            return false;
        }
        /** @var UserRepository $r */
        $r = $this->repository;
        $model = $r->findByEmail($this->email);
        if ($model) {
            $this->addError('email', 'Email адрес уже занят');
            return false;
        }
        return true;
    }

    public function beforeSave()
    {
        if ($this->scenario !== self::SCENARIO_INSERT) {
            return true;
        }
        $hasher = App::instance()->getHasher();
        $password = $hasher->getRandom();
        
        if (!isset($this->password)) {
            $this->setPassword($password);
        }
        $this->email = strtolower($this->email);
        $this->confirm_key = $hasher->getRandom();
        $this->identity_key = $hasher->getRandom();
        $this->registered_at = time();
        return true;
    }


    public function safeAttributes()
    {
        return ['email'];
    }


}