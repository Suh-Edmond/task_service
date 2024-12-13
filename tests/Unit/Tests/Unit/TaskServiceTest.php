<?php

namespace Tests\Unit;

use App\Exceptions\ResourceNotFoundException;
use App\Http\Requests\CreateTaskRequest;
 use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Faker\Generator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
 use Mockery\MockInterface;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use DatabaseMigrations;
    private TaskService $taskService;
    private CreateTaskRequest $data;
    private User $user;
    private Task $task;

    private Generator $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = new Generator();

        $this->taskService = new TaskService();

        $this->user = User::factory([
            'email'  => 'email@gmailcc.om',
            'name'   => 'Test',
            'password'=>'Summer1343',
            'id' => "3456349503459034534535"
        ])->create();

        $this->task = Task::factory([
            'title'         => "My New Task",
            'description'   => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation",
            'status'        =>  true,
            'due_date'      =>  "2025/12/12",
            'user_id'       =>  $this->user->id
        ])->create();

        $this->data = new CreateTaskRequest([
            'userId' => $this->user->id,
            'title'=> 'My Task Title',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation',
            'status' => true,
            'due_date'=> "2025/09/09",
        ]);

    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_create_return_model_not_found(): void
    {
        $this->data['userId'] = "435234";
        $userMock = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('findOrFail')->andReturnNull();
        });

        $userMock->findOrFail();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\User] ".$this->data['userId']);


        $this->taskService->createTask($this->data);
    }

    public function test_create_return_success_after_created_task(): void
    {

        $userMock = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('findOrFail')->andReturn($this->user);
        });

        $userMock->findOrFail($this->data['userId']);

        $this->taskService->createTask($this->data);

        $this->assertDatabaseCount(Task::class, 2);
        $this->assertDatabaseHas(Task::class, ['title' => $this->data['title']]);
        $this->assertDatabaseHas(Task::class, ['description' => $this->data['description']]);
        $this->assertDatabaseHas(Task::class, ['due_date' => $this->data['due_date']]);
        $this->assertDatabaseHas(Task::class, ['status' => $this->data['status']]);
    }

    public function test_update_return_not_found_when_task_not_found(): void
    {

        $updatedData = new CreateTaskRequest([
            'userId'       => $this->user->id,
            'title'        => 'My Task Title Change',
            'description'  => 'Some task description change',
            'status'       => true,
            'due_date'     => "2024/12/13",
            'id'           => $this->task->id
        ]);

        $taskMock = $this->mock(Task::class, function (MockInterface $mock){
            $mock->shouldReceive('findOrFail')->andReturnNull();
        });


        $taskMock->findOrFail($this->data['id']);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\Task]");

        $this->taskService->updateTask($updatedData, $this->data['id']);
    }

    public function test_update_return_success_after_update_task(): void
    {
        $updatedData = new CreateTaskRequest([
            'userId'       => $this->user->id,
            'title'        => 'My Task Title Change',
            'description'  => 'Some task description change',
            'status'       =>  true,
            'due_date'     => "2024/12/13",
            'id'           => $this->task->id
        ]);

        $taskMock = $this->mock(Task::class, function (MockInterface $mock){
            $mock->shouldReceive('findOrFail')->andReturn($this->task);
        });

        $taskMock->findOrFail($this->data['id']);

        $this->taskService->updateTask($updatedData, $updatedData['id']);

        $this->assertDatabaseCount(Task::class, 1);
        $this->assertDatabaseHas(Task::class, ['title' => $updatedData['title']]);
        $this->assertDatabaseHas(Task::class, ['description' => $updatedData['description']]);
        $this->assertDatabaseHas(Task::class, ['due_date' => $updatedData['due_date']]);
        $this->assertDatabaseHas(Task::class, ['status' => $updatedData['status']]);
    }

    public function test_fetch_user_tasks_returns_not_found():void
    {

        $userMock = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('findOrFail')->andReturnNull();
        });

        $userMock->findOrFail($this->data['userId']);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\User]");

        $this->taskService->fetchTasks($this->data['id']);
    }

    public function test_fetch_user_tasks_returns_all_user_task():void
    {

        $userMock = $this->mock(User::class, function (MockInterface $mock){
            $mock->shouldReceive('findOrFail')->andReturn($this->user);
        });

        $userMock->findOrFail($this->user->id);

        $response = $this->taskService->fetchTasks($this->user->id);

        $this->assertEquals(1, count($response));
    }


    public function test_delete_user_task_returns_exception_when_user_not_found():void
    {

        $taskMock = $this->mock(Task::class, function (MockInterface $mock){
            $mock->shouldReceive('first')->andReturnNull();
        });

        $taskMock->first();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Task not exist");

        $this->taskService->deleteTask($this->data['id'], $this->data['userId']);
    }

    public function test_delete_user_task_deletes_tasks():void
    {

        $taskMock = $this->mock(Task::class, function (MockInterface $mock){
            $mock->shouldReceive('first')->andReturn($this->task);
        });

        $taskMock->first();

        $this->taskService->deleteTask($this->task->id, $this->user->id);

        $this->assertDatabaseCount(Task::class, 0);
    }

    public function test_toggle_user_task_returns_exception_when_task_not_found():void
    {
        $request = new Request();
        $request['id'] = $this->task->id;
        $request['status'] = true;

        $taskMock = $this->mock(Task::class, function (MockInterface $mock){
            $mock->shouldReceive('first')->andReturnNull();
        });

        $taskMock->first();

        $toggledTask = $this->taskService->toggleTaskStatus($request);


        $this->assertTrue($toggledTask);
    }
}
