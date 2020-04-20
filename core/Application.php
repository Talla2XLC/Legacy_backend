<?php
namespace Core;

class Application 
{
    public $config_mail;

    public $config_db;

    public function __construct()
    {
        $class =  get_class($this);

        if($class == 'Core\Mailer'){
            $configs = require_once('config/config_mail.php');
            //$mailConf = new Configuration();
            $this->config_mail = $configs['mail'];
        }
        if($class == 'Core\Model'){
            
        }
                
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