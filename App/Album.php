<?php

namespace App;

use App\Db\DbAlbum;

class Album
{
    public function create()
    {
        //echo 'test';
        $item = json_decode(file_get_contents("php://input"));
        if(isset($item['name_album']) && !empty($item['name_album'])){
            $dbAlbum = new DbAlbum();
            $id = $dbAlbum->createAlbum('9',$item['name_album']);
            echo $id;
        }
    }
}