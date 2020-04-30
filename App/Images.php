<?php

namespace App;

use Core\S3Libs;
use App\Db\DbPictures;

class Images
{
    public function getImages()
    {
        $dbPictures = new DbPictures();

        $imgs =  $dbPictures->select('12');
        $s3Libs = new S3Libs();
        foreach($imgs as $img){
            $urls[] = $s3Libs->getURL($img,'9');
        }
        //header('Access-Control-Allow-Origin: *');
        //header("Content-type: application/json; charset=utf-8");
        echo json_encode($urls);
        
    }
    public function upload()
    {
        $result = $this->upladFile();
        
        //$dbPictures->insert($id,$idAlbum,$url);
        if (is_array($result)) {
            //$i = 0;
            //print_r($result);
            echo json_encode($result);
           
        }
        //print_r($arr);
    }
    protected function upladFile()
    {
        $dbPictures = new DbPictures();
        if (!empty($_FILES)) {
            $images = $_FILES['images']['name'];
            //print_r($images);
            $n = 0;
            foreach ($images as $image) {
                $nImage = $image;
                $image_sh = sha1($image);
                $img = '';
                for ($i = 0; $i < 15; $i++) {
                    $math = rand(0, 39);
                    $img .= $image_sh[$math];
                }
                $image = $img . $image;
                $type = $_FILES['images']['type'][$n];
                switch ($type) {
                    case 'image/png':
                        $type = true;
                        break;
                    case 'image/jpeg':
                        $type = true;
                        break;
                    default:
                        $type = false;
                }
                $tmp = $_FILES['images']['tmp_name'][$n];
                $imageDir = 'temp/' . $image;
                if ($type) {
                    move_uploaded_file($tmp, $imageDir);
                    if (exif_imagetype($imageDir) == IMAGETYPE_JPEG) {
                        $s3Libs = new S3Libs();
                        $id = '9';

                        $uploadResult = $s3Libs->uploadCloud($image, $imageDir, $id);
                        if($uploadResult == true){
                            $dbPictures->insert('9','12',$image);
                            $result['result'][$n]  = true;
                            $result['error'][$n] = '';
                            $result['img'][$n] = $nImage;
                            
                        }else{
                            $result['result'][$n] = false;
                            $result['error'][$n] = 2;
                            $result['img'][$n] = $nImage;
                        }
                        unlink($imageDir);
                        
                    } else {
                        unlink($imageDir);
                        $result['result'][$n] = false;
                        $result['error'][$n] = 1;
                        $result['img'][$n] = $nImage;
                    }
                } else {
                    unlink($imageDir);
                    $result['result'][$n] = false;
                    $result['error'][$n] = 1;
                    $result['img'][$n] = $nImage;

                }

                $n++;
            }
            return $result;
        }
        return false;
    }
}