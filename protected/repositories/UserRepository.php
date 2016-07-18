<?php
namespace app\repositories;

use app\components\db\Repository;

class UserRepository extends Repository
{
    public function tableName()
    {
        return 'users';
    }

    public function findByEmail($email)
    {
        return $this->findBySql('select * from users where email=:email', [
            ':email' => strtolower($email),
        ]);
    }

    public function findByConfirmKey($key)
    {
        return $this->findBySql('select * from users where confirm_key=:key', [
            ':key' => $key,
        ]);
    }

}