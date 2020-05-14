<?php

return $rout =  [
    '/' => 'Home@index',
    '/db/getUsers/all' => 'Users@all',
    '/db/setUsers' => 'Users@setUser',
    '/db/setAlbum' => 'Album@create',
    '/db/getUsers/item' => 'Users@getItem',//test
    '/mail/sendmail' => 'SendMail@goToMail',// это для теста
    '/test/connect' => 'CreateImg@setImg', //test
    '/uplad/images' => 'Images@upload',
    '/get/images' => 'Images@getImages',
    '/set/person' => 'RecognitionFace@addPerson',
    '/recognize' => 'RecognitionFace@recognizePersone',
    '/auth/confirm' => 'AuthUser@checkEmail',
    '/tester' => 'Tester@testAuthUser',//test
    '/check/auth-email' => 'Users@checkUserEmail',
    '/user/registration' => 'Users@registrationUser',
    '/user/auth' => 'Users@authUser',
    '/db/setAccount' => 'Users@setAccount',
    '/test/jwt' => 'TestJWT@getJWT',
    '/checkToken' => 'Users@checkToken',
    '/testrequest' => 'Request@test',
    '/errorRequest' => 'Error@info'//если код ошибки то выводим сообшение об ошибке
];