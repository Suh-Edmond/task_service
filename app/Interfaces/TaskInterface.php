<?php


namespace App\Interfaces;

interface TaskInterface {

    public function createTask($request);

    public function updateTask($request, $id);

    public function fetchTasks($id);

    public function deleteTask($id, $userId);
}
