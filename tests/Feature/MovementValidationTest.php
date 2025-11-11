<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class MovementValidationTest extends TestCase
{
    use RefreshDatabase;

    protected Location $H;
    protected Location $P;
    protected Item $wood;

    protected function setUp(): void
    {
        parent::setUp();
        $this->H = Location::factory()->state(['name' => 'Hauptlager'])->create();
        $this->P = Location::factory()->state(['name' => 'Produktion'])->create();

        $this->wood = Item::factory()->woodBuche()->create();

        // Startbestand ins Hauptlager
        Movement::factory()->inbound($this->H)->create([
            'item_id' => $this->wood->id, 'quantity' => 100,
        ]);
    }

    public function test_inbound_requires_only_to_location(): void
    {
        $m = Movement::create([
            'item_id' => $this->wood->id,
            'type' => 'in',
            'from_location_id' => null,
            'to_location_id' => $this->H->id,
            'quantity' => 10,
        ]);

        $this->assertDatabaseHas('movements', ['id' => $m->id, 'type' => 'in']);
    }

    public function test_outbound_requires_only_from_location(): void
    {
        $m = Movement::create([
            'item_id' => $this->wood->id,
            'type' => 'out',
            'from_location_id' => $this->H->id,
            'to_location_id' => null,
            'quantity' => 5,
        ]);

        $this->assertDatabaseHas('movements', ['id' => $m->id, 'type' => 'out']);
    }

    public function test_transfer_requires_both_locations_and_not_equal(): void
    {
        $m = Movement::create([
            'item_id' => $this->wood->id,
            'type' => 'transfer',
            'from_location_id' => $this->H->id,
            'to_location_id' => $this->P->id,
            'quantity' => 20,
        ]);

        $this->assertDatabaseHas('movements', ['id' => $m->id, 'type' => 'transfer']);
    }

    public function test_invalid_combination_throws_validation_exception(): void
    {
        $this->expectException(ValidationException::class);

        Movement::create([
            'item_id' => $this->wood->id,
            'type' => 'in',
            'from_location_id' => $this->H->id, // ungültig
            'to_location_id' => $this->P->id,
            'quantity' => 1,
        ]);
    }

    public function test_negative_stock_is_prevented_on_outbound(): void
    {
        $this->expectException(ValidationException::class);

        // H hat 100; P hat 0 → aus P darf nicht entnommen werden
        Movement::create([
            'item_id' => $this->wood->id,
            'type' => 'out',
            'from_location_id' => $this->P->id,
            'to_location_id' => null,
            'quantity' => 1,
        ]);
    }
}
