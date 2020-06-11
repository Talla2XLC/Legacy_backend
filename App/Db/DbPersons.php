<?php

namespace App\Db;

use Core\Application;
use Core\Model;
use Core\Db;
use \RedBeanPHP\RedException;
use Core\S3Libs;

class DbPersons
{
    public function create(int $creator_id, array $all = null, $image)
    {
        $table_name = 'person';

        $model = new Model();
        \R::ext('xdispense', function ($type) {
            return \R::getRedBean()->dispense($type);
        });

        $person = \R::xdispense($table_name);
        $person->creator_id = $creator_id;
        $person->ico_url = $image;

        foreach ($all as $key => $val) {
            if ($key == "ico_url") {
                $person->key = $image;
            } else {
                $person->$key = $val;
            }

            \R::store($person);
        }

       $id_person =  \R::store($person);
        //$result = true;
        return $id_person;
    }

    public function readHistorys()
    {
        new Model();
        //return $result = \R::getAll('SELECT * FROM unit_story WHERE owner_id = ?',[$id_owner]);
        return $result = \R::findAll('unit_story');
    }

    public function readPerson(int $id)
    {
        new Model();
        $s3Libs = new S3Libs();
        $result = \R::findAll('person', ' creator_id = ?', [$id]);
        $array = \R::exportAll($result);
        $i = 0;
        foreach ($array as $person) {
           // echo $person['images'];
            //echo '--------------';
            //echo $person['id'];
            $ids_images = json_decode($person['images']);
            if (!empty($ids_images)) {
                foreach ($ids_images as $id_image) {
                    $photo = \R::getAll("SELECT content_url, photo_name FROM unit_photo WHERE id = {$id_image}");
                    if(!empty($photo)){
                        $photo = $photo[0];
                        $photo['content_url'] = $s3Libs->getURL($photo['content_url'], $id); 
                        $images[] = $photo;
                    }
                    
                }
            }


            if (isset($images) && !empty($images)) $array[$i]['images'] = $images;
            unset($images);
            $i++;
        }
        //print_r($array);
        return $array;
    }

    public function updatePerson($id, $first_name, array $all = null, $image = null)
    {
        //new Model();
        $S3Libs = new S3Libs();
        $person = \R::load('person', $id);
        //print_r($all);
        $ico_url = $person->ico_url;
        $person->first_name = $first_name;
        if ($image != null) {
            $person->ico_url = $image;
        }

        if ($all != null && is_array($all)) {
            foreach ($all as $key => $val) {
                if ($key == 'ico_url') {
                    //$person->$key = $person->ico_url;
                } else {
                    $person->$key = $val;
                }

                \R::store($person);
            }
        }
        $result = \R::store($person);
        return $result;
    }
}
