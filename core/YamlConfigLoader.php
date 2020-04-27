<?php

namespace Core;


use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

class YamlConfigLoader extends FileLoader
{
    public function load($resource, $typy = null)
    {
        return Yaml::parse(file_get_contents($resource));
    }

    public function supports($resource, $typy = null)
    {
        return is_string($resource) && 'yaml' == pathinfo($resource,PATHINFO_EXTENSION);
    }
}