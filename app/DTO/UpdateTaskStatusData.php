<?php

namespace App\DTO;

readonly class UpdateTaskStatusData
{
    public function __construct(
        public string $status,
    )
    {
    }

    public function toArray()
    {
        return [
            'status' => $this->status,
        ];
    }
}
