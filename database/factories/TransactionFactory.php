<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transactions>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userIds = User::pluck('id');

        return [
            'date_time' => fake()->dateTime('now'),
            'description' => fake()->sentence(),
            'debit' => fake()->randomElement([100, 1000]),
            'credit' => fake()->randomElement([100, 1000]),
            'tag' => fake()->randomElement(['food', 'gym', 'others', 'bills', 'share']),
            'status' => fake()->randomElement(['pending', 'completed']),
            'channel' => fake()->randomElement(['esewa']),
            'user_id' => $userIds->isNotEmpty() ? fake()->randomElement($userIds->toArray()) : null,
        ];
    }
}
