<?php

class m05_messages extends \app\components\db\Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE messages
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    message text,
    created_at INT,
    owner_id INT,
    thread_id INT,
    CONSTRAINT messages_thread_id_fk FOREIGN KEY (thread_id) REFERENCES threads (id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT messages_users_id_fk FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
)
SQL;
        $this->connection->createQuery($sql)->execute();
    }

    public function down()
    {
        $this->connection->createQuery('drop table `messages`')->execute();
    }


}