<?php

namespace App;

use App\Db\DbHistory;
use App\Db\DbPersons;
use Core\JWT;
use Core\S3Libs;
use Core\Model;

class Persons
{
    public function setPerson()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        //echo $id;
        $recognise = new RecognitionFace();
        if (!empty($id) && $id != 0) {
            //$data = json_decode(file_get_contents("php://input"));
            $data = $_POST;

            if (isset($data['first_name']) && !empty($data['first_name'])) {
                $dbPersons = new DbPersons();

                foreach ($data as $key => $val) {
                    if ($key != 'story_name' || $key != 'content') {
                        $dataPersons[$key] = $val;
                    }
                }
                if (!is_array($dataPersons)) {
                    $dataStorys = null;
                }
                //print_r($dataStorys);
                //print_r($_FILES['ico_url']);
                if (isset($_FILES['ico_url'])) {

                    $image = $_FILES['ico_url']['name'];
                    //echo '----- name----';
                    $n = 0;
                    $nImage = $image;
                    $image_sh = sha1($image);
                    $img = '';
                    for ($i = 0; $i < 15; $i++) {
                        $math = rand(0, 39);
                        $img .= $image_sh[$math];
                    }
                    $image = $img . $nImage;
                    //echo '---fName---';
                    //echo $image;
                    $type = $_FILES['ico_url']['type'];
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
                    $tmp = $_FILES['ico_url']['tmp_name'];
                    $imageDir = 'temp/' . $image;
                    if ($type) {
                        move_uploaded_file($tmp, $imageDir);
                        $exifImage = exif_imagetype($imageDir);
                        if ($exifImage == IMAGETYPE_JPEG || $exifImage == IMAGETYPE_PNG) {
                            $s3Libs = new S3Libs();
                            $uploadResult = $s3Libs->uploadCloud($image, $imageDir, $id);
                            if ($uploadResult == true) {
                                $id_person = $dbPersons->create($id, $dataPersons, $image);
                                $arr = ['error' => '', 'result' => true];
                                echo json_encode($arr);
                            } else {
                                //$id = $dbPersons->create($id, $dataPersons, $image);
                                $arr = ['error' => '', 'result' => false];
                                echo json_encode($arr);
                            }
                            
                            $recognise->addPerson($imageDir,$id_person);
                            unlink($imageDir);
                        }else{
                            unlink($imageDir);
                        }
                        
                    }

                }
            } else {
                $arr = ['error' => 'Пустое поле название Истории', 'result' => false];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function getPersons()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();

        if ($id != 0 && !empty($id)) {
            $dbPersons = new DbPersons();

            $arr = $dbPersons->readPerson($id);

            //print_r($arr);
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
            $s3Libs = new S3Libs();

            $arr = $dbHistory->readPerson($id);
            //print_r($arr);
            $i = 0;
            foreach ($arr as $person) {
                //echo $person["ico_url"];
                //echo "----------";
                //print_r($person);

                $arr[$i]['ico_url'] = $s3Libs->getURL($person['ico_url'], $id);
                $i++;
            }
            echo json_encode($arr);
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function updatePerson()
    {
        new Model();
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            //$data = json_decode(file_get_contents("php://input"));
            $data = $_POST;
            if (isset($data) && !empty($data)) {
                $dbPerson = new DbPersons();

                $story_id = (int) $data['id'];
                foreach ($data as $key => $val) {
                    if ($key != 'story_name' || $key != 'content') {
                        $dataStorys[$key] = $val;
                    }
                }
                if (!is_array($dataStorys)) {
                    $dataStorys = null;
                }
                $id_person = $dataStorys['id'];
                //print_r($dataStorys);
                if (isset($_FILES['ico_url'])) {

                    $image = $_FILES['ico_url']['name'];
                    //echo '----- name----';
                    $n = 0;
                    $nImage = $image;
                    $image_sh = sha1($image);
                    $img = '';
                    for ($i = 0; $i < 15; $i++) {
                        $math = rand(0, 39);
                        $img .= $image_sh[$math];
                    }
                    $image = $img . $nImage;
                    //echo '---fName---';
                    //echo $image;
                    $type = $_FILES['ico_url']['type'];
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
                    $tmp = $_FILES['ico_url']['tmp_name'];
                    $imageDir = 'temp/' . $image;
                    if ($type) {
                        move_uploaded_file($tmp, $imageDir);
                        $exifImage = exif_imagetype($imageDir);
                        if ($exifImage == IMAGETYPE_JPEG || $exifImage == IMAGETYPE_PNG) {
                            $person = \R::load('person',$id_person);
                            $s3Libs = new S3Libs();
                            //$s3Libs->DeleteObject($person->ico_url,$id);
                            $uploadResult = $s3Libs->uploadCloud($image, $imageDir, $id);
                            if ($uploadResult == true) {
                                $id = $dbPerson->updatePerson($story_id, $data['first_name'], $dataStorys, $image);
                                $arr = ['error' => '', 'result' => true];
                                echo json_encode($arr);
                                exit();
                            } else {
                                //$id = $dbPersons->create($id, $dataPersons, $image);
                                $arr = ['error' => '', 'result' => false];
                                echo json_encode($arr);
                                exit();
                            }
                            unlink($imageDir);
                        }
                        unlink($imageDir);
                    }
                }else{
                    $id = $dbPerson->updatePerson($story_id, $data['first_name'], $dataStorys);
                    $arr = ['error' => '', 'result' => true];
                    echo json_encode($arr);
                }
                
                
            } else {
                $arr = ['error' => 'Пустые поля ', 'result' => false];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function deletePerson()
    {
        //echo 'sdsds';
        
        $_PUT = array();
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $putdata = file_get_contents('php://input');
            $exploded = explode('&', $putdata);

            foreach ($exploded as $pair) {
                $item = explode('=', $pair);
                if (count($item) == 2) {
                    $_PUT[urldecode($item[0])] = urldecode($item[1]);
                }
            }
        }
        
        $jwt = new JWT();
        $id = $jwt->checkToken();

        $dbPerson = new Model();
        //print_r($_POST);
        //echo '--------------';
        //print_r($_GET);
        //echo 'sdsd';
        if(isset($_POST['id'])){
            $id_person = $_POST['id'];
        }
        if($data = json_decode(file_get_contents("php://input"))){
            $id_person = $data->id;
        }
        //echo $id_person;
        //echo '---------';
        //print_r($_PUT);
        //$id_person = $data->id;
        //echo $id_person;

        $result = $dbPerson->delete('person',$id_person);
        //http_response_code(200);
        //echo "Хорошо";
        
        
        if($result){
            
            $arr = ['error'=>'','result'=>true];
            echo json_encode($arr);
        }else{
            $arr = ['error'=>'Не удалось удалить!','result'=>false];
            echo json_encode($arr);
        }
        
    }
}
