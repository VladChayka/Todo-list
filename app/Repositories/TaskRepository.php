<?php

namespace App\Repositories;

use App\DTO\TaskData;
use App\Enum\TaskStatusEnum;
use App\Filters\TaskFilter;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TaskRepository
{
    public function __construct(
        protected TaskFilter $taskFilter
    )
    {
    }

    public function index()
    {
        $tasks = Task::query()
            ->with('tasks')
            ->filter($this->taskFilter)
            ->get();

        return $tasks;
    }

    public function show($request): Model|Collection|Builder|array|null
    {
        $task = Task::query()
            ->with('tasks')
            ->whereNull('task_id')
            ->findOrFail($request->id);

        return $task;
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

        $newTask->save();

        return $newTask;
    }

    public function update($request)
    {
        $task = Task::findOrFail($request->id);

        $task->update($request->validated());

        $task->save();

        return $task;
    }

    public function updateStatus($request)
    {
        $task = Task::findOrFail($request->id);

        $subtaskStatuses = $task->tasks()->pluck('status')->toArray();

        if($task->status === TaskStatusEnum::TODO->value || in_array(TaskStatusEnum::TODO->value, $subtaskStatuses)){
            return abort(\Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN, 'Cannot set status to "done" because there are subtasks or task with status "todo".');
        }
        $task->update($request->validated());

        $task->save();

        return $task;
    }

    public function destroy($request)
    {
        $task = Task::findOrFail($request->id);

        $task->delete();

        return $task;
    }
}
