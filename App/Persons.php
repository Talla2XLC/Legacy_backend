<?php

namespace App;

use App\Db\DbHistory;
use App\Db\DbPersons;
use Core\JWT;

class Persons
{
    public function setPerson()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            $data = json_decode(file_get_contents("php://input"));
            //print_r($data);
            if (isset($data->first_name) && !empty($data->first_name)) {
                $dbHistory = new DbPersons();
                
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

                $id = $dbHistory->create($id, $dataStorys);
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
