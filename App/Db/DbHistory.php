<?php

    namespace App\Db;

    use Core\Application;
    use Core\Model;
    use Core\Db;
    use \RedBeanPHP\RedException;


    class DbHistory
    {
        public function createHistory(string $account_id, string $story_name, string $content)
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
            \R::store($history);
        }

        public function updateHistory(string $id, string $story_name, string $content)
        {
            $table_name = 'unit_story';
            $model = new Model();
            \R::ext('xdispense', function( $type ){
                return \R::getRedBean()->dispense( $type );
            });
            $history = \R::xdispense($table_name);

            $history = \R::load($table_name, $id);
            $history->story_name = $story_name;
            $history->content = $content;
            \R::store($history);
        }

        public function readHistorys()
        {
            
        }

        public function readHistory()
        {
            
        }

        public function deleteHistory($id)
        {

            $history = \R::load('unit_story', $id);
            \R::trash($history);
        }
    }