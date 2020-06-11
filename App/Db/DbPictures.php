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
            $account = \R::findOne('account','id = ?',[$idAccount]);
            $first_name = $account->first_name;
            $last_name = $account->last_name;
            $author = $first_name.' '.$last_name;
            \R::ext('xdispense', function( $type ){
                return \R::getRedBean()->dispense( $type );
            });
            $table_photo = \R::xdispense( $photo );
            $table_photo->owner_id = $idAccount;
            $table_photo->content_url = $url;
            $table_photo->author = $author;
            if($reference == true){
                $table_photo->reference = true;
            }
            $id_photo = \R::store($table_photo);
            //echo $id_photo."\n";

            \R::exec('INSERT INTO "relation_album_photo" ("album_id","photo_id") VALUES ('.$idAlbum.','.$id_photo.')');
            /*
            $r_album = \R::xdispense( $relation_album );
            $r_album->album_id = $idAlbum;
            $r_album->photo_id = $id_photo;
            \R::store($r_album);
            */
            \R::close();
            return $id_photo;
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
                $id_photo = $result['photo_id'];
                //echo $id_photo;
                $photos = \R::find('unit_photo',' id = ?',[$id_photo]);
               // print_r($photos);
                //echo  $photos->content_url;
                foreach($photos as $photo){
                    $arr_photos[$i]['id'] = $photo->id;
                $arr_photos[$i]['content_url'] = $photo->content_url;
                $arr_photos[$i]['coordinates'] = $photo['coordinates'];
                $arr_photos[$i]['reference'] = $photo['reference'];
                $arr_photos[$i]['photo_name'] = $photo['photo_name'];
                $arr_photos[$i]['author'] = $photo['author'];
                }
                
                //$arr_photos[$i]['photo_name'] = $photos['photo_name'];
                $i++;
            }
            return $arr_photos;
        }else{
            return null;
        }
        
        
    }
    public function updateImage($data, $id_photo){
        $model = new Model();
        //$result = \R::load('unit_photo',$id_photo);
        $sql = 'UPDATE unit_photo SET ';
        $i = 0;
        foreach($data as $key => $dt)
        {
            if($key != 'id') $sql .= "$key = $dt, ";
            
            $i++;
            
        }
        $sql .= "WHERE id = {$id_photo}";

        \R::exec($sql);

        return true;
    }
}