<?php

namespace App\Repositories;

use App\DTO\TaskData;
use App\Enum\TaskStatusEnum;
use App\Filters\TaskFilter;
use App\Models\Task;
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

    public function show($request)
    {
        $task = Task::query()
            ->with('tasks')
            ->findOrFail($request->id);

        return $task;
    }

    public function store($request)
    {
        $newTaskData = new TaskData(...$request->validated());

        $newTask = new Task();

        $newTask->title = $newTaskData->title;
        $newTask->description = $newTaskData->description;
        $newTask->status = $newTaskData->status;
        $newTask->priority = $newTaskData->priority;
        $newTask->created_at = now();
        $newTask->completed_at = $newTaskData->completedAt;
        $newTask->user_id = Auth::id();
        $newTask->task_id = $newTaskData->taskId;

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
