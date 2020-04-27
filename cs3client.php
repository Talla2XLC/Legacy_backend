<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
require_once 'vendor/autoload.php';

use Aws\S3\S3Client;
use PHPUnit\Framework\TestCase;

class s3test extends TestCase
{
    const BUCKET = 'mamery_lane';
    const KEY = 'Yin_Yang.jpg';

    public function testUrl(): void
    {
        $content = "body_content";
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => 'AccessKeyEXAMPLE',
                'secret' => 'TheVeryLongLongLongSecretKeyEXAMPLE',
            ],
            'endpoint' => 'https://hb.bizmrg.com',
            'region'   => 'ru-msk',
            'version'  => 'latest',
        ]);
        $res = $s3Client->putObject([
            'Key'    => self::KEY,
            'Bucket' => self::BUCKET,
            'Body'   => $content,
        ]);
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => self::BUCKET,
            'Key'    => self::KEY,
        ]);

        $request = $s3Client->createPresignedRequest($cmd, '+20 minutes');
        // var_dump ($request);
        $presignedUrl = (string) $request->getUri();
        print($presignedUrl);
        $page = file_get_contents($presignedUrl);
        print($page);
        $this->assertEquals(
            $content,
            $page
        );
    }
    public function test()
    {
        $s3Client = new S3Client([
            'credentials' => [
                'key'    => 'wjVNWdZzrWt9EaLuRi2DoT',
                'secret' => 'cdWXHsw5ToJpdzpjKXW5AeE9E2Gr1Q25AULKndqcN2f4',
            ],
            'endpoint' => 'https://hb.bizmrg.com',
            'region'   => 'ru-msk',
            'version'  => 'latest',
        ]);
        $cmd = $s3Client->getCommand('GetObject', [
            'Bucket' => self::BUCKET,
            'Key'    => self::KEY,
        ]);
        $request = $s3Client->createPresignedRequest($cmd, '+1 minutes');
        $presignedUrl = (string) $request->getUri();
        return $presignedUrl;
    }
}
$test = new s3test();
$url = $test->test();
echo '<img src="'.$url.'" alt="">';