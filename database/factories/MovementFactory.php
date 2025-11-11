<?php

namespace Database\Factories;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Database\Eloquent\Factories\Factory;

class MovementFactory extends Factory
{
    protected $model = Movement::class;

    public function definition(): array
    {
        return [
            'item_id'          => Item::inRandomOrder()->value('id') ?? Item::factory(),
            'type'             => 'in',
            'from_location_id' => null,
            'to_location_id'   => null,
            'quantity'         => $this->faker->numberBetween(1, 50),
            'note'             => $this->faker->optional()->sentence(),
        ];
    }

    public function inbound(?Location $to = null): static
    {
        return $this->state(function () use ($to) {
            $toId = $to?->id ?? Location::inRandomOrder()->value('id');
            return [
                'type' => 'in',
                'from_location_id' => null,
                'to_location_id'   => $toId,
            ];
        });
    }

    public function outbound(?Location $from = null): static
    {
        return $this->state(function () use ($from) {
            $fromId = $from?->id ?? Location::inRandomOrder()->value('id');
            return [
                'type' => 'out',
                'from_location_id' => $fromId,
                'to_location_id'   => null,
            ];
        });
    }

    public function transfer(?Location $from = null, ?Location $to = null): static
    {
        return $this->state(function () use ($from, $to) {
            $fromId = $from?->id ?? Location::inRandomOrder()->value('id');
            $toId   = $to?->id   ?? Location::whereKeyNot($fromId)->inRandomOrder()->value('id');
            return [
                'type' => 'transfer',
                'from_location_id' => $fromId,
                'to_location_id'   => $toId,
            ];
        });
    }
}
