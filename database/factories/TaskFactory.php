<?php

namespace Database\Factories;

use App\Enum\TaskStatusEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use function Laravel\Prompts\text;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title(),
            'description' => fake()->text(50),
            'status' => TaskStatusEnum::TODO->value,
            'priority' => fake()->numberBetween(1, 5),
            'created_at' => now(),
            'user_id' => 1
        ];
    }
}
