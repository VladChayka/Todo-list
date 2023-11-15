<?php

namespace App\Services;

use App\DTO\UserData;
use App\Enum\TaskStatusEnum;
use App\Filters\TaskFilter;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(
        protected TaskRepository $taskRepository,
        protected TaskFilter     $taskFilter
    )
    {
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

        return $this->taskRepository->index($tasks);
    }

    public function show($taskId, $task)
    {
        $task = $task::query()
            ->with('tasks')
            ->findOrFail($taskId->id);

        return $this->taskRepository->show($task);
    }

    public function store($taskData): Task
    {
        $newTask = new Task();

        $newTask->title = $taskData->title;
        $newTask->description = $taskData->description;
        $newTask->status = $taskData->status;
        $newTask->priority = $taskData->priority;
        $newTask->created_at = now();
        $newTask->completed_at = $taskData->completedAt;
        $newTask->user_id = Auth::id();
        $newTask->task_id = $taskData->taskId;

        return $this->taskRepository->store($newTask);
    }

    public function update($updateTaskData)
    {
        $task = Task::findOrFail($updateTaskData->id);
        $task = $this->taskRepository->update($updateTaskData, $task);
        return $task;
    }

    public function updateStatus($updateTaskStatusData)
    {
        $task = Task::findOrFail($updateTaskStatusData->id);

        $subtaskStatuses = $task->tasks()->pluck('status')->toArray();

        if (in_array(TaskStatusEnum::TODO->value, $subtaskStatuses)) {
            return abort(\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, 'Cannot set status to "done" because there are subtasks or task with status "todo".');
        }

        return $this->taskRepository->updateStatus($updateTaskStatusData, $task);
    }

    public function destroy($taskId)
    {
        $task = Task::findOrFail($taskId->id);
        return $this->taskRepository->destroy($task);
    }
}
