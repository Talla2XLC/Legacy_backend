<?php

namespace Core;
//class_alias('\RedBeanPHP\R', '\R');


class Db extends Application
{
    use TSingletone;

    protected function __construct()
    {
        $configs_db = require_once 'config/config_db.php';
        $config = $configs_db['dev'];
        class_alias('\RedBeanPHP\R', '\R');
        

        \R::setup(
            "pgsql:host={$config['host']};dbname={$config['db_name']}",
            "{$config['user_db']}",
            "{$config['pass_db']}"
        );
        
    }
}