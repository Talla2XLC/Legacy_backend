<?php

namespace App;

use Core\Application;
use Core\MainFaceRecognition;

class RecognitionFace
{
    public function addPerson()
    {
        $mainFaceRec = new MainFaceRecognition();

        $resurlt = $mainFaceRec->set($mainFaceRec->token,'/images/persona.jpeg',1);
        //Application::dump($resurlt);
        
    }
    public function recognizePersone()
    {
        $mainFaceRec = new MainFaceRecognition();
        $resurlt = $mainFaceRec->recognize($mainFaceRec->token,'friends.jpeg');
        $json = json_decode($resurlt);
        //Application::dump($res);
        // тут блок получение базу данных имен персоны
        $person = array('person1'=>'Джесика','person2'=>'Кристина');
        //конец блока
        $info = $json->body->objects[0];
        $info = $info->persons;
        $i = 0;
        foreach($info as $item){
            //print_r($item);
            $arr[$i]['coord'] = $item->coord;
            if(!empty($person[$item->tag])){
                $arr[$i]['tag'] = $person[$item->tag];
            }else{
                $arr[$i]['tag'] = $item->tag;
            }
            
            $i++;
        }
    }
}