<?php

namespace App\Repositories;

use App\Enum\TaskStatusEnum;
use App\Filters\TaskFilter;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class TaskRepository
{
    public function __construct(
        protected TaskFilter $taskFilter
    )
    {
    }

    public function getById(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function index()
    {
        return Task::query()
            ->with('tasks')
            ->when(request('search'), function ($query, $search) {
                $query->whereFullText(['title', 'description'], $search);
            })
            ->filter($this->taskFilter)
            ->when(is_null(request('search')), function ($query) {
                $query->whereNull('task_id');
            })
            ->get();
    }

    public function store(Task $newTask): Task
    {
        $newTask->save();

        return $newTask;
    }

    public function update(array $updateTaskData, int $id): Task
    {
        $task = $this->getById($id);

        $task->update($updateTaskData);

        return $task;
    }

    public function updateStatus(array $updateTaskStatusData, int $id): Task
    {
        $task = $this->getById($id);

        $subtaskStatuses = $task->tasks()->pluck('status')->toArray();

        if ($updateTaskStatusData['status'] === TaskStatusEnum::DONE->value && in_array(TaskStatusEnum::TODO->value, $subtaskStatuses)) {
            return abort(\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, 'Cannot set status to "done" because there are subtasks or task with status "todo".');
        }

        $task->update($updateTaskStatusData);

        return $task;
    }

    public function destroy(Task $task): Task
    {
        $task->delete();

        return $task;
    }
}
