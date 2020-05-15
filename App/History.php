<?php

namespace App;

use App\Db\DbHistory;
use Db\DbAlbum;
use Core\JWT;

class Hitory
{
    public function createHistory()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        if (!empty($id) && $id != 0) {
            $data = json_decode(file_get_contents("php://input"));
            if (isset($data->story_name) && !empty($data->story_name)) {
                $dbHistory = new DbHistory();
                if (isset($data->content) && !empty($data->content)) {
                    $content = $data->content;
                } else {
                    $content = null;
                }
                $id = $dbHistory->createHistory($id, $data->story_name, $data->content);
                $arr = ['error' => '', 'result' => true];
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Пустое поле название Истории'];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => '', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function getHistorys()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();

        if ($id != 0 && !empty($id)) {
            $dbHistorys = new DbHistory();
            $arr = $dbHistorys->readHistorys();
            if ($arr != null) {
                echo json_encode($arr);
            } else {
                $arr = ['error' => 'Нет записей', 'result' => false];
                echo json_encode($arr);
            }
        } else {
            $arr = ['error' => 'Токен не дествителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function getHistory()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        $data = json_decode(file_get_contents("php://input"));// сда передовать id истории которую нужно показать
        $idHistory = $data->id;
        if ($id != 0 && !empty($id)) {
            $dbHistory = new DbHistory();
            $arr = $dbHistory->readHistory($idHistory);
            echo json_encode($arr);
        } else {
            $arr = ['error' => 'Токен не дествителен', 'result' => false];
            echo json_encode($arr);
        }
    }

    public function updateHistory()
    {
        //Грач извини сделал что смог, спать хочу, уже позно да и после работы я.
    }
}