<?php

namespace App\Http\Controllers\Api;

use App\DTO\TaskData;
use App\DTO\UpdateTaskData;
use App\DTO\UpdateTaskStatusData;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\IndexTaskRequest;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Requests\Api\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;

class TaskController extends BaseApiController
{
    public function __construct(
        protected TaskService    $taskService,
        protected TaskRepository $taskRepository,

    )
    {
    }

    /**
     * Display a listing of the resource.
     * @throws AuthorizationException
     */
    public function index(IndexTaskRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $this->taskRepository->index();

        return self::successfulResponseWithData($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $taskData = new TaskData(...$request->validated());

        $this->taskService->store($taskData);

        return self::successfulResponse();
    }

    /**
     * Display the specified resource.
     * @throws AuthorizationException
     */
    public function show(Task $task): JsonResponse
    {
        $task = $this->taskRepository->show($task->id);

        $this->authorize('view', $task);

        return self::successfulResponseWithData($task);

    }

    /**
     * Update the specified resource in storage.
     * @throws AuthorizationException
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updateTaskData = new UpdateTaskData(...$request->validated());

        $this->authorize('update', $task);

        $updatedTask = $this->taskService->update(updateTaskData: $updateTaskData, id: $task->id);

        return self::successfulResponseWithData($updatedTask);
    }

    /**
     * Update task status.
     * @throws AuthorizationException
     */
    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $updateTaskStatusData = new UpdateTaskStatusData(...$request->validated());

        $this->authorize('updateStatus', $task);

        $this->taskService->updateStatus(updateTaskStatusData: $updateTaskStatusData, id: $task->id);

        return self::successfulResponse();
    }

    /**
     * Remove the specified resource from storage.
     * @throws AuthorizationException
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->destroy($task->id);

        return self::successfulResponse();
    }
}
