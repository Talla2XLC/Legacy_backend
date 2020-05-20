<?php

namespace App;

use App\Db\DbHistory;
use Core\JWT;

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
                $id = $dbHistory->createHistory($id, $data->story_name, $content);
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
                $id = $dbHistory->updateHistory($story_id, $data->story_name, $content);
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
}
