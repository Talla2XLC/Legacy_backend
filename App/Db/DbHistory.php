<?php

    namespace App\Db;

    use Core\Application;
    use Core\Model;
    use Core\Db;
    use \RedBeanPHP\RedException;


    class DbHistory
{
        public function createHistory(string $account_id, string $story_name, string $content,array $all = null)
        {
            $table_name = 'unit_story';

            $model = new Model();
            \R::ext('xdispense', function( $type ){
                return \R::getRedBean()->dispense( $type );
            });

            $history = \R::xdispense($table_name);
            $history->owner_id = $account_id;
            $history->story_name = $story_name;
            $history->content = $content;
            //print_r($all);
            $history->date = (string) $all['date'];
            $history->city = (string) $all['city'];
            $history->author = (string) $all['author'];
            //$history->tags = $all['tags'];            
            
            \R::store($history);
            $result = true;
        }

        public function readHistorys()
        {
            new Model();
            //return $result = \R::getAll('SELECT * FROM unit_story WHERE owner_id = ?',[$id_owner]);
            return $result = \R::findAll('unit_story');
        }

        public function readHistory(int $id)
        {
            new Model();
            
            $result = \R::findAll('unit_story','owner_id = ?',[$id]);
            $array = \R::exportAll($result);
            return $array;
        }

        public function updateHistory($id, $story_name,$content = null,array $all = null)
        {
            new Model();
            $story = \R::load('unit_story',$id);
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

        public function deleteHistory($id)
        {
            new Model();
            $history = \R::exec('DELETE FROM "unit_story" WHERE id = ?, [$id]');
            $result = true;
        }
    }