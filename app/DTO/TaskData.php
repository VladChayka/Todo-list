<?php

namespace App\DTO;

readonly class TaskData
{
    public function __construct(
        public string      $title,
        public string      $description,
        public string      $status,
        public int         $priority,
        public null|string $completedAt = null,
        public null|int    $taskId = null,
    )
    {
    }
}
