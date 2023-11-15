<?php

namespace App\Repositories;

use App\Enum\TaskStatusEnum;
use App\Filters\TaskFilter;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    public function __construct()
    {
    }

    public function index($tasks)
    {
        return $tasks;
    }

    public function show($task)
    {
        return $task;
    }

    public function store($newTask): Task
    {
        $newTask->save();

        return $newTask;
    }

    public function update($updateTaskData, $task)
    {
        $task->update($updateTaskData->toArray());

        return $task;
    }

    public function updateStatus($updateTaskStatusData, $task)
    {
        $task->update($updateTaskStatusData->toArray());

        return $task;
    }

    public function destroy($task)
    {
        $task->delete();

        return $task;
    }
}
