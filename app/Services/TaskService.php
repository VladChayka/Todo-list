<?php

namespace App\Services;

use App\DTO\UserData;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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

    public function show($request): Model|Collection|Builder|array|null
    {
        return $this->taskRepository->show($request);
    }

    public function store($taskData): Task
    {
        return $this->taskRepository->store($taskData);
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
