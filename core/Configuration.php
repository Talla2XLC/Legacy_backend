<?php

namespace Core;

use RedUNIT\Base\Aliasing;

class Configuration
{
    /**
     * Хранит конфигурацию
     */
    public $conf;
    private $configs;
    
    public function getConfig($configArray,$conf, $type = null)
    {
        
        //Application::dump($configs);
        /*
        if ($type === null) {
            $this->requireConfig();
            $type = $this->configs['type'];
            return $this->configs[$type][$conf];
        } else {
            $this->requireConfig();
            //$this->configs = require_once('config/config.php');            
            return $this->configs['glob']['mail'];
        }
        */
    }

    private function requireConfig()
    {
        require_once('config/config.php');
        Application::dump($config);
    }
}
