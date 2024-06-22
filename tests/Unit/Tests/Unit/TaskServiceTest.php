<?php

namespace Tests\Unit;

use App\Exceptions\ResourceNotFoundException;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Request;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use DatabaseMigrations;

    private TaskService $taskService;
    private Request  $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->taskService = new TaskService();

        $this->data = new Request(['userId' => '3456349503459034534535','title'=> 'Test',
            'description' => 'description', 'status' => true, 'due_date'=> "2023/09/09", 'id' => "3456349503459034534535"]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function test_create_return_model_not_found(): void
    {
        $userMock = $this->getMockBuilder(User::class)->addMethods(['firstOrFail'])->getMock();

        $userMock->expects($this->once())->method('firstOrFail')->will($this->returnValue(null));

        $userMock->firstOrFail();

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\User] 3456349503459034534535");

        $this->taskService->createTask($this->data);
    }

    public function test_create_return_success_after_created_task(): void
    {
        $user = User::factory([
            'email'  => 'email@gmailcc.om',
            'name'   => 'Test',
            'password'=>'Summer1343',
            'id' => "3456349503459034534535"
        ])->create();

        $userMock = $this->getMockBuilder(User::class)->addMethods(['findOrFail'])->getMock();

        $userMock->expects($this->once())->method('findOrFail')->willReturn($user);

        $userMock->findOrFail($this->data['userId']);

        $this->taskService->createTask($this->data);

        $this->assertDatabaseCount(Task::class, 1);
        $this->assertDatabaseHas(Task::class, ['title' => $this->data['title']]);
        $this->assertDatabaseHas(Task::class, ['description' => $this->data['description']]);
        $this->assertDatabaseHas(Task::class, ['due_date' => $this->data['due_date']]);
        $this->assertDatabaseHas(Task::class, ['status' => $this->data['status']]);
    }

    public function test_update_return_snot_found_when_task_not_found(): void
    {

        $taskMock = $this->getMockBuilder(Task::class)->addMethods(['findOrFail'])->getMock();

        $taskMock->expects($this->once())->method('findOrFail')->willReturn(null);

        $taskMock->findOrFail($this->data['id']);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\Task] 3456349503459034534535");

        $this->taskService->updateTask($this->data, $this->data['id']);
    }

    public function test_update_return_success_after_update_task(): void
    {
        $user = User::factory()->create();

        $task = Task::factory([
            'title'  => $this->data['title'],
            'description'   => $this->data['description'],
            'status'=>$this->data['status'],
            'due_date' => $this->data['due_date'],
            'id' => $this->data['id'],
            'user_id' => $user->id
        ])->create();

        $taskMock = $this->getMockBuilder(Task::class)->addMethods(['findOrFail'])->getMock();

        $taskMock->expects($this->once())->method('findOrFail')->willReturn($task);

        $taskMock->findOrFail($this->data['id']);

        $this->taskService->updateTask($this->data, $this->data['id']);

        $this->assertDatabaseCount(Task::class, 1);
        $this->assertDatabaseHas(Task::class, ['title' => $this->data['title']]);
        $this->assertDatabaseHas(Task::class, ['description' => $this->data['description']]);
        $this->assertDatabaseHas(Task::class, ['due_date' => $this->data['due_date']]);
        $this->assertDatabaseHas(Task::class, ['status' => $this->data['status']]);
    }

    public function test_fetch_user_tasks_returns_not_found():void
    {



        $userMock = $this->getMockBuilder(User::class)->addMethods(['findOrFail'])->getMock();

        $userMock->expects($this->once())->method('findOrFail')->willReturn(null);

        $userMock->findOrFail($this->data['userId']);

        $this->expectException(ModelNotFoundException::class);
        $this->expectExceptionMessage("No query results for model [App\Models\User] 3456349503459034534535");

        $this->taskService->fetchTasks($this->data['id']);
    }

    public function test_fetch_user_tasks_returns_all_user_task():void
    {

        $user = User::factory([
            'email'  => 'email@gmailcc.om',
            'name'   => 'Test',
            'password'=>'Summer1343',
            'id' => "3456349503459034534535"
        ])->create();

          Task::factory([
            'title'  => $this->data['title'],
            'description'   => $this->data['description'],
            'status'=>$this->data['status'],
            'due_date' => $this->data['due_date'],
            'id' => $this->data['id'],
            'user_id' => $user->id
        ])->create();


        $userMock = $this->getMockBuilder(User::class)->addMethods(['findOrFail'])->getMock();

        $userMock->expects($this->once())->method('findOrFail')->willReturn($user);

        $userMock->findOrFail($this->data['userId']);

        $response = $this->taskService->fetchTasks($this->data['id']);

        $this->assertEquals(1,count($response));
    }


    public function test_delete_user_task_returns_exception():void
    {

        $taskMock = $this->getMockBuilder(Task::class)->addMethods(['find'])->getMock();

        $taskMock->expects($this->once())->method('find')->willReturn(null);

        $taskMock->find();

        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Task not exist");

        $this->taskService->deleteTask($this->data['id'], $this->data['userId']);
    }

    public function test_delete_user_task_deletes_tasks():void
    {
        $user = User::factory([
            'email'  => 'email@gmailcc.om',
            'name'   => 'Test',
            'password'=>'Summer1343',
            'id' => "3456349503459034534535"
        ])->create();

        $task = Task::factory([
            'title'  => $this->data['title'],
            'description'   => $this->data['description'],
            'status'=>$this->data['status'],
            'due_date' => $this->data['due_date'],
            'id' => $this->data['id'],
            'user_id' => $user->id
        ])->create();

        $taskMock = $this->getMockBuilder(Task::class)->addMethods(['find'])->getMock();

        $taskMock->expects($this->once())->method('find')->willReturn($task);

        $taskMock->find();

        $this->taskService->deleteTask($this->data['id'], $this->data['userId']);

        $this->assertDatabaseCount(Task::class, 0);
    }
}
