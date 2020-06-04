<?php

namespace App;

use App\Db\DbAlbum;
use Core\JWT;
use Core\Model;
use Core\S3Libs;

class Album
{
    public function create()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();

        if (!empty($id) && $id != 0) {
            $data = json_decode(file_get_contents("php://input"));
            //print_r($data);
            if (isset($data->album_name) && !empty($data->album_name)) {
                $dbAlbum = new DbAlbum();
                if (isset($data->description) && !empty($data->description)) {
                    $desc = $data->description;
                } else {
                    $desc = null;
                }
                $id = $dbAlbum->createAlbum($id, $data->album_name, ['description' => $desc]);
                $arr = ['error' => '', 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Пустые поле название Альбома'];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => '', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function getAlbum()
    {
        //header('HTTP/1.1 404 Not found');
        //echo 'testGet';
        //header('application/json');
        $jwt = new JWT();
        $id = $jwt->checkToken();
        //$id = 82;
        if ($id != 0 && !empty($id)) {
            //$data = file_get_contents(json_decode("php://input"));
            $dbAlbum = new DbAlbum();

            $arr = $dbAlbum->readAlbum($id);
            //print_r($arr);
            if ($arr != null) {
                
                $arr = ['content' => $arr, 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Нет записей', 'result' => false];
                echo json_encode($arr);
            }
        }else{
            $arr = ['error' => 'Токе не дествителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function updateAlbum()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if ($id == 0) {
            $jwt = new JWT();
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $dt = $jwt->JWT_decode($token);
            if ($dt != null) {
                list($id, $time) = explode('.', $dt);
            } else {
                $arr = ['error' => 'Токен не действителен', 'result' => false];
                echo json_encode($arr);
                exit;
            }
        }
        if (isset($id) && !empty($id)) {
            $dbAlbum = new DbAlbum();
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->album_name) && !empty($data->album_name)) $album_name = $data->album_name;

            if (isset($data->content) && !empty($data->content)) {
                $content = $data->content;
            } else {
                $content = null;
            };


            $result = $dbAlbum->updateAlbum($id, $album_name, $content);
            $arr = ['error' => '', 'result' => $result];
            echo json_encode($arr);
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function delete(){
        $data = json_decode(file_get_contents("php://input"));
        if($data){
            $id_album = $data->id_album;
            //print_r($data);
        }
        if(isset($_POST['id_album'])){
            $id_album = $_POST['id_album'];
        }
        //$dbAlbum = new DbAlbum();
        $model = new Model();
        $s3 = new S3Libs();
        $jwt = new JWT();
        $id = $jwt->checkToken();
        //$photoIds['photo_id'] = 'sdsdsd';
        //$photoIds = \R::getAll("SELECT photo_id FROM relation_album_photo WHERE album_id = {$id_album}");
        //foreach($photoIds as $id_photo){
        //$photo = \R::getAll("SELECT content_url FROM unit_photo WHERE id = {$id_photo['photo_id']}");
        //$photo['content_url'];
        
          //$resDel = $s3->DeleteObject($id_photo['content_url'],$id);
          //$resPhoto =   $model->delete('unit_photo',$id_photo['photo_id']);
        //}
        echo "test";
        //$resAlbum = $model->delete('album',$id_album);
        /*
        if($resAlbum){
            $arr = ['error' => '', 'result' => true];
            echo json_encode($arr);
        }else{
            $arr = ['error' => 'Не удалось удалить', 'result' => true];
            echo json_encode($arr);
        }
        */
        
    }
}
