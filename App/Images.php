<?php

namespace App;

use Core\S3Libs;
use App\Db\DbPictures;
use Core\Application;
use Core\JWT;
use Core\Model;

class Images
{
    public function getImages()
    {
        //header('application/json');
        $data = json_decode(file_get_contents("php://input"));
        $jwt = new JWT();
        $id = $jwt->checkToken();

        if ($id != 0) {
            $dbPictures = new DbPictures();
            $id_album = $data->id_album;
            $imgs =  $dbPictures->select($id_album);
            //print_r($imgs);
            $s3Libs = new S3Libs();
            if ($imgs != null) {
                foreach ($imgs as $key=>$img) {
                    $imgs[$key]['urls'] = $s3Libs->getURL($img['content_url'], $id);
                }
                
                $arr = ['content' => $imgs, 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => '', 'result' => true];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Не верный токен', 'result' => false];
            echo json_encode($arr);
        }
    }
    public function upload()
    {

        $jwt = new JWT();
        $id = $jwt->checkToken();
        //$id = 82;
        if ($id != 0) {
            //$data = json_decode(file_get_contents("php://input"));
            
            $id_album = $_POST['id_album'];
            $result = $this->upladFile($id, $id_album);
        }


        //$dbPictures->insert($id,$idAlbum,$url);
        if (is_array($result)) {
            //$i = 0;
            //print_r($result);
            echo json_encode($result);
        }
        //print_r($arr);
    }
    protected function upladFile($id_user, $id_album)
    {
        //Application::dump($_SERVER);
        //Application::dump($_POST);
        //Application::dump($_FILES);
        //print_r($_FILES);
        $RecFace = new RecognitionFace();
        $dbPictures = new DbPictures();
        if (!empty($_FILES)) {
            $images = $_FILES['images']['name'];
            //print_r($images);
            //print_r($images);
            $n = 0;
            if (!empty($images)) {
                
                if(is_array($images)){
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
                                //$id = '10';
    
                                $uploadResult = $s3Libs->uploadCloud($image, $imageDir, $id_user);
                                if ($uploadResult == true) {
                                    $id_photo = $dbPictures->insert($id_user, $id_album, $image);
                                    $result['result'][$n]  = true;
                                    $result['error'][$n] = '';
                                    $result['img'][$n] = $nImage;
                                } else {
                                    $result['result'][$n] = false;
                                    $result['error'][$n] = 2;
                                    $result['img'][$n] = $nImage;
                                }
                                
                                $regResult = $RecFace->recognizePersone($id_user,$imageDir,$id_photo);
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
                }else{
                    $nImage = $images;
                    $image_sh = sha1($images);
                        $img = '';
                        for ($i = 0; $i < 15; $i++) {
                            $math = rand(0, 39);
                            $img .= $image_sh[$math];
                        }
                        $image = $img . $images;
                        $type = $_FILES['images']['type'];
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
                        $tmp = $_FILES['images']['tmp_name'];
                        $imageDir = 'temp/' . $image;
                        if ($type) {
                            move_uploaded_file($tmp, $imageDir);
                            if (exif_imagetype($imageDir) == IMAGETYPE_JPEG) {
                                $s3Libs = new S3Libs();
                                //$id = '10';
    
                                $uploadResult = $s3Libs->uploadCloud($image, $imageDir, $id_user);
                                
                                
                                $regResult = $RecFace->recognizePersone($imageDir,$image);
                                if ($uploadResult == true) {
                                    $dbPictures->insert($id_user, $id_album, $image);
                                    $result['result']  = true;
                                    $result['error'] = '';
                                    $result['img'] = $nImage;
                                    unlink($imageDir);
                                } else {
                                    $result['result'] = false;
                                    $result['error'] = 2;
                                    $result['img'] = $nImage;
                                }
                                unlink($imageDir);
                            } else {
                                unlink($imageDir);
                                $result['result'] = false;
                                $result['error'] = 1;
                                $result['img'] = $nImage;
                            }
                        } else {
                            unlink($imageDir);
                            $result['result'] = false;
                            $result['error'] = 1;
                            $result['img'] = $nImage;
                        }
                }
                
                return $result;
            }
            return false;
        }else{
            return $result = ['error'=>'Не тот тип данных','result'=>false];
        }
    }

    public function delete($id_photo = null)
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        new Model();
        if($id_photo == null){
            $data = json_decode(file_get_contents("php://input"));
            $result = \R::exec("DELETE FROM unit_photo WHERE id = {$data->id_photo}");
        }else{
            $result = \R::exec("DELETE FROM unit_photo WHERE id = {$id_photo}");
        }
        
    }
}
