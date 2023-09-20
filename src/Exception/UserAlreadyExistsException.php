<?php

namespace App\Exception;

class UserAlreadyExistsException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('user already exists');
    }
}