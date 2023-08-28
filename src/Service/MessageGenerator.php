<?php

namespace App\Service;

class MessageGenerator
{
    public function getHappyMessage(): string
    {
        $message = 'message from MessageGenerator: thanks fot ordering!';


        return $message;
    }
    public function reservationCanceled(): string
    {
        $message = 'Reservation was canceled!';

        return $message;
    }
    public function reservationDoesnotExist(): string
    {
        $message = 'Reservation was canceled early, it does`t exist!';

        return $message;
    }
}