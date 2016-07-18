<?php

namespace app\models;


use app\components\App;
use app\components\Model;
use app\repositories\UserRepository;

class LoginForm extends Model
{
    public $email;

    public $password;

    public $userModel;

    public $rememberMe = false;

    protected function checkValid()
    {
        /** @var UserRepository $repo */
        $repo = App::instance()->getDb()->getRepository('user');
        /** @var User $user */
        $user = $repo->findByEmail($this->email);
        if (!$user) {
            $this->addError('email', 'Unknown user email');
            return false;
        }

        $isValidPassword = App::instance()->getHasher()->verify($this->password, $user->password_hash);
        if (!$isValidPassword) {
            $this->addError('password', 'Invalid password');
            return false;
        }

        if (!$user->active) {
            $this->addError('email', 'Вам необходимо сперва активировать аккаунт');
            return false;
        }

        $this->userModel = $user;
        return true;
    }

    public function attributeLabels()
    {
        return [
            'email'    => 'email',
            'password' => 'Пароль',
        ];
    }


}