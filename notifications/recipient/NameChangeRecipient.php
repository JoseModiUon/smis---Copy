<?php

namespace app\notifications\recipient;

use tuyakhov\notifications\NotifiableInterface;
use tuyakhov\notifications\NotifiableTrait;

class NameChangeRecipient implements NotifiableInterface
{
    use NotifiableTrait;

    public function __construct(protected string $email)
    {
    }

    public function routeNotificationForMail()
    {
        return $this->email;
    }

}
