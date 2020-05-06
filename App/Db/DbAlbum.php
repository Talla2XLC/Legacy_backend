<?php

    namespace App\Db;

    use Core\Application;
    use Core\Model;
    use Core\Db;
    use \RedBeanPHP\RedException;


    class DbAlbum
    {
        //public $id;
        //public $album_name;
        //public $year = null;
        //public $description = null;
        //public $account_id;
        //public $date_time = date("Y-m-d H:i:s");
        
        public function createAlbum(string $account_id,string $album_name, array $param = null)
        {
            $table_name = 'album_video';
            
            $model = new Model();
            \R::ext('xdispense', function( $type ){
                return \R::getRedBean()->dispense( $type );
            });
            $album = \R::xdispense( $table_name );
            $album->album_name = $album_name;            
            $album->owner_id = $account_id;
            if($param != null){
                foreach($param as $key=>$val){
                    $album->$key = $val;
                }
            }

            return \R::store($album);
        }

        public function updateAlbum($id, $album_name)
        {
            $album = \R::load('album_photo', $id);
            $album->album_name = $album_name;
            \R::store($album);
        }

        public function readAlbum()
        {
            /*
            $albums = \R::loadAll('album_photo',9);
            foreach($albums as $album){
                echo $album->album_name.'<br>';
            }
            */
        }

        public function deleteAlbum($id, $album_name)
        {
            $album = \R::load('album_photo', $id);
            \R::trash($album);
        }
    }