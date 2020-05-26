<?php

namespace App;

use Core\Application;
use Core\MainFaceRecognition;
use Core\JWT;
use Core\Model;

class RecognitionFace
{
    public function addPerson()
    {
        $mainFaceRec = new MainFaceRecognition();

        $resurlt = $mainFaceRec->set($mainFaceRec->token,'https://i.pinimg.com/originals/d3/08/34/d30834214a4b70df3145d9c89d382ebf.jpg',5);
        //Application::dump($resurlt);
        
    }
    public function recognizePersone()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        new Model();
        //$data = json_decode(file_get_contents("php://input"));
        if ($id != 0 && !empty($id)) {
            
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
            exit;
        }
        $data = json_decode(file_get_contents("php://input"));
        $mainFaceRec = new MainFaceRecognition();
        foreach($data->img_urls as $img_url){
            $resurlts[] = $mainFaceRec->recognize($mainFaceRec->token,$img_url);
        }
        
        $json = json_decode($resurlts[0]);
        //print_r($json);
        //Application::dump($res);
        // тут блок получение базу данных имен персоны
        //$person = array('person1'=>'Дженнифер Энистон','person2'=>'Дэ́вид Ло́уренс','person3'=>'Спанч Боб','person4'=>'Вин Дизел','person5'=>'Скала Джонсон');
        //конец блока
        $persons = \R::findAll("person","creator_id = ?",[$id]);
        if(!empty($persons)){
            foreach($persons as $person)
            $arrPerson[] = $person->id;
        }
        /*
        {
            token:space:{"persse2":id,"coord":[l,t,w,h]},

        };
        */
        
        $info = $json->body->objects[0];
        $info = $info->persons;
        //print_r($info);
        $i = 0;
        foreach($info as $item){
            //print_r($item);
            $n = 0;
            foreach($item->coord as $key=>$coord){
                if($key == 1) {
                    $height1 = $coord;
                    $coordArr[$n] = $coord;
                }
                if($key == 3) $height2 = $coord;
                if(!empty($width1) && !empty($width2))
                $height = $height2 - $height1;//
                if($key == 0) {
                    $width1 = $coord;
                    $coordArr[$n] = $coord;
                }
                if($key == 2) $width2 = $coord;
                if(!empty($height1) && !empty($height2))
                $width = $width2 - $width1;
                $n++;
            }
            $arr[$i]['WH']=[$width,$height];
            //echo $width.'.'.$height.'/';
            $arr[$i]['coord'] = $coordArr;
            if(!empty($person[$item->tag])){
                $arr[$i]['tag'] = $person[$item->tag];
            }else{
                $arr[$i]['tag'] = $item->tag;
            }
            //$arr[$i]['sex'] = $item->sex;
            //$arr[$i]['emotion'] = $item->emotion;
            //$arr[$i]['age'] = $item->age;
            $i++;
            //echo $i;
        }
        echo json_encode($arr);
    }
}