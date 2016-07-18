<?php

namespace app\repositories;


use app\components\db\Repository;

class ThreadRepository extends Repository
{
    public function tableName()
    {
        return 'threads';
    }

}