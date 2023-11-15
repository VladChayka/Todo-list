<?php

namespace App\Filters;

class TaskFilter extends QuerySortAndFilter
{
    public static array $sortKeys = ['status', 'priority', 'title', 'description', 'created_at', 'completed_at'];

    public function filterStatus(string $status): void
    {
        $this->builder->where('status', 'like', '%' . $status . '%');
    }

    public function filterPriority(int $priority): void
    {
        $this->builder->where('priority', 'like', '%' . $priority . '%');
    }

    public function filterCreatedAt(string $createdAt): void
    {
        $this->builder->where('created_at', 'like', '%' . $createdAt . '%');
    }

    public function filterCompletedAt(string $completedAt): void
    {
        $this->builder->where('completed_at', 'like', '%' . $completedAt . '%');
    }
}
