<?php

namespace App\Services;

use App\Constants\TaskStatus;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Resources\TaskResource;
use App\Interfaces\TaskInterface;
use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\Rule;

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

    public function fetchTasks($id)
    {
        $user = User::findOrFail($id);

        return $user->tasks()->orderBy('created_at', 'DESC')->paginate(10);
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
        $task = Task::find($request->id)->first();
        if(!isset($task)){
            throw new ResourceNotFoundException("Task not found", 404);
        }
        $task->update([
            'status' => $request['status']
        ]);

        return $task;
    }
}
