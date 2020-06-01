<?php

    namespace App;

    use Core\JWT;


    Class CalculatingDatePhoto
    {
        public function calculatingDatePhoto(){
            $data = json_decode(file_get_contents("php://input"));
            $birthDate = $data->birthDate;
            $age = $data->age;
            
            do{
                $i = 0;
                $photoDate = $birthDate[$i] + $age[$i];
                $i++;
            } while ($birthDate[$i] <= count($birthDate) - 1 && $age[$i] <= count($age) - 1);

            $photoDate = min($photoDate);

            if ($photoDate != null) {
                $photoDate = ['content' => $photoDate, 'result' => true];
                echo json_encode($photoDate);
            }
        }
        
    }