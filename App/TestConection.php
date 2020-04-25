<?php

namespace App;

use Aws\S3\S3Client;

class TestConection
{
    public function connect()
    {/*
        $c3Client = new S3Client([
            'version'     => 'latest',
            'region'      => 'us-west-2',
            'credentials' => [
                'key'    => 'oGc9jLoyF5XWzEgjpZfBLp',
                'secret' => '6NUkgm1T1wnEt7mdg614gcZQubbfLETM92rxot29xQjN',
            ],
            'endpoint' => 'https://hb.bizmrg.com'
         ]);
         //print_r($c3Client);
        $result =  $c3Client->getObjectAcl([
             '9T4D3U.jpg' => 'my traktor',
             'Key' => 'oGc9jLoyF5XWzEgjpZfBLp'
         ]);
         echo $result['body'];
         */
         echo "test";
    }
    
}