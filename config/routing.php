<?php

return $rout =  [
    '/' => 'Home@index',
    '/db/getUsers/all' => 'Users@all',
    '/db/setUsers' => 'Users@setUser',
    '/db/getUsers/item' => 'Users@getItem',//test
    '/mail/sendmail' => 'SendMail@goToMail',// это для теста
    '/auth' => 'AuthUser@getURL', //test
    '/auth/confirm' => 'AuthUser@checkEmail',
    '/tester' => 'Tester@authUser',
    '/chechk/auth-email' => 'Users@checkUserEmail'
];