<?php

namespace App;

use App\Db\DbHistory;
use App\Db\DbPersons;
use Core\JWT;
use Core\S3Libs;

class Persons
{
    public function setPerson()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            //$data = json_decode(file_get_contents("php://input"));
            $data = $_POST;
            //print_r($_POST);
            //print_r($_FILES);
            //print_r($data);
            if (isset($data['first_name']) && !empty($data['first_name'])) {
                $dbPersons= new DbPersons();
                
                foreach($data as $key => $val){
                    if($key != 'story_name' || $key != 'content'){
                        $dataStorys[$key] = $val; 
                    }
                }
                if(!is_array($dataStorys))
                {
                    $dataStorys = null;
                }
                //print_r($dataStorys);
                //print_r($_FILES);
                if(isset($_FILES['ico_url'])){
                    $image = $_FILES['ico_ulr']['name'];
                    $n = 0;
                    $nImage = $image;
                    $image_sh = sha1($image);
                    $img = '';
                    for ($i = 0; $i < 15; $i++) {
                        $math = rand(0, 39);
                        $img .= $image_sh[$math];
                    }
                    $image = $img . $nImage;
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
                        $exifImage = exif_imagetype($imageDir);
                        if($exifImage == IMAGETYPE_JPEG || $exifImage == IMAGETYPE_PNG) {
                            $s3Libs = new S3Libs();

                            $uploadResult = $s3Libs->uploadCloud($image, $imageDir, $id);
                            unlink($imageDir);
                           
                        }
                    }
                }       
                    
                $id = $dbPersons->create($id, $dataStorys,$image);
                $arr = ['error' => '', 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Пустое поле название Истории', 'result' => false];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function getHistorys()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();

        if ($id != 0 && !empty($id)) {
            $dbHistorys = new DbHistory();
            $arr = $dbHistorys->readHistorys($id);
            if ($arr != null) {
                $arr = ['content' => $arr, 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Нет записей', 'result' => null];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function getPerson()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        //$data = json_decode(file_get_contents("php://input"));
        if ($id != 0 && !empty($id)) {
            $dbHistory = new DbPersons();
            $arr = $dbHistory->readPerson($id);
            echo json_encode($arr);
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function updatePerson()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            $data = json_decode(file_get_contents("php://input"));
            //print_r($data);
            if (isset($data->story_name) && !empty($data->story_name) && isset($data->story_id)) {
                $dbPerson = new DbPersons();
                if (isset($data->content) && !empty($data->content)) {
                    $content = $data->content;
                } else {
                    $content = null;
                }
                $story_id = (int) $data->story_id;
                foreach($data as $key => $val){
                    if($key != 'story_name' || $key != 'content'){
                        $dataStorys[$key] = $val; 
                    }
                }
                if(!is_array($dataStorys))
                {
                    $dataStorys = null;
                }
                
                $id = $dbPerson->updatePerson($story_id, $data->story_name, $content,$dataStorys);
                $arr = ['error' => '', 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Пустое поле название Истории', 'result' => false];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function delete(){
        $jwt = new JWT();
        $id = $jwt->checkToken();
        $dbPerson = new DbPersons();
        $result = $dbPerson->delete($id);
    }
}
