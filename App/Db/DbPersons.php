<?php

namespace App\Db;

use Core\Application;
use Core\Model;
use Core\Db;
use \RedBeanPHP\RedException;


class DbPersons
{
    public function create(int $creator_id, array $all = null)
    {
        $table_name = 'person';

        $model = new Model();
        \R::ext('xdispense', function( $type ){
            return \R::getRedBean()->dispense( $type );
        });

        $person = \R::xdispense($table_name);
        $person->creator_id = $creator_id;
        //$history->story_name = $story_name;
        //$history->content = $content;
        //print_r($all);
        //$history->date = (string) $all['date'];
        //$history->city = (string) $all['city'];
        //$history->author = (string) $all['author'];
        //$history->tags = $all['tags'];    
        foreach($all as $key => $val){
            $person->$key = $val;
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

    public function updatePerson($id, $story_name,$content = null,array $all = null)
    {
        new Model();
        $story = \R::load('person',$id);
        $story->story_name = $story_name;

        if($content != null) $story->content = $content;
        if($all != null && is_array($all)){
            foreach($all as $key => $val){
                $story->$key = $val;
                \R::store($story);
            }
        }
        $result = \R::store($story);
        return $result;
    }

    public function delete($id)
    {
        new Model();
        return $result = \R::exec("DELETE FROM unit_story WHERE id = {$id}");
        
    }
}