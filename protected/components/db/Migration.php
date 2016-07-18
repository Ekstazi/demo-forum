<?php

namespace app\components\db;


class Migration
{
    /**
     * @var Db
     */
    protected $connection;
    
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function up()
    {
        
    }
    
    public function down()
    {
        
    }
}