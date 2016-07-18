<?php

namespace app\components;


use app\repositories\UserRepository;

class User extends Component
{

    protected $identity;

    public $allowAutoLogin = true;

    const IDENTITY_COOKIE = 'identity_key';

    protected function loadIdentity()
    {
        session_start();
        if (isset($_SESSION['id'])) {
            return $this->getRepository()->findByPk($_SESSION['id']);
        }

        if (!$this->allowAutoLogin) {
            return false;
        }

        return $this->loadIdentityFromCookie();
    }

    protected function loadIdentityFromCookie()
    {
        if (!isset($_COOKIE[self::IDENTITY_COOKIE]))
            return false;


        list($email, $identityKey) = explode(':', $_COOKIE[self::IDENTITY_COOKIE]);
        if (!$email || !$identityKey)
            return false;

        $hasher = App::instance()->getHasher();
        $email = $hasher->decrypt($email);
        $identityKey = $hasher->decrypt($identityKey);

        /** @var \app\models\User $model */
        $model = $this->getRepository()->findByEmail($email);
        if (!$model)
            return false;

        if (strcmp($model->identity_key, $identityKey) !== 0) {
            return false;
        }

        return $model;
    }

    public function authenticate(\app\models\User $user, $remember = false)
    {
        $this->setIdentity($user);

        if (!$remember) {
            return;
        }
        $this->saveIdentityCookie($user);
    }

    protected function saveIdentityCookie(\app\models\User $identity)
    {
        $hasher = App::instance()->getHasher();
        $email = $hasher->crypt($identity->email);
        $identityKey = $hasher->crypt($identity->identity_key);
        App::instance()->getResponse()->addCookie(self::IDENTITY_COOKIE, "{$email}:{$identityKey}", time() + 30 * 24 * 60 * 60);
    }

    /**
     * @return \app\models\User|false
     */
    public function getIdentity()
    {
        if (isset($this->identity))
            return $this->identity;

        return $this->identity = $this->loadIdentity();
    }

    public function setIdentity(\app\models\User $user)
    {
        session_start();
        $_SESSION['id'] = $user->id;
        $this->identity = $user;
    }

    /**
     * @return UserRepository
     * @throws \Exception
     */
    protected function getRepository()
    {
        return App::instance()->getDb()->getRepository('user');
    }

    public function isGuest()
    {
        return $this->getIdentity() === false;
    }

    public function logout()
    {
        $this->getIdentity();
        session_destroy();
        App::instance()->getResponse()->removeCookie(self::IDENTITY_COOKIE);
    }

    public function getId()
    {
        if ($this->isGuest())
            return false;

        return $this->getIdentity()->id;
    }

}