<?php

namespace App\Interfaces;

interface iUsers 
{
    /**
     * 
     */
    public function authUser($name,$email); //

    public function all();

    public function setUser();
}