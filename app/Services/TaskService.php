<?php

namespace App\Services;

use App\Exceptions\ResourceNotFoundException;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use App\Models\User;

class TaskService implements TaskInterface
{

    public function createTask($request)
    {
        $user = User::findOrFail($request->userId);
        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request-> due_date,
            'status' => $request->status,
            'user_id' => $user->id
        ]);
    }

    public function updateTask($request, $id)
    {
        Task::findOrFail($id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'status' => $request->status
        ]);
    }

    public function fetchTasks($id, $request)
    {
        $user = User::findOrFail($id);

        $sort = $request->sortBy;

        $filter = $request->filter;

        $userTasks = $user->tasks();

        if(isset($filter)){
            $userTasks = $userTasks->where('status', $filter);
        }

        if (isset($sort)){
            switch ($sort) {
                case 'DATE_ASC':
                    $userTasks->orderBy('created_at');
                    break;
                case 'DATE_DESC':
                    $userTasks->orderBy('created_at', 'DESC');
                    break;
                default:
                    $userTasks->orderByDesc('created_at');
                    break;
            }
        }

        return $userTasks->paginate($request->per_page);
    }

    public function deleteTask($id, $userId)
    {
        $task = Task::where('id', $id)->where('user_id', $userId)->first();
        if (!isset($task)){
            throw new ResourceNotFoundException("Task not exist", 404);
        }
        $task->delete();
    }

    public function toggleTaskStatus($request)
    {
        $task = Task::where('id', $request->id)->where('user_id', $request->userId)->first();
        if(!isset($task)){
            throw new ResourceNotFoundException("Task not found", 404);
        }
        $task->update([
            'status' => $request->status
        ]);

        return $task;
    }
}
