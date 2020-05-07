<?php

namespace Core;

class MainFaceRecognition extends Application
{
    public $token;
    public function __construct()
    {
        parent::__construct();
        $service_token = '6CshMa9WiZzMiYndXn18fQCSToU3qew9V3hAXJcpccjS2x3zS';
        $answerApi = $this->authApi($this->config_id_face);
        $answerApi = json_decode($answerApi);
        $this->token = $answerApi->access_token;
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
    public function recognize($token,$picture)
    {
        $url = "https://smarty.mail.ru/api/v1/persons/recognize?oauth_provider=mcs&oauth_token={$token}";
        $meta = '{"space":"1","images":[{"name":"'.$picture.'"}],"create_new":false}';
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
}
