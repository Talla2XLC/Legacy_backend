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

            $collection = \R::findCollection( 'album,');
        }
    }