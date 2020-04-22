<?php

namespace Core;

interface iConfiguration
{
    public function addConfig($file);
    public function get($keyValue);
}