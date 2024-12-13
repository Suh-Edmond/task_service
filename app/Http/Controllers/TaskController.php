<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\ToggleTaskStateRequest;
use App\Services\TaskService;
use App\Trait\ResponseTrait;
use Illuminate\Support\Facades\Request;


class TaskController extends Controller
{
    private TaskService $taskService;
    use ResponseTrait;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchUserTasks($userId)
    {
        $data = $this->taskService->fetchTasks($userId);

        return $this->sendResponse($data, 200);
    }



    public function createTask(CreateTaskRequest $request)
    {
        $this->taskService->createTask($request);

        return $this->sendResponse("Task created Success", 204);
    }


    public function updateTask(CreateTaskRequest $request, $id)
    {
        $this->taskService->updateTask($request, $id);

        return $this->sendResponse("Task updated Success", 204);
    }

    public function toggleTask(ToggleTaskStateRequest $request)
    {
        $data = $this->taskService->toggleTaskStatus($request);

        return $this->sendResponse($data, 200);
    }


    public function deleteTasks($id, $userId)
    {
        $this->taskService->deleteTask( $id, $userId);
        return $this->sendResponse("Task deleted Success", 204);
    }
}
