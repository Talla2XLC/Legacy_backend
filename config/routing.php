<?php

return $rout =  [
    '/' => 'Home@index',
    '/db/getUsers/all' => 'Users@all',
    '/db/setUsers' => 'Users@setUser',
    '/db/setAlbum' => 'Album@create',
    '/db/getAlbum' => 'Album@getAlbum',
    '/db/updateAlbum' => 'Album@updateAlbum',
    '/db/setHistory' => 'History@createHistory',
    '/db/getHistory' => 'History@getHistory',
    '/db/updateHistory' => 'History@updateHistory',
    '/db/getUsers/item' => 'Users@getItem',//test
    '/mail/sendmail' => 'SendMail@goToMail',// это для теста
    '/test/connect' => 'CreateImg@setImg', //test
    '/upload/images' => 'Images@upload',
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
    '/errorRequest' => 'Error@info',//если код ошибки то выводим сообшение об ошибке
    '/search' => 'Search@searchData'
];