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
        
        public function createAlbum(string $owner_id,string $album_name, array $param = null)
        {
            $table_name = 'album';
            
            $model = new Model();
            /* \R::ext('xdispense', function( $type ){
                return \R::getRedBean()->dispense( $type );
            }); */
            $album = \R::dispense( $table_name );
            $album->album_name = $album_name;            
            $album->owner_id = $owner_id;
            if($param != null){
                foreach($param as $key=>$val){
                    $album->$key = $val;
                }
            }

            return \R::store($album);
        }

        public function updateAlbum($id, $album_name,$content = null)
        {
            $album = \R::load('album', $id);
            $album->album_name = $album_name;
            if($content != null) $album->content = $content;
            $result = \R::store($album);
            return $result;
        }

        public function readAlbum($id)
        {
            $model = new Model();
            $albums = \R::findAll('album',"owner_id = ?",[$id]);
            //Application::dump($albums);
            if($albums != null){
            
                return $albums;
            }else{
                return null;
            }
            
        }

        public function deleteAlbum($id, $album_name)
        {
            $album = \R::load('album_photo', $id);
            \R::trash($album);
        }
    }