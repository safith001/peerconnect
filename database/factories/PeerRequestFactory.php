<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PeerRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'status' => 'pending',
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'accepted']);
    }

    public function declined(): static
    {
        return $this->state(fn (array $attrs) => ['status' => 'declined']);
    }
}
