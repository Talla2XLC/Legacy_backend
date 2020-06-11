<?php

    namespace App\Db;

    use Core\Application;
    use Core\Model;
    use Core\Db;
    use \RedBeanPHP\RedException;
    use Core\S3Libs;

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
            if($content != null) $album->description = $content;
            $result = \R::store($album);
            return $result;
        }

        public function readAlbum($id)
        {
            $model = new Model();
            //$albums = \R::findAll('album',"owner_id = ?",[$id]);
            $albums = $model->getTable('album',$id);
            //$albums = $albums[0];
            //print_r($albums);
            //print_r($albums);
            //$array = \R::exportAll($albums);
            //Application::dump($albums);
            //$albums = (array) $albums;
            //print_r($albums);
            $s3Libs = new S3Libs();
            $i = 0;
            
            foreach($albums as $album){
               //echo $album['id'].'--';
                //echo '-----albumID-----';
                //echo $album['id'];
                //echo $album['id'];
            $photoIds = \R::getAll("SELECT photo_id FROM relation_album_photo WHERE album_id = {$album['id']}");
            //print_r($photoIds);
                //print_r($photoIds);
                //echo '----photo-----';
                
                $n = 0;
                if(!empty($photoIds)){
                    foreach($photoIds as $photoID){
                        //echo $photoID['photo_id'].'#'."\n";
                        $photo = \R::findOne('unit_photo','id = ?',[$photoID['photo_id']]);
                        //$photo$s3Libs->getURL($photo['content_url'], $id);
                        $arrPhoto = \R::exportAll($photo);
                        $arrPhoto = $arrPhoto[0];
                        //print_r($arrPhoto);
                        $arrPhoto['content_url'] = $s3Libs->getURL($photo['content_url'], $id);
                        $photos[$n] = $arrPhoto;
                        $n++;
                    }
                }
                
                
                //echo '-----endphoto----';
                if(!empty($photos)){
                    $albums[$i]['photo'] = $photos;
                    unset($photos);
                }
                
                $i++;
            }
            if($albums != null){
            
                return $albums;
            }else{
                return null;
            }
            
        }

        public function deleteAlbum($id)
        {
            $album = \R::load('album_photo', $id);
            \R::trash($album);
        }
    }