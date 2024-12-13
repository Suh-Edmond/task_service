<?php

namespace Tests\Feature;

use App\Constants\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;
    private User $user;
    private Task $task;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory([
            'email' => "johndoe@gmail.com",
            "name" => "John Doe",
            "password" => Hash::make("password")
        ])->create();

        $this->be($this->user);

        $this->task = Task::factory([
            'title'         => 'Test',
            'description'   => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book",
            'status'        => TaskStatus::COMPLETE,
            'due_date'      => '2029/09/09',
            'user_id'       => $this->user->id,
        ])->create();


    }
    public function test_returns_errors_for_invalid_fields()
    {

        $response = $this->post('/api/protected/tasks/create', [
            'title'         => '',
            'description'   => '',
            'status'        => TaskStatus::COMPLETE,
            'due_date'      => '',
            'user_id'       => 'fake_user_id',

        ]);

        $response->assertSessionHasErrorsIn('title');
        $response->assertSessionHasErrorsIn('description');
        $response->assertSessionHasErrorsIn('due_date');
        $response->assertSessionHasErrorsIn('user_id');

    }

    public function test_creates_tasks()
    {
        $response = $this->post('api/protected/tasks/create', [
            'title'         => 'My First Task',
            'description'   => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s",
            'status'        => TaskStatus::COMPLETE,
            'due_date'      => '2029/09/09',
            'userId'        => $this->user->id,

        ]);
        $task = Task::all();


        $response->assertOk();
        $this->assertEquals( "Task created Success", $response['data']);
        $this->assertTrue($response['success']);
        $this->assertEquals('My First Task', $task[1]->fresh()->title);
        $this->assertEquals("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s", $task[1]->fresh()->description);

        $this->assertEquals(TaskStatus::COMPLETE, $task[1]->fresh()->status);
        $this->assertEquals('2029/09/09', $task[1]->fresh()->due_date);
        $this->assertEquals($this->user->id, $task[1]->fresh()->user_id);

        $this->assertCount(2, Task::all());

    }

    public function test_update_task()
    {

        $response = $this->put("/api/protected/tasks/".$this->task->id."/update", [
            'title'         => 'title',
            'description'   => 'description',
            'status'        => TaskStatus::COMPLETE,
            'due_date'      => '2024/09/09',
            'userId'     => $this->user->id,
        ]);

        $response->assertOk();
        $response->assertJson([
            'message' => 204,
            "success" => TaskStatus::COMPLETE,
            'data' => 'Task updated Success'
        ]);
        $this->assertCount(1, Task::all());
    }

    public function test_fetch_tasks_return_all_tasks()
    {

        $response = $this->get("/api/protected/tasks/users/".$this->user->id);

        $response->assertOk();
    }

    public function test_delete_return_200_and_remove_task()
    {
        $response = $this->delete("/api/protected/tasks/".$this->task->id."/users/".$this->user->id);

        $response->assertOk();
    }

    public function test_toggle_task_return_200_and_change_task_status()
    {

        $response = $this->put("/api/protected/tasks/toggle-status", [
            'id'            => $this->task->id,
            'status'        => TaskStatus::COMPLETE,
        ]);

        $response->assertOk();
    }
}
