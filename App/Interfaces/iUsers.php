<?php

namespace App\Interfaces;

interface iUsers 
{
    /**
     * 
     */
    public function authUser(); //

    public function all();

    public function setUser();
    public function registrationUser($password, $email);
}