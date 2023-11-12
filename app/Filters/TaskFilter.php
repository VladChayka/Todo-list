<?php

namespace App\Filters;

class TaskFilter extends QuerySortAndFilter
{
    public static array $sortKeys = ['status', 'priority', 'title', 'description', 'created_at', 'completed_at'];

    public function filterStatus(string $status): void
    {
        $this->builder->where('status', $status);
    }

    public function filterPriority(int $priority): void
    {
        $this->builder->where('priority', $priority);
    }

    public function filterTitle(string $title): void
    {
        $this->builder->where('title', $title);
    }

    public function filterDescription(string $description): void
    {
        $this->builder->where('description', $description);
    }

    public function filterCreatedAt(string $createdAt): void
    {
        $this->builder->where('created_at', $createdAt);
    }

    public function filterCompletedAt(string $completedAt): void
    {
        $this->builder->where('completed_at', $completedAt);
    }
}
