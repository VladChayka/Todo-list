<?php

namespace App\Services;

use App\DTO\TaskData;
use App\DTO\UpdateTaskData;
use App\DTO\UpdateTaskStatusData;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function __construct(
        protected TaskRepository $taskRepository,

    )
    {
    }

    public function store(TaskData $taskData): Task
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

    public function update(UpdateTaskData $updateTaskData, int $id): Task
    {
        $updateTaskData = $updateTaskData->toArray();

        $task = $this->taskRepository->update($updateTaskData, $id);

        return $task;
    }

    public function updateStatus(UpdateTaskStatusData $updateTaskStatusData, int $id): JsonResponse|Task
    {
        $updateTaskStatusData = $updateTaskStatusData->toArray();

        return $this->taskRepository->updateStatus(updateTaskStatusData: $updateTaskStatusData, id: $id);
    }
}
