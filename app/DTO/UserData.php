<?php

namespace App\DTO;

readonly class UserData
{
    public function __construct(
        public string      $email,
        public null|string $name = null,
        public null|string $password = null
    )
    {
    }
}
