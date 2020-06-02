<?php

namespace Core;

class MainFaceRecognition extends Application
{
    public $token;
    public function __construct()
    {
        parent::__construct();
        $service_token = 'EeMGiQ3qah52CKHt3DwJZ1ZBQbfi7dp9JsV4fFHeZsqBbPfz3';
        //print_r($this->config_id_face);
        $answerApi = $this->authApi($this->config_id_face);
        $answerApi = json_decode($answerApi);
        //$this->token = $answerApi->access_token;
        $this->token = $service_token;
    }


    /**
     * Функция создает новых первсон для дальнейшего опознание
     * @param string $token ключ авторизации
     * @param string $path путь к картине 
     * @param int $id_person id персоны
     */
    public function set(string $token, string $path, int $id_person)
    {
        $url = "https://smarty.mail.ru/api/v1/persons/set?oauth_provider=mcs&oauth_token={$token}";
        $meta = '{"space":"1","images":[{"name":"' . $path . '","person_id":' . $id_person . '}]}';
        $filenames = array($path);
        $files = array();
        foreach ($filenames as $f) {
            if (strlen($f) == 0) {
                # just to make correct multipart/form-data request
                $files[$f] = 'fake content';
            } else {
                $files[$f] = file_get_contents($f);
            }
        }
        $fields = array("meta" => $meta);
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $fields, $files);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            #CURLOPT_VERBOSE => true,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
              "Content-Type: multipart/form-data; boundary=" . $delimiter,
              "Content-Length: " . strlen($post_data),
            ),
          ));
          $response = curl_exec($curl);
          curl_close($curl);
          return $response;
    }
    public function recognize($token,$picture,$spaceID)
    {
        $url = "https://smarty.mail.ru/api/v1/persons/recognize?oauth_provider=mcs&oauth_token={$token}";
        $meta = '{"space":"'.$spaceID.'","images":[{"name":"'.$picture.'"}],"create_new":false}';
        $filenames = array($picture);
        $files = array();
        foreach ($filenames as $f) {
            if (strlen($f) == 0) {
                # just to make correct multipart/form-data request
                $files[$f] = 'fake content';
            } else {
                $files[$f] = file_get_contents($f);
            }
        }
        $fields = array("meta" => $meta);
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $post_data = $this->build_data_files($boundary, $fields, $files);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            #CURLOPT_VERBOSE => true,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
              "Content-Type: multipart/form-data; boundary=" . $delimiter,
              "Content-Length: " . strlen($post_data),
            ),
          ));
          $response = curl_exec($curl);
          curl_close($curl);
          return $response;        
    }
    private function authApi($config)
    {
        $url = "https://mcs.mail.ru/auth/oauth/v1/token";
        $data = [
            'client_id' => $config['public_key'],
            'client_secret' => $config['secret'],
            'grant_type' => 'client_credentials'
        ];
        $post_data = http_build_query($data);
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            #CURLOPT_VERBOSE => true,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded; boundary=" . $delimiter
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    private function build_data_files($boundary, $fields, $files)
    {
        $data = '';
        $eol = "\r\n";

        $delimiter = '-------------' . $boundary;

        foreach ($fields as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . "\"" . $eol . $eol
                . $content . $eol;
        }

        foreach ($files as $name => $content) {
            $data .= "--" . $delimiter . $eol
                . 'Content-Disposition: form-data; name="' . $name . '"; filename="' . $name . '"' . $eol
                . 'Content-Type: image/jpeg' . $eol
                . 'Content-Transfer-Encoding: binary' . $eol;

            $data .= $eol;
            $data .= $content . $eol;
        }
        $data .= "--" . $delimiter . "--" . $eol;
        return $data;
    }

    public function getResultRecognize($token,$image,$space){
        $resurlts = $this->recognize($token, $image, $space);
            //echo $resurlts;
            //print_r($resurlts);
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

                   
                        $arr[$i]['name'] = "не известно";
                        if (!empty($item->sex)) $arr[$i]['sex'] = $item->sex;
                        if (!empty($item->emotion)) $arr[$i]['emotion'] = $this->emotion[$item->emotion];
                        if (!empty($item->age)) $arr[$i]['age'] = $item->age;
                        //$photo->coordinates = json_encode($arr);
                        //\R::store($photo);
                    


                    $i++;
                    //echo $i;

                }
                return $arr;
            }else{
                return false;
            }
    }
}
