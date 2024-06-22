<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_errors_for_invalid_fields()
    {
        $this->withoutMiddleware();

        $response = $this->post('/api/protected/tasks/create', [
            'title'     => '',
            'description'   => '',
            'status'      => true,
            'due_date'      => '',
            'user_id'                => '',

        ]);

        $response->assertSessionHasErrorsIn('title');
        $response->assertSessionHasErrorsIn('description');
        $response->assertSessionHasErrorsIn('due_date');
        $response->assertSessionHasErrorsIn('user_id');

    }

    public function test_creates_tasks()
    {
        $this->withoutMiddleware();
        $created =User::factory([
            'email'  => 'email@gmailcc.om',
            'name'   => 'Test',
            'password'=>'Summer1343',
        ])->create();

        $userId = User::findOrFail($created->id);

        $response = $this->post('/api/protected/tasks/create', [
            'title'         => 'Test',
            'description'   => 'description',
            'status'      => true,
            'due_date'    => '2029/09/09',
            'user_id'     => $userId,

        ]);


        $task = Task::first();

        $response->assertOk();
        $response->assertSeeText("Transaction completed successfully");

        $this->assertEquals('Tests', $task->fresh()->title);
        $this->assertEquals('description', $task->fresh()->description);

        $this->assertEquals('status', $task->fresh()->status);
        $this->assertEquals('due_date', $task->fresh()->due_date);

        $this->assertCount(1, Task::all());

    }

    public function test_update_task()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $task = Task::factory([
            'title'         => 'Test',
            'description'   => 'description',
            'status'      => true,
            'due_date'    => '2029/09/09',
            'user_id'     => $user->id,
        ])->create();

        Task::findOrFail($task->id);

        $response = $this->put("/api/protected/tasks/".$task->id."/update", [
            'title'         => 'ttile',
            'description' => 'description',
            'status'     => true,
            'due_date' => '2024/09/09'
        ]);

        $response->assertOk();
        $response->assertJson([
            'message' => 204,
            "success" => true,
            'data' => 'Task updated Success'
        ]);
        $this->assertCount(1, Task::all());
    }

    public function test_fetch_tasks_return_all_tasks()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        Task::factory([
            'title'         => 'Test',
            'description'   => 'description',
            'status'      => true,
            'due_date'    => '2029/09/09',
            'user_id'     => $user->id,
        ])->create();

        $response = $this->get("/api/protected/tasks/users/".$user->id);

        $response->assertOk();
    }

    public function test_delete_return_200_and_remove_task()
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $task = Task::factory([
            'title'         => 'Test',
            'description'   => 'description',
            'status'      => true,
            'due_date'    => '2029/09/09',
            'user_id'     => $user->id,
        ])->create();

        $response = $this->delete("/api/protected/tasks/".$task->id."/users/".$user->id);

        $response->assertOk();
    }
}
