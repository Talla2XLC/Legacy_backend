<?php

namespace Core;


class Model extends Application
{
    
    public $pdo;
    private $pd;
    public function __construct() {
        $this->pdo = Db::instance();
    }
    private function getPDO(){
        $host = '193.168.3.129';
        $db   = 'dev_memory_lane';
        $user = 'postgres';
        $pass = 'W}J}W`PH&n{>x2nf>%[+^Ex=JAX8N^Et~Er';
        $charset = 'utf8';

        $dsn = "pgsql:host=$host;dbname=$db";
        $opt = [
        \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $this->pd = new \PDO($dsn, $user, $pass);
    }
    public function delete($table,$id)
    {
        //new Model();
        //return $result = \R::exec("DELETE FROM person WHERE id = $id");
        $this->getPDO();
        if($this->pd){
            return $this->pd->query("DELETE FROM $table WHERE id = $id");
        }else{
            return false;
        }
        //return $pdo->query("DELETE FROM person WHERE id = $id");
        
    }
    public function getTable($table_name,$id){
        $this->getPDO();
        $sql = "SELECT * FROM {$table_name} WHERE owner_id = {$id}";
        $sth = $this->pd->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll();
        return $result;
        //return $this->pd->query("SELECT * FROM {$table_name} WHERE id = {$id}");
    } 
}
