<?php

namespace App\Repositories;

use App\DTO\GetIdData;
use App\DTO\UpdateTaskData;
use App\DTO\UpdateTaskStatusData;
use App\Enum\TaskStatusEnum;
use App\Filters\TaskFilter;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaskRepository
{
    public function __construct(
        protected TaskFilter $taskFilter
    )
    {
    }

    public function getById(int $id): Task
    {
        $task = Task::findOrFail($id);

        return $task;
    }

    public function index()
    {
        $tasks = Task::query()
            ->with('tasks')
            ->when(request('search'), function ($query, $search) {
                $query->whereFullText(['title', 'description'], $search);
            })
            ->filter($this->taskFilter)
            ->when(is_null(request('search')), function ($query) {
                $query->whereNull('task_id');
            })
            ->get();

        return $tasks;
    }

    public function show(GetIdData $taskId): Model|Collection|Builder|array|null
    {
        $task = Task::query()
            ->with('tasks')
            ->findOrFail($taskId->id);

        return $task;
    }

    public function store(Task $newTask): Task
    {
        $newTask->save();

        return $newTask;
    }

    public function update(UpdateTaskData $updateTaskData, Task $task): Task
    {
        $task->update($updateTaskData->toArray());

        return $task;
    }

    public function updateStatus(UpdateTaskStatusData $updateTaskStatusData, Task $task): Task
    {
        $task->update($updateTaskStatusData->toArray());

        return $task;
    }

    public function destroy(Task $task): Task
    {
        $task->delete();

        return $task;
    }
}
