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
    private $emotion = [
        "Neutral" => "Нейтраль", "Happiness" => "Счастье", "Sadness" => "Печаль",
        "Surprise" => "Удивление", "Fear" => "Страх", "Disgust" => "Отвращение",
        "Anger" => "Гнев", "Contempt" => "Презрение"
    ];
    public function addPerson($imageDir = null,$id_person)
    {
        $jwt = new JWT();
        $id = $jwt->checkToken();
        new Model();
        $s3Libs = new S3Libs();
        $data = json_decode(file_get_contents("php://input"));
        //$album_id = $data->id_album;
        if ($imageDir == null) {
            $image = $data->image;
            $urlImage =  $s3Libs->getURL($image, $id);
        }


        //$person = \R::findAll('person', 'creator_id = ?', [$id]);
        $mainFaceRec = new MainFaceRecognition();
        $registers = \R::getAll("SELECT * FROM registry WHERE id = (SELECT MAX(id) FROM registry)");
        if(!empty($registers)){
        $register = $registers[0];
        $token = $register['token'];
        $space = $register['space'];
        $persons = json_decode($register['id_mcs_person']);
        }
        
        $i = 0;
        if (!empty($persons)) {
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
        }else{
            $token = 0;
            $space = 1;
            $personID = 1;
        }
        //echo $token.'-'.$space.'-'.$personID;
        if (isset($urlImage)) {
            $resurlts = $mainFaceRec->recognize($this->tokens[$token], $urlImage, $space);
        } else {
            $resurlts = $mainFaceRec->recognize($this->tokens[$token], $imageDir, $space);
        }


        $json = json_decode($resurlts);

        $info = $json->body->objects[0];
        $info = $info->persons;
        $i = 0;
        foreach ($info as $item) {
            $col_person[] = $item->tag;
            if ($item->tag != "undefined") {
                $is_person[] = $item->tag;
            }
        }
        if (count($col_person) > 1) {
            $arr = ['error' => 'Вы загрузили фото с несколькоми лицами', 'result' => false];
            echo json_encode($arr);
            exit;
        } elseif (!empty($is_person) && isset($is_person)) {
            $regPerson = $is_person[0];
        }

        $register = \R::dispense('registry');

        if (isset($regPerson) && !empty($regPerson)) {
            //$regPerson = json_encode($regPerson);
            //echo $regPerson;
            $register->token = $token;
            $register->space = $space;
            $register->id_mcs_person = '["'.$regPerson.'"]';
            $register->id_person = $id_person;
            $resurlt = $mainFaceRec->set($this->tokens[$token], $imageDir, $space);
        } else {
            $register->token = $token;
            $register->space = $space;
            $personId = 'person' . $personID;
            $array = array();
            $array[] = $personId;
            $json = json_encode($array);
            $register->id_mcs_person = $json;
            $register->id_person = $id_person;
            $resurlt = $mainFaceRec->set($this->tokens[$token], $imageDir, $space);
        }
        \R::store($register);
        return $resurlt;
        
        //Application::dump($resurlt);

    }
    public function recognizePersone($id, string $tmpImage, $id_photo)
    {
        $model = new Model();
        //echo $image;
        $mainFaceRec = new MainFaceRecognition();
        $persons = \R::findAll('person', 'creator_id = ?', [$id]);
        //echo '-----persons---' . "\n";
        //print_r($persons);
        //echo '-----end persons-----' . "\n";
        $photo = \R::load('unit_photo', $id_photo);
        //echo "-----photo-----\n";
        //print_r($photo);
        //echo "-----photo-----\n";
        if (!empty($persons)) {
            foreach($persons as $person){
                $personID[] = $person->id;
                $personName[$person->id] = $person->first_name;
                $personLastName[$person->id] = $person->last_name;
            }
            
                
                //print_r($personID);
                if (isset($personID) && !empty($personID)) $registers = \R::findAll('registry', 'id_person IN(' . \R::genSlots($personID) . ')', $personID);
                //print_r($registers);
                if (!empty($registers)) {
                    foreach ($registers as $register) {
                        //Application::dump($register);
                        $mscPersonsID = $register->id_mcs_person;
                        $jsonMscPersonID = json_decode($mscPersonsID);
                        foreach ($jsonMscPersonID as $jsonPersonID) {
                            $personsID[$register->token]['space' . $register->space][$jsonPersonID] = $personName[$register->id_person] . ' '
                                . $personLastName[$register->id_person];
                            $personsID[$register->token]['space' .$register->space]['id_person'] = $register->id_person;
                            //$personsID[$register->id_person]['name'] = $personName[$register->id_person] . ' '
                            // . $personLastName[$register->id_person];

                        }
                    }
                } else {
                   // echo '--not--';
                    $arr = $mainFaceRec->getResultRecognize('2UebSbfgZ3cJBPzdTSpMsp6ezZkvn9LzyHSrgwvF8MYYgWMtoP', $tmpImage, 1);
                    $coordinates = json_encode($arr);
                    //print_r($coordinates);
                    $result = \R::exec("UPDATE unit_photo SET coordinates = '{$coordinates}' WHERE id = {$id_photo}");
                    if ($result) return true;
                }
            
        } else {
        }
        //print_r($personsID);

        if (isset($personsID) && !empty($personsID)) {
            foreach ($personsID as $token => $spaces) {
                foreach ($spaces as $space => $persons) {
                    $sp = str_replace('space', '', $space);
                    foreach ($persons as $key => $person) {
                       // $arr = $mainFaceRec->getResultRecognize($this->tokens[$token], $tmpImage, $sp);
                       $resurlts = $mainFaceRec->recognize($this->tokens[$token],$tmpImage,$sp);
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
                                        $height = $height2 - $height1; //
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
                                
                                //print_r($persons);
                                //Формируем массив для сохронение в  БД 
                                if (!empty($persons[$item->tag])) {
                                    //$id_person = $personsID[$register->id_person][$item->tag];
                                    $arr[$i]['name'] = $persons[$item->tag];
                                    if (!empty($item->sex)) $arr[$i]['sex'] = $item->sex;
                                    if (!empty($item->emotion)) $arr[$i]['emotion'] = $this->emotion[$item->emotion];
                                    if (!empty($item->age)) $arr[$i]['age'] = $item->age;
                                    $id_person = $persons['id_person'];
                                    $per = \R::load('person',$id_person);
                                    $json_images = json_decode($per->images);
                                    $json_images = (array) $json_images;

                                    $json_images[] = $id_photo;

                                    $json_images = json_encode($json_images);

                                    $per->images = $json_images;

                                    \R::store($per);

                                    
                                    ///$photo->coordinates = json_encode($arr);
                                    //\R::store($photo);
                                } else {
                                    $arr[$i]['name'] = "не известно";
                                    if (!empty($item->sex)) $arr[$i]['sex'] = $item->sex;
                                    if (!empty($item->emotion)) $arr[$i]['emotion'] = $this->emotion[$item->emotion];
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
            //print_r($photo);
            //echo '--yes--';
            $coordinates = json_encode($arr);
            
            //print_r($coordinates);
            $result = \R::exec("UPDATE unit_photo SET coordinates = '{$coordinates}' WHERE id = {$id_photo}");
            if ($result) return true;
        }

        //Application::dump($arr);
        //echo json_encode($arr);
    }

    private function getNextIdPerson(object $model, $idAccount, array $idsPersons)
    {
        $registr = \R::find('registry', 'id_person = IN(' . \R::genSlots($idsPersons) . ')', $idsPersons);
        $mcsId = $registr['id_mcs_person'];
    }
}
