<?php

namespace Tests\Feature\Task;

use Domain\Task\Models\Category;
use Domain\Task\Models\Priority;
use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_add_new_task(): void
    {
        $this->actingAs($this->user);

        $this->assertEquals(0, Task::count());

        $this->postJson(route('api.tasks.store'), [
            'name' => 'my first task',
            'description' => 'blablablab albabalablab blabal',
            'priority_id' => Priority::first()->id,
            'categories' => 'Work,Project 1'
        ])->assertStatus(201);

        $this->assertEquals(1, Task::count());
        $this->assertEquals(2, Category::count());
        $this->assertEquals('Project 1', $this->user->fresh()->categories()->first()->name);
        $this->assertEquals('my first task', Task::first()->name);
    }

    public function test_user_cannot_add_new_task_missing_parameter(): void
    {
        $this->actingAs($this->user);

        $this->postJson(route('api.tasks.store'), [
        ])->assertStatus(422);
    }

    public function test_unauthorized_user_cannot_add_new_task(): void
    {

        $this->postJson(route('api.tasks.store'), [
        ])->assertStatus(401);
    }

    public function test_user_can_get_all_tasks(): void
    {
        $tasks = Task::factory(10)->create(
            ['user_id' => $this->user->id]
        );

        $category = Category::factory()->create();

        $category->users()->syncWithoutDetaching($this->user->id);

        $category->tasks()->syncWithoutDetaching($tasks->get(0)->id);

        $this->actingAs($this->user);


        $response = $this->getJson(route('api.tasks.index'));

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');

        $this->assertCount(1, $response->json()['data'][0]['categories']);

    }

    public function test_user_can_get_all_tasks_with_filter(): void
    {
        $task1 = Task::factory()->create(
            [
                'user_id' => $this->user->id,
                'priority_id' => 1,
                'name' => 'task 1 blabla',
                'description' => 'task 1 description'
            ]
        );

        Task::factory()->create(
            [
                'user_id' => $this->user->id,
                'priority_id' => 2,
                'completed' => true
            ]
        );

        $category = Category::factory()->create();

        $category->users()->syncWithoutDetaching($this->user->id);

        $category->tasks()->syncWithoutDetaching($task1->id);

        $this->actingAs($this->user);

        $response = $this->getJson(route('api.tasks.index') . '?filter[priority_id]=1')
            ->assertStatus(200);

        $this->assertCount(1, $response->json()['data']);

    }

    public function test_user_can_get_all_tasks_with_filter_2(): void
    {
        $task1 = Task::factory()->create(
            [
                'user_id' => $this->user->id,
                'priority_id' => 1,
                'name' => 'task 1 blabla',
                'description' => 'task 1 description'
            ]
        );

        Task::factory()->create(
            [
                'user_id' => $this->user->id,
                'priority_id' => 2,
                'completed' => true
            ]
        );

        $category = Category::factory()->create();

        $category->users()->syncWithoutDetaching($this->user->id);

        $category->tasks()->syncWithoutDetaching($task1->id);

        $this->actingAs($this->user);

        $response = $this->getJson(route('api.tasks.index') . '?filter[category]=1&filter[name]=task&filter[description]=descr');

        $this->assertCount(1, $response->json()['data']);

    }

    public function test_user_can_delete_task(): void
    {
        $this->actingAs($this->user);

        $tasks = Task::factory(10)->create(
            ['user_id' => $this->user->id]
        );

        $this->assertEquals(10, Task::count());

        $this->deleteJson(route('api.tasks.delete', ['task' => $tasks->get(0)->id]))
            ->assertStatus(204);

        $this->assertEquals(9, Task::count());

    }

    public function test_user_cannot_mark_task_complete_from_another_user(): void
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create();

        $this->postJson(route('api.tasks.complete', ['task' => $task]))
            ->assertStatus(403);

    }

    public function test_user_can_mark_task_as_pending_from_another_user(): void
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create([
            'completed' => true
        ]);

        $this->postJson(route('api.tasks.pending', ['task' => $task]))
            ->assertStatus(403);

    }

    public function test_user_can_mark_task_complete(): void
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->assertFalse($task->completed);

        $this->postJson(route('api.tasks.complete', ['task' => $task]))
            ->assertStatus(200);

        $this->assertTrue($task->fresh()->completed);

    }

    public function test_user_can_mark_task_pending(): void
    {
        $this->actingAs($this->user);

        $task = Task::factory()->create([
            'user_id' => $this->user->id,
            'completed' => true
        ]);

        $this->assertTrue($task->completed);

        $this->postJson(route('api.tasks.pending', ['task' => $task]))
            ->assertStatus(200);

        $this->assertFalse($task->fresh()->completed);

    }
}
