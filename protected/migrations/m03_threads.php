<?php

class m03_threads extends \app\components\db\Migration
{
    public function up()
    {
        $sql = <<<SQL
CREATE TABLE threads
(
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    created_at INT,
    owner_id INT,
    CONSTRAINT threads_users_id_fk FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
)
SQL;
        $this->connection->createQuery($sql)->execute();
    }

    public function down()
    {
        $this->connection->createQuery('drop table `threads`')->execute();
    }


}