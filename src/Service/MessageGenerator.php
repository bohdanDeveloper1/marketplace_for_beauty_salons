<?php

namespace App\Service;

class MessageGenerator
{
    public function getHappyMessage(): string
    {
        $message = 'message from MessageGenerator: thanks fot ordering!';


        return $message;
    }
}