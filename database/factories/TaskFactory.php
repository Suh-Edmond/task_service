<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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

            'title' => fake()->name(),
            'description' => fake()->name(),
            'due_date' =>"2024/09/09",
            'status' => false,
            'user_id' => '3456349503459034534535'
        ];
    }
}
