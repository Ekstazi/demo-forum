<?php

class m01_users extends \app\components\db\Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE users
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255),
    password_hash VARCHAR(255),
    confirm_key VARCHAR(255), -- used for conform registration
    identity_key VARCHAR(255), -- used for autologin    
    active int(1) default 0, 
    registered_at INT
);
CREATE UNIQUE INDEX users_email_uindex ON users (email);
SQL;
        $this->connection->createQuery($sql)->execute();
    }

    public function down()
    {
        $this->connection->createQuery('drop table `users`')->execute();
    }


}