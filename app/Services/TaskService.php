<?php

namespace App\Services;

use App\DTO\UserData;
use App\Repositories\TaskRepository;

class TaskService
{
    public function __construct(
        protected TaskRepository $taskRepository
    )
    {
    }

    public function index()
    {
        return $this->taskRepository->index();
    }

    public function show($request)
    {
        return $this->taskRepository->show($request);
    }

    public function store($request)
    {
        return $this->taskRepository->store($request);
    }

    public function update($request)
    {
        $task = $this->taskRepository->update($request);
        return $task;
    }

    public function updateStatus($request)
    {
        return $this->taskRepository->updateStatus($request);
    }

    public function destroy($request)
    {
        return $this->taskRepository->destroy($request);
    }
}
