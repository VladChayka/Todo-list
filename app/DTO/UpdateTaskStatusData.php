<?php

namespace App\DTO;

readonly class UpdateTaskStatusData
{
    public function __construct(
        public int    $id,
        public string $status,
    )
    {
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
        ];
    }
}
