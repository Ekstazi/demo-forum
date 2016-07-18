<?php

class m02_users_demo extends \app\components\db\Migration
{
    public function up()
    {
        $this->addUser('test@mail.ru', 'qazwsxedc');
        $this->addUser('test2@mail.ru', 'qazwsxedc');
    }

    protected function addUser($email, $password)
    {
        $repo = $this->connection->getRepository('user');
        /** @var \app\models\User $user1 */
        $user1 = $repo->create();
        $user1->email = $email;
        $user1->setPassword($password);
        $user1->active = 1;
        $user1->save();
    }

    public function down()
    {
        $this->connection->createQuery('delete from `users`')->execute();
    }


}