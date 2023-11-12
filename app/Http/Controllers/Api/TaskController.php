<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Api\GetIdRequest;
use App\Http\Requests\Api\IndexTaskRequest;
use App\Http\Requests\Api\StoreTaskRequest;
use App\Http\Requests\Api\UpdateTaskRequest;
use App\Http\Requests\Api\UpdateTaskStatusRequest;
use App\Models\Task;
use App\Services\TaskService;

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
    public function index(IndexTaskRequest $request)
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
    public function store(StoreTaskRequest $request)
    {
        $this->taskService->store($request);

        return self::successfulResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(GetIdRequest $request)
    {
        $task = $this->taskService->show($request);

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
    public function update(UpdateTaskRequest $request)
    {
        $task = Task::findOrFail($request->id);

        $this->authorize('update', $task);

        $task = $this->taskService->update($request);

        return self::successfulResponseWithData($task);
    }

    public function updateStatus(UpdateTaskStatusRequest $request)
    {
        $task = Task::findOrFail($request->id);

        $this->authorize('updateStatus', $task);

        $this->taskService->updateStatus($request);

        return self::successfulResponse();
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GetIdRequest $request, Task $task)
    {
        $task = Task::findOrFail($request->id);

        $this->authorize('delete', $task);

        $this->taskService->destroy($request);

        return self::successfulResponse();
    }
}
