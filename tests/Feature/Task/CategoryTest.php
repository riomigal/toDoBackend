<?php

namespace Tests\Feature\Task;

use Domain\Task\Models\Category;
use Domain\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_add_new_category_missing_parameter_fails(): void
    {
        $this->actingAs($this->user);

        $this->postJson(route('api.categories.store'), [])
            ->assertStatus(422);
    }

    public function test_can_add_new_category(): void
    {
        $this->actingAs($this->user);

        $this->assertEquals(0, Category::count());

        $this->postJson(route('api.categories.store'), ['name' => 'Work'])
            ->assertStatus(201);

        $this->assertEquals(1, Category::count());
    }

    public function test_doesnt_add_duplicate_category(): void
    {
        $this->actingAs($this->user);

        $this->assertEquals(0, Category::count());

        $this->postJson(route('api.categories.store'), ['name' => 'Work'])
            ->assertStatus(201);

        $this->assertEquals(1, Category::count());

        $this->postJson(route('api.categories.store'), ['name' => 'Work'])
            ->assertStatus(201);

        $this->assertEquals(1, Category::count());
    }

    public function test_can_delete_category(): void
    {
        $this->actingAs($this->user);

        $this->assertEquals(0, Category::count());

        $this->postJson(route('api.categories.store'), ['name' => 'Work'])
            ->assertStatus(201);

        $this->assertEquals(1, Category::count());

        $category = Category::first();
        $this->deleteJson(route('api.categories.delete', ['category' => $category->id]))
            ->assertStatus(204);

        $this->assertEquals(0, Category::count());
    }

    public function test_can_delete_category_but_only_detaches_it(): void
    {
        $this->actingAs($this->user);

        $this->assertEquals(0, Category::count());
        $this->assertEquals(0, DB::table('category_user')->count());

        $this->postJson(route('api.categories.store'), ['name' => 'Work'])
            ->assertStatus(201);

        $this->assertEquals(1, Category::count());
        $this->assertEquals(1, DB::table('category_user')->count());

        $this->actingAs(User::factory()->create());

        $this->postJson(route('api.categories.store'), ['name' => 'Work'])
            ->assertStatus(201);

        $this->assertEquals(1, Category::count());
        $this->assertEquals(2, DB::table('category_user')->count());

        $category = Category::first();
        $this->deleteJson(route('api.categories.delete', ['category' => $category->id]))
            ->assertStatus(204);

        $this->assertEquals(1, DB::table('category_user')->count());
        $this->assertEquals(1, Category::count());
    }

    public function test_can_retrieve_categories(): void
    {
        $this->actingAs($this->user);

        $this->user->categories()->sync(Category::factory(10)->create()->pluck('id')->toArray());

        $this->getJson(route('api.categories.index'))
            ->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    public function test_cannot_retrieve_categories_from_other_user(): void
    {
        $this->actingAs($this->user);

        $this->user->categories()->sync(Category::factory(10)->create()->pluck('id')->toArray());

        $this->getJson(route('api.categories.index'))
            ->assertStatus(200)
            ->assertJsonCount(10, 'data');

        $this->actingAs(User::factory()->create());

        $this->getJson(route('api.categories.index'))
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

}
