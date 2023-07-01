<?php

namespace Tests\Feature\Task;

use Domain\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PriorityTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_user_can_get_priorities(): void
    {

        $this->actingAs(User::factory()->create());
        $this->getJson(route('api.priorities'))
            ->assertStatus(200)
            ->assertJsonCount(4, 'data');
    }

    public function test_unauthorized_user_cannot_get_priorities(): void
    {

        $this->getJson(route('api.priorities'))
            ->assertStatus(401);
    }
}
