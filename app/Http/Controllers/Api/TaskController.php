<?php

namespace App\Http\Controllers\Api;

use App\DTO\GetIdData;
use App\DTO\TaskData;
use App\DTO\UpdateTaskData;
use App\DTO\UpdateTaskStatusData;
use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\GetIdRequest;
use App\Http\Requests\Api\IndexTaskRequest;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Requests\Api\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;

class TaskController extends BaseApiController
{
    public function __construct(
        protected TaskService $taskService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexTaskRequest $request): JsonResponse
    {
        $this->authorize('viewAny', Task::class);

        $tasks = $this->taskService->index();

        return self::successfulResponseWithData($tasks);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     */
    public function show(GetIdRequest $request, Task $task): JsonResponse
    {

        $taskId = new GetIdData(...$request->validated());

        $task = $this->taskService->show($taskId, $task);

        $this->authorize('view', $task);

        return self::successfulResponseWithData($task);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $updateTaskData = new UpdateTaskData(...$request->validated());

        $this->authorize('update', [$task, $updateTaskData]);

        $task = $this->taskService->update($updateTaskData);

        return self::successfulResponseWithData($task);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): JsonResponse
    {
        $updateTaskStatusData = new UpdateTaskStatusData(...$request->validated());

        $this->authorize('updateStatus', [$task, $updateTaskStatusData]);

        $this->taskService->updateStatus($updateTaskStatusData);

        return self::successfulResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GetIdRequest $request, Task $task): JsonResponse
    {
        $taskId = new GetIdData(...$request->validated());

        $this->authorize('delete', [$task, $taskId]);

        $this->taskService->destroy($taskId);

        return self::successfulResponse();
    }
}
