<?php

namespace App;

use Core\Application;
use Core\MainFaceRecognition;
use Core\JWT;
use Core\Model;
use Core\S3Libs;

class RecognitionFace
{
    private $tokens = [
        0 => 'EeMGiQ3qah52CKHt3DwJZ1ZBQbfi7dp9JsV4fFHeZsqBbPfz3',
        1 => 'UiTaWdUv2AKEXiFoFQ7uSiuDVR7UynK6MdoKRvT2BCQtbwbLr',
        2 => '2S3suu7w5cQqKXGejNVfHaYT8oCs8x7g6xr1hHCcbdWUxaFprY'
    ];
    public function addPerson()
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        new Model();
        $data = json_decode(file_get_contents("php://input"));
        $album_id = $data->id_album;
        $image = $data->image;
        $s3Libs = new S3Libs();
        $urlImage =  $s3Libs->getURL($image, $id);
        //$person = \R::findAll('person', 'creator_id = ?', [$id]);
        $mainFaceRec = new MainFaceRecognition();
        $registers = \R::getAll("SELECT * FROM register WHERE id = (SELECT MAX(id) FROM register)");
        $register = $registers[0];
        $token = $register['token'];
        $space = $register['space'];
        $persons = json_decode($register['id_mcs_person']);
        $i = 0;
        foreach ($persons as $person) {
            $idPerson[] = str_replace('person', '', $person);
            if ($i > 0) {
                if ($idPerson[$i] > $idPerson[$i - 1]) {
                    $maxIdPerson = $idPerson[$i];
                } else {
                    $maxIdPerson = $idPerson[$i - 1];
                }
            } else {
                $maxIdPerson = $idPerson[$i];
            }

            $i++;
        }
        $nextID = $maxIdPerson + 1;
        if ($nextID == 10001) {
            if ($space < 10) {
                $space = $space + 1;
                $personID  = 1;
            } else {
                $token = $token + 1;
                $space = 1;
                $personID = 1;
            }
        } else {
            $personID = $nextID;
        }
        //echo $token.'-'.$space.'-'.$personID;
        $resurlts = $mainFaceRec->recognize($this->tokens[$token], $urlImage, $space);
        $json = json_decode($resurlts);
        
        $info = $json->body->objects[0];
        $info = $info->persons;
        $i = 0;
        foreach ($info as $item) {
            $col_person[] = $item->tag;
            if($item->tag != "undefined"){
                $is_person[] = $item->tag;
            }

        }
        if(count($col_person) > 1){
            $arr = ['error'=>'Вы загрузили фото с несколькоми лицами','result'=>false];
            echo json_encode($arr);
            exit; 
        }elseif(!empty($is_person) && isset($is_person)){
            $regPerson = $is_person[0];
        }

        $register = \R::dispense('register');

        if(isset($regPerson) && !empty($regPerson)){
            
            $register->token = $token;
            $register->space = $space;
            $register->person = $regPerson;
        }else{
            $register->token = $token;
            $register->space = $space;
            $register->person = 'person'.$personID;
        }
        \R::store($register);

        //$resurlt = $mainFaceRec->set($this->tokens[0], 'images/persona.jpeg', 6);
        //Application::dump($resurlt);

    }
    public function recognizePersone(string $image = 'images/friends2.jpeg')
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        $model = new Model();
        //$data = json_decode(file_get_contents("php://input"));
        if ($id != 0 && !empty($id)) {
        } else {
            $arr = ['error' => 'Токен не действителен', 'result' => false];
            echo json_encode($arr);
            exit;
        }


        //$data = json_decode(file_get_contents("php://input"));
        $mainFaceRec = new MainFaceRecognition();
        $persons = \R::findAll('person', 'creator_id = ?', [$id]);
        $photo = \R::findOne('unit_photo', 'content_url = ?', [$image]);
        foreach ($persons as $person) {
            $personID[] = $person->id;
            $personName[$person->id] = $person->first_name;
            $personLastName[$person->id] = $person->last_name;
        }
        $registers = \R::findAll('register', 'id_person IN(' . \R::genSlots($personID) . ')', $personID);
        foreach ($registers as $register) {
            //Application::dump($register);
            $mscPersonsID = $register->id_mcs_person;
            $jsonMscPersonID = json_decode($mscPersonsID);
            foreach ($jsonMscPersonID as $jsonPersonID) {
                $personsID[$register->token]['space' . $register->space][$jsonPersonID] = $personName[$register->id_person] . ' '
                    . $personLastName[$register->id_person];
                //$personsID[$register->id_person]['name'] = $personName[$register->id_person] . ' '
                // . $personLastName[$register->id_person];

            }
        }

        foreach ($personsID as $token => $spaces) {
            foreach ($spaces as $space => $persons) {
                $sp = str_replace('space', '', $space);
                foreach ($persons as $key => $person) {
                    $resurlts = $mainFaceRec->recognize($this->tokens[$token], $image, $sp);
                    if (!empty($resurlts)) {

                        $json = json_decode($resurlts);
                        //Application::dump($json);
                        $info = $json->body->objects[0];
                        $info = $info->persons;
                        $i = 0;
                        foreach ($info as $item) {
                            //print_r($item);
                            $n = 0;
                            foreach ($item->coord as $key => $coord) {
                                if ($key == 1) {
                                    $height1 = $coord;
                                    $coordArr[$n] = $coord;
                                }
                                if ($key == 3) $height2 = $coord;
                                if (!empty($width1) && !empty($width2))
                                    $height = $height2 - $height1;//
                                if ($key == 0) {
                                    $width1 = $coord;
                                    $coordArr[$n] = $coord;
                                }
                                if ($key == 2) $width2 = $coord;
                                if (!empty($height1) && !empty($height2))
                                    $width = $width2 - $width1;
                                $n++;
                            }
                            $arr[$i]['WH'] = [$width, $height];
                            //echo $width.'.'.$height.'/';
                            $arr[$i]['coord'] = $coordArr;
                            //echo $item->tag;
                            //Application::dump($personsID);

                            if (!empty($persons[$item->tag])) {
                                //$id_person = $personsID[$register->id_person][$item->tag];
                                $arr[$i]['name'] = $persons[$item->tag];
                                if (!empty($item->sex)) $arr[$i]['sex'] = $item->sex;
                                if (!empty($item->emotion)) $arr[$i]['emotion'] = $item->emotion;
                                if (!empty($item->age)) $arr[$i]['age'] = $item->age;
                                ///$photo->coordinates = json_encode($arr);
                                //\R::store($photo);
                            } else {
                                $arr[$i]['name'] = "не известно";
                                if (!empty($item->sex)) $arr[$i]['sex'] = $item->sex;
                                if (!empty($item->emotion)) $arr[$i]['emotion'] = $item->emotion;
                                if (!empty($item->age)) $arr[$i]['age'] = $item->age;
                                //$photo->coordinates = json_encode($arr);
                                //\R::store($photo);
                            }


                            $i++;
                            //echo $i;

                        }
                    }
                }

                /*
                    
                    */

                //echo '<br>';

            }
        }
        $photo->coordinates = json_encode($arr);
        \R::store($photo);
        return true;
        //Application::dump($arr);
        //echo json_encode($arr);
    }

    private function getNextIdPerson(object $model, $idAccount, array $idsPersons)
    {
        $registr = \R::find('register', 'id_person = IN(' . \R::genSlots($idsPersons) . ')', $idsPersons);
        $mcsId = $registr['id_mcs_person'];
    }
}
