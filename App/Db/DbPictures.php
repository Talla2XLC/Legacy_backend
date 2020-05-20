<?php

namespace App\Db;

use Core\Model;
use \RedBeanPHP\RedException;

class DbPictures
{
    
    public function insert($idAccount,$idAlbum,$url, bool $reference = false)
    {
        $model = new Model();
        $photo = 'unit_photo';
        $relation_album = 'relation_album_photo';
        try{
            \R::ext('xdispense', function( $type ){
                return \R::getRedBean()->dispense( $type );
            });
            $table_photo = \R::xdispense( $photo );
            $table_photo->owner_id = $idAccount;
            $table_photo->content_url = $url;
            if($reference == true){
                $table_photo->reference = true;
            }
            $id_photo = \R::store($table_photo);
            echo $id_photo."\n";

            \R::exec('INSERT INTO "relation_album_photo" ("album_id","photo_id") VALUES ('.$idAlbum.','.$id_photo.')');
            /*
            $r_album = \R::xdispense( $relation_album );
            $r_album->album_id = $idAlbum;
            $r_album->photo_id = $id_photo;
            \R::store($r_album);
            */
            \R::close();
            return true;
        }catch(RedException $e){
            return $e;
        }
        
        
    }
    public function select($idAlbum)
    {
        new Model();
        //$result = \R::exec('SELECT * FROM "relation_album_photo" WHERE album_id = '.$idAlbum);
        $results = \R::getAll('SELECT * FROM relation_album_photo WHERE "album_id" = '.$idAlbum);
        //print_r($results);
        if(!empty($results)){
            $i = 0;
            foreach($results as $result){
                //print_r($result);
                $photos = \R::loadAll('unit_photo',$result['photo_id']);
                print_r($photos);
                $arr_photos[$i]['id'] = $photos['id'];
                $arr_photos[$i]['content_url'] = $photos['content_url'];
                $arr_photos[$i]['coordinates'] = $photos['coordinates'];
                $arr_photos[$i]['reference'] = $photos['reference'];
                $arr_photos[$i]['photo_name'] = $photos['photo_name'];
                $arr_photos[$i]['author'] = $photos['author'];
                $arr_photos[$i]['photo_name'] = $photos['photo_name'];
                $i++;
            }
            return $arr_photos;
        }else{
            return null;
        }
        
        
    }
}