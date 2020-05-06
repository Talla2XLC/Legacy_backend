<?php

namespace Core;


class Model extends Application
{
    
    public $pdo;

    public function __construct() {
        $this->pdo = Db::instance();
    }
}
