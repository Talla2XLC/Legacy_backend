<?php
namespace Core;



class Application 
{
    public $config_mail;

    public $config_db;

    private $config;

    


    public function __construct()
    {
        $class =  get_class($this);
        $config  = new Configuration('config');
        $config->addConfig('config.yaml');
        //self::dump($config->getConfig());
         
        self::dump($this->config_mail);
        if($class == 'Core\Mailer'){
            $confMail = $config->get('config.dev.mail');
            if($confMail != null){
                self::dump($confMail);
                $this->config_mail = $confMail;
            }
            
        }
        if($class == 'Core\Model'){
            
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