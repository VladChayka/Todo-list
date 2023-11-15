<?php

namespace App\Services;

use App\DTO\GetIdData;
use App\DTO\TaskData;
use App\DTO\UpdateTaskData;
use App\DTO\UpdateTaskStatusData;
use App\Enum\TaskStatusEnum;
use App\Models\Task;
use App\Repositories\TaskRepository;
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

    public function update(UpdateTaskData $updateTaskData): Task
    {
        $task = $this->taskRepository->getById($updateTaskData->id);

        $task = $this->taskRepository->update($updateTaskData, $task);

        return $task;
    }

    public function updateStatus(UpdateTaskStatusData $updateTaskStatusData): Task
    {
        $task = $this->taskRepository->getById($updateTaskStatusData->id);

        $subtaskStatuses = $task->tasks()->pluck('status')->toArray();

        if (in_array(TaskStatusEnum::TODO->value, $subtaskStatuses)) {
            return abort(\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, 'Cannot set status to "done" because there are subtasks or task with status "todo".');
        }

        return $this->taskRepository->updateStatus($updateTaskStatusData, $task);
    }

    public function destroy(GetIdData $taskId): Task
    {
        $task = $this->taskRepository->getById($taskId->id);
        return $this->taskRepository->destroy($task);
    }
}
