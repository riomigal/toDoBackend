<?php

namespace Database\Factories;

use Domain\Task\Models\Category;
use Domain\Task\Models\Priority;
use Domain\Task\Models\Task;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\User\Models\User>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'user_id' => User::factory()->create()->id,
            'priority_id' => Priority::factory()->create()->id,
            'completed' => false
        ];
    }
    
}
