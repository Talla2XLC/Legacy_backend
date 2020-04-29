<?php

    namespace App;

    use Core\Application;
    use Core\Model;
    use Core\Db;
    use \RedBeanPHP\RedException;


    class Album
    {
        public $id;
        public $album_name;
        public $year = null;
        public $description = null;
        public $account_id;
        //public $date_time = date("Y-m-d H:i:s");
        
        public function createAlbum($album_name, $year, $description, $account_id, $data_time)
        {
            $album = R::dispense('album_photo');
            $album->album_name = $album_name;
            $album->year = $year;
            $album->dascription = $description;
            $album->owner_id = $account_id;
            //$album->date_time = $date_time;

            R::store($album);
        }

        public function updateAlbum($id, $album_name)
        {
            $album = R::load('album_photo', $id);
            $album->album_name = $album_name;
            R::store($album);
        }

        public function readAlbum()
        {
            $albums = R::loadAll('album_photo');
            foreach($albums as $album){
                echo $album->album_name.'<br>';
            }
        }

        public function deleteAlbum($id, $album_name)
        {
            $album = R::load('album_photo', $id);
            R::trash($album);
        }
    }