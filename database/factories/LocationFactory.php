<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Hauptlager', 'Produktion', 'Zwischenlager', 'Versand'
            ]),
        ];
    }

    public function hauptlager(): static
    {
        return $this->state(fn () => ['name' => 'Hauptlager']);
    }

    public function produktion(): static
    {
        return $this->state(fn () => ['name' => 'Produktion']);
    }
}
