<?php

namespace App\DTO;

readonly class GetIdData
{
    public function __construct(
        public int      $id,
    )
    {
    }
}
