<?php
namespace Core;



class Application 
{
    public $config_mail;

    public $config_db;

    private $config;
    protected $config_cloud;
    protected $config_id_face;


    public function __construct()
    {
        $class =  get_class($this);
        $config  = new Configuration('config');
        //print_r($config);
        if($class == 'Core\Mailer'){
            $config->addConfig('config.yaml');
            $confMail = $config->get('config.mail');
            //print_r($confMail);
            if($confMail != null){
                //self::dump($confMail);
                //print_r($confMail);
                $this->config_mail = $confMail;
            }
            
        }
        if($class == 'Core\S3Libs'){
            $config->addConfig('config_api_mail.yaml');
            $this->config_cloud = $config->get('config_api_mail.dev.cloud');
            //self::dump($this->config_cloud);
        }
        if($class == 'Core\MainFaceRecognition'){
            $config->addConfig('config_api_mail.yaml');
            //print_r($config);
            $this->config_id_face = $config->get('config_api_mail.dev.cloud');
            //self::dump($this->config_id_face);
        }
                
    }
    private function loadConfig()
    {
        

    }
    public static function dump($atr)
    {
        echo '<style>'."\n";
        self::getCss();
        echo '</style>'."\n";
        echo '<div class="app_dump"><pre>';
        print_r($atr);
        echo '</pre></div>';
    }

    private static function getCss()
    {
        require_once 'app_style.php';
    }
}