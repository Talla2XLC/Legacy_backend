<?php

namespace Core;

class JWT 
{
    private const KEY = '02f6cda5a10b5d98449b9ff9616ef159988bd279408a5a7a46956af7d42d';
    private const CIPHER = "aes-128-gcm";

    public function checkToken():int
    {
        if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = $_SERVER['HTTP_AUTHORIZATION'];
            $dt = $this->JWT_decode($token);
            if ($dt != null) {
                list($id, $time) = explode('.', $dt);
                return $id;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }
    public function JWT_encode($original)
    {
        $base64_origin = base64_encode($original);
        $ivlen = openssl_cipher_iv_length(self::CIPHER);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext = openssl_encrypt($base64_origin, self::CIPHER, self::KEY, $options=0, $iv, $tag);
        //echo $ciphertext.'<br>';
        $tag_64 = base64_encode($tag);
        //$iv_64 = base64_encode($iv);
        //echo strlen( $tag_64).'<br>';
        $token = base64_encode($iv.$tag_64.$ciphertext);
        //$JWT = array($ciphertext,$iv_64,$teg_64);
        return $token;
    }

    public function JWT_decode($token)
    {
        $c = base64_decode($token);
        $ivlen = openssl_cipher_iv_length(self::CIPHER);
        $iv =  substr($c,0,$ivlen);
        $tag_64 = substr($c,$ivlen,24);
        $tag = base64_decode($tag_64);
        $ciphertext = substr($c,$ivlen+24);
        //echo $ciphertext;
        //$iv = base64_decode($iv);
        
        $original = openssl_decrypt($ciphertext, self::CIPHER, self::KEY, $options=0, $iv, $tag);
        $txt_decode =  base64_decode($original);
        return $txt_decode;
        //echo $tag;
    }
}