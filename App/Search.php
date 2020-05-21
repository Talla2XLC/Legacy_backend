<?php

    namespace App;

    use Core\Application;
    use Core\Model;
    use Core\Db;
    use \RedBeanPHP\RedException;
    use Core\JWT;

    class Search
    {
        public function searchData()
        {
            $jwt = new JWT();
            $id = $jwt->checkToken();

            if (!empty($id) && $id != 0) {
                $data = json_decode(file_get_contents("php://input"));
            } else {
                $arr = ['error' => 'Пустой запрос поиска'];
                echo json_encode($arr);
                exit();
            }

            $model = new Model();
            $search = '%'.$data->search.'%';

            $album = \R::findAll('album', 'owner_id = ? AND album_name LIKE = ?', [$id, $search]);
            $i = 0;
            if(!empty($album)){
                foreach($album as $value){
                    $array[$i]['album']['album_name'] = $value->album_name;
                    $array[$i]['album']['id'] = $value->id;
                    $i++;                
                }
            }
            
            $audio = \R::findAll('unit_audio', 'owner_id = ? AND audio_name LIKE = ?', [$id, $search]);
            $i = 0;
            if(!empty($audio)){
                foreach($audio as $value){
                    $array[$i]['audio']['audio_name'] = $value->audio_name;
                    $array[$i]['audio']['id'] = $value->id;
                    $i++;                
                }
            }

            $photo = \R::findAll('unit_photo', 'owner_id = ? AND photo_name LIKE = ?', [$id, $search]);
            $i = 0;
            if(!empty($photo)){
                foreach($photo as $value){
                    $array[$i]['photo']['photo_name'] = $value->photo_name;
                    $array[$i]['photo']['id'] = $value->id;
                    $i++;                
                }
            }

            $story = \R::findAll('unit_story', 'owner_id = ? AND story_name LIKE = ?', [$id, $search]);
            $i = 0;
            if(!empty($story)){
                foreach($story as $value){
                    $array[$i]['story']['story_name'] = $value->story_name;
                    $array[$i]['story']['id'] = $value->id;
                    $i++;                
                }
            }

            $video = \R::findAll('unit_video', 'owner_id = ? AND video_name LIKE = ?', [$id, $search]);
            $i = 0;
            if(!empty($video)){
                foreach($video as $value){
                    $array[$i]['video']['video_name'] = $value->video_name;
                    $array[$i]['video']['id'] = $value->id;
                    $i++;                
                }
            }

            if(!empty($array) && is_array($array)){
                $arr = ['conten' => $array, 'result' => true];
            } else {
                $arr = ['error' => '', 'result' => true];
            }

        }
    }