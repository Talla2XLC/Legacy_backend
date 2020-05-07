<?php

namespace App\Db;

use Core\Model;

class DbAlbum
{
    public function insert($idAccount,$album_name,array $param = null)
    {
        $model = new Model();

        $album_photo = \R::dispense( 'album_photo' );
        $album_photo->album_name = $album_name;
        $album_photo->owner_id = $idAccount;
        $album_photo->date_time = 'now';
        if($param != null){
            foreach($param as $key=>$val){
                $album_photo->$key = $val;
            }
        }
        return $id = \R::store($album_photo);

    }
}