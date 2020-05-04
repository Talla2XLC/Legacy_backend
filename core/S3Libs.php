<?php

namespace Core;

use Aws\S3\S3Client;
use Core\Application;
use PHPUnit\Framework\TestCase;
use Aws\Credentials\CredentialProvider;
use Aws\Sdk;


class S3Libs extends Application
{
    
    public $key;
    
    

    //const KEY = 'persona.jpeg';
    /**
     * Это функция принимает пораметры для создание файлов в облоко
     * @param string $name_file название файла с форматом
     * @param string $iamgeDir путь к файлу
     * @param string $user индифакционый номер пользователя
     */
    public function uploadCloud($name_file,$imagDir,$user)
    {
        $this->key = $user.'/'.$name_file;

        $img = file_get_contents($imagDir);
        //$size = $_FILES['userfile']['size'];
        
        //$img = file_get_contents($this->key);
        $boundary = uniqid();
        $delimiter = '-------------' . $boundary;
        //$content = "Content-Length:" . $size . $delimiter . "Content-Type:" . $type;
        $content = $img;
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => $this->config_cloud['public_key'],
                'secret' => $this->config_cloud['secret'],
            ],
            'endpoint' => 'https://hb.bizmrg.com',
            'region'   => 'ru-msk',
            'version'  => 'latest',
        ]);
        $res = $s3Client->putObject([
            'Key'    => $this->key,
            'Bucket' => $this->config_cloud['bucket'],
            'Body'   => $content,
        ]);
        $result = $s3Client->getObject([
            'Bucket' => $this->config_cloud['bucket'],
            'Key'    => $this->key,
        ]);
        
        if($content == $result['Body']){
            $arr = array('error'=>'','result'=>true);
            
            return true;
        }else{
            $arr = array('error'=>'','result'=>true);
            //echo json_encode($arr);
             return false;
        }
        
    }
    public function getURL($name_file,$user)
    {
        $this->key = $user.'/'.$name_file;
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => $this->config_cloud['public_key'],
                'secret' => $this->config_cloud['secret'],
            ],
            'endpoint' => 'https://hb.bizmrg.com',
            'region'   => 'ru-msk',
            'version'  => 'latest',
        ]);
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => $this->config_cloud['bucket'],
            'Key'    => $this->key,
        ]);
        $request = $s3Client->createPresignedRequest($cmd, '+1 minutes');
        $presignedUrl = (string) $request->getUri();
        return $presignedUrl;
    }
    public function testPutCopyObject($copyFile): void
    {
        $sourcebucket = $this->config_cloud['bucket'];
        $sourcekey    = $this->key;
        $key          = $copyFile;
        $bucket       = $sourcebucket;

        $s3Client = new S3Client([
            'credentials' => [
                'key'    => $this->config_cloud['public_key'],
                'secret' => $this->config_cloud['secret'],
            ],
            'endpoint' => 'https://hb.bizmrg.com',
            'region'   => 'ru-msk',
            'version'  => 'latest',
        ]);
        $s3Client->copyObject([
            'Key'    => $key,
            'Bucket' => $bucket,
            'CopySource' => "{$sourcebucket}/{$sourcekey}",
        ]);
        $copy_result = $s3Client->getObject([
            'Bucket' => $bucket,
            'Key'    => $key,
        ]);
        $result = $s3Client->getObject([
            'Bucket' => $sourcebucket,
            'Key'    => $sourcekey,
        ]);
        
    }
    public function DeleteObject($nameFile,$content): void
    {
        $key          = $nameFile;
        $content      = $content;

        $s3Client = new S3Client([
            'credentials' => [
                'key'    => $this->config_cloud['public_key'],
                'secret' => $this->config_cloud['secret'],
            ],
            'endpoint' => 'https://hb.bizmrg.com',
            'region'   => 'ru-msk',
            'version'  => 'latest',
        ]);
        $s3Client->putObject([
            'Key'    => $this->key,
            'Bucket' => $this->config_cloud['bucket'],
            'Body'   => $content,
        ]);
        $result = $s3Client->deleteObject([
            'Key'    => $this->key,
            'Bucket' => $this->config_cloud['bucket'],
        ]);
        
    }
    
}
