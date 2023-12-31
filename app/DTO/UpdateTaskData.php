<?php

namespace App\DTO;

readonly class UpdateTaskData
{
    public function __construct(
        public string      $title,
        public string      $description,
        public int         $priority,
        public null|string $completedAt = null,
        public null|int    $taskId = null,
    )
    {
    }

    public function toArray()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'completed_at' => $this->completedAt,
            'task_id' => $this->taskId,
        ];
    }
}
