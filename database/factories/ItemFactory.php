<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        return [
            'sku'        => Str::upper(Str::slug($name)).'-'.str_pad((string) $this->faker->numberBetween(1,999), 3, '0', STR_PAD_LEFT),
            'name'       => ucfirst($name),
            'min_stock'  => $this->faker->numberBetween(0, 200),
            // optional: 'target_stock' => $this->faker->optional()->numberBetween(50, 500),
        ];
    }

    // Fixe States für die Modulix-Materialien
    public function woodBuche(): static
    {
        return $this->state(fn () => ['sku' => 'WOOD-BUCHE-2CM', 'name' => 'Holzbrett Buche 2cm', 'min_stock' => 100]);
    }

    public function sandpaper(): static
    {
        return $this->state(fn () => ['sku' => 'SANDPAPER-P80', 'name' => 'Schleifpapier P80', 'min_stock' => 50]);
    }

    public function glueMl(): static
    {
        return $this->state(fn () => ['sku' => 'GLUE-WOOD-ML', 'name' => 'Holzleim (ml)', 'min_stock' => 2500]);
    }

    public function paintMixMl(): static
    {
        return $this->state(fn () => ['sku' => 'PAINT-MIX-ML', 'name' => 'Lackfarben (ml gesamt)', 'min_stock' => 4000]);
    }

    public function screws(): static
    {
        return $this->state(fn () => ['sku' => 'SCREW-3CM', 'name' => 'Schraube 3cm (Stk.)', 'min_stock' => 400]);
    }

    public function box(): static
    {
        return $this->state(fn () => ['sku' => 'BOX-HOLZKISTE', 'name' => 'Holzkiste (Stk.)', 'min_stock' => 50]);
    }

    public function modulix(): static
    {
        return $this->state(fn () => ['sku' => 'FINISHED-MODULIX', 'name' => 'Holzbaukasten „Modulix“', 'min_stock' => 0]);
    }
}
