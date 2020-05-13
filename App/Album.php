<?php

namespace App;

use App\Db\DbAlbum;
use Core\JWT;

class Album
{
    public function create()
    {
        
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $jwt = new JWT();
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $dt = $jwt->JWT_decode($token);
            if ($dt != null) {
                list($id, $time) = explode('.', $dt);
            }
        }
        //echo 'test';
        
        if(isset($id) && !empty($id)){
            $data = file_get_contents(json_decode("php://input"));
            if(isset($data['album_name']) && !empty($data['album_name'])){
                $dbAlbum = new DbAlbum();
                if(isset($data->description) && !empty($data->description)){
                    $desc = $data->decscription;
                }else{
                    $desc = null;
                }
                $id = $dbAlbum->createAlbum($id,$data['album_name'],['decription'=>$desc]);
                $arr = ['error'=>'','result'=>true];
                echo json_encode($arr);
            }else{
                $arr = ['error'=>'Пустые поле название Альбома'];
                echo json_encode($arr);
            }
        }else{
            $arr = ['error'=>'','result'=>false];
            echo json_encode($arr);
        }
        
    }

    public function getAlbum()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $jwt = new JWT();
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $dt = $jwt->JWT_decode($token);
            if ($dt != null) {
                list($id, $time) = explode('.', $dt);
            }else{
                $arr = ['error'=>'Токе не дествителен','result'=>false];
                echo json_encode($arr);
                exit;
            }
        }
        if(isset($id) && !empty($id)){
            //$data = file_get_contents(json_decode("php://input"));
            $dbAlbum = new DbAlbum();
            
            if($arr != null){
                $arr = $dbAlbum->readAlbum($id);
                $arr = ['content'=>$arr,'result'=>true];
                echo json_encode($arr);
            }else{
                $arr = ['error'=>'Нет записей','result'=>false];
                echo json_encode($arr);
            }
            
        }
    }

    public function updateAlbum()
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $jwt = new JWT();
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $dt = $jwt->JWT_decode($token);
            if ($dt != null) {
                list($id, $time) = explode('.', $dt);
            }else{
                $arr = ['error'=>'Токе не дествителен','result'=>false];
                echo json_encode($arr);
                exit;
            }
        }
        if(isset($id) && !empty($id)){
            $dbAlbum = new DbAlbum();
            $data = file_get_contents(json_decode("php://input"));
            if(isset($data->album_name) && !empty($data->album_name)) $album_name = $data->album_name;

            if(isset($data->content) && !empty($data->content)) {$content = $data->content;} else{ $content = null;};

            
            $result = $dbAlbum->updateAlbum($id,$album_name,$content);
            $arr = ['error'=>'','result'=>$result];
            echo json_encode($arr);
        }else{
            $arr = ['error'=>'Токе не дествителен','result'=>false];
            echo json_encode($arr);
        }   
    }
}