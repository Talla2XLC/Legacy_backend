<?php

return $rout =  [
    '/' => 'Home@index',
    '/db/getUsers/all' => 'Users@all',
    '/db/setUsers' => 'Users@setUser',
    '/db/getUsers/item' => 'Users@getItem',//test
    '/mail/sendmail' => 'SendMail@goToMail',// это для теста
    '/test/connect' => 'TestConnection@connect', //test
    '/auth/confirm' => 'AuthUser@checkEmail',
    '/tester' => 'Tester@testAuthUser',
    '/chechk/auth-email' => 'Users@checkUserEmail',
    '/user/add' => 'Users@authUser'
];