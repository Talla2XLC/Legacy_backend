<?php

namespace Core;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class Configuration implements iConfiguration
{
    /**
     * Хранит конфигурацию
     */
    private $config = [];
    private $loader;
    private $locator;

    public function __construct($dir)
    {
        $directories = [
            __DIR__.'/../'.$dir
        ];
        $this->setLocator($directories);
        $this->setLoader();
    }

    public function addConfig($file)
    {
        $configValues = $this->loader->load($this->locator->locate($file));
        //Application::dump($configValues);
        if($configValues){
            foreach($configValues as $key=>$arr){
                $this->config[$key] = $arr;
            }
        }
    }
    
    public function get($keyValue)
    {
        list($config, $key,$value) = explode('.',$keyValue);
        //Application::dump($this->config[$key]);
        
        if(isset($config) && isset($key,) && isset($value)){
            foreach($this->config[$config][$key][$value] as $key=>$val){
                $arr[$key] = $val;
                
            }
        }elseif(isset($config) && isset($key)){
            foreach($this->config[$config][$key] as $key=>$val){
                $arr[$key] = $val;
            }
        }else{
            $arr = null;
        }
        return $arr;
    }

    public function getConfig()
    {
        return $this->config;
    }

    public function setLoader()
    {
        $this->loader = new YamlConfigLoader($this->locator);
    }

    public function setLocator($dir)
    {
        $this->locator = new FileLocator($dir);
    }

    private function requireConfig()
    {
        require_once('config/config.php');
        Application::dump($config);
    }
}
