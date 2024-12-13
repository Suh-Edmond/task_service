<?php


namespace App\Interfaces;

interface TaskInterface {

    public function createTask($request);

    public function updateTask($request, $id);

    public function fetchTasks($id, $request);

    public function deleteTask($id, $userId);

    public function toggleTaskStatus($request);
}
