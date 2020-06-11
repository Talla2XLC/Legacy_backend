<?php

namespace App;

use App\Db\DbHistory;
use Core\JWT;
use Core\Model;

class History
{
    public function createHistory()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            $data = json_decode(file_get_contents("php://input"));
            //print_r($data);
            if (isset($data->story_name) && !empty($data->story_name)) {
                $dbHistory = new DbHistory();
                if (isset($data->content) && !empty($data->content)) {
                    $content = $data->content;
                } else {
                    $content = null;
                }
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
                $id = $dbHistory->createHistory($id, $data->story_name, $content,$dataStorys);
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

    public function getHistory()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        //$data = json_decode(file_get_contents("php://input"));
        if ($id != 0 && !empty($id)) {
            $dbHistory = new DbHistory();
            $arr = $dbHistory->readHistory($id);
            echo json_encode($arr);
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function updateHistory()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            $data = json_decode(file_get_contents("php://input"));
            //print_r($data);
            if (isset($data->story_name) && !empty($data->story_name) && isset($data->story_id)) {
                $dbHistory = new DbHistory();
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
                
                $id = $dbHistory->updateHistory($story_id, $data->story_name, $content,$dataStorys);
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
        $model = new Model();
        $data = json_decode(file_get_contents("php://input"));
        $id_story = $data->id;
        $result = $model->delete('unit_story',$id_story);
        if($result){
            $arr = ['error' => '', 'result' => true];
            echo json_encode($arr);
        }else{
            $arr = ['error' => 'Произошла ошибка, не удалось удалить', 'result' => false];
            echo json_encode($arr);
        }
    }
}
