<?php

namespace App\Db;

use Core\Application;
use Core\Model;
use Core\Db;
use \RedBeanPHP\RedException;


class DbPersons
{
    public function create(int $creator_id, array $all = null,$image)
    {
        $table_name = 'person';

        $model = new Model();
        \R::ext('xdispense', function( $type ){
            return \R::getRedBean()->dispense( $type );
        });

        $person = \R::xdispense($table_name);
        $person->creator_id = $creator_id;
        $person->ico_url = $image;
            
        foreach($all as $key => $val){
            if($key == "ico_url")
            {
                $person->key = $image;
            }else{
                $person->$key = $val;
            }
            
            \R::store($person);
        }
        
        \R::store($person);
        $result = true;
    }

    public function readHistorys()
    {
        new Model();
        //return $result = \R::getAll('SELECT * FROM unit_story WHERE owner_id = ?',[$id_owner]);
        return $result = \R::findAll('unit_story');
    }

    public function readPerson(int $id)
    {
        new Model();
        
        $result = \R::findAll('person',' creator_id = ?',[$id]);
        $array = \R::exportAll($result);

        return $array;
    }

    public function updatePerson($id, $first_name,array $all = null)
    {
        new Model();
        $person = \R::load('person',$id);
        $person->first_name = $first_name;

        
        if($all != null && is_array($all)){
            foreach($all as $key => $val){
                $person->$key = $val;
                \R::store($person);
            }
        }
        $result = \R::store($person);
        return $result;
    }

    
}