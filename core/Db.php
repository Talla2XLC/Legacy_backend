<?php

namespace Core;
//class_alias('\RedBeanPHP\R', '\R');


class Db extends Application
{
    use TSingletone;

    private $pdo;

    protected function __construct()
    {
        $config  = new Configuration('config');
        $config->addConfig('config.yaml');
        $config_db = $config->get('config.prod.configdb');
        class_alias('\RedBeanPHP\R', '\R');
        //Application::dump($config_db);
        \R::setup(
            "pgsql:host={$config_db['host']};dbname={$config_db['dbname']}",
            "{$config_db['user']}",
            "{$config_db['password']}"
        );
        \R::freeze(TRUE);
        
    }
}