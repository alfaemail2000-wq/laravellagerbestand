<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockCalculationTest extends TestCase
{
    use RefreshDatabase;

    public function test_stock_by_location_and_total_changes_correctly(): void
    {
        $H = Location::factory()->state(['name' => 'Hauptlager'])->create();
        $P = Location::factory()->state(['name' => 'Produktion'])->create();
        $wood = Item::factory()->woodBuche()->create();

        // +100 in H
        Movement::factory()->inbound($H)->create(['item_id' => $wood->id, 'quantity' => 100]);
        $this->assertSame(100, $wood->fresh()->stockFor($H));
        $this->assertSame(0, $wood->fresh()->stockFor($P));
        $this->assertSame(100, $wood->fresh()->totalStock());

        // Transfer 30 H -> P
        Movement::factory()->transfer($H, $P)->create(['item_id' => $wood->id, 'quantity' => 30]);
        $this->assertSame(70, $wood->fresh()->stockFor($H));
        $this->assertSame(30, $wood->fresh()->stockFor($P));
        $this->assertSame(100, $wood->fresh()->totalStock());

        // Outbound 10 aus P
        Movement::factory()->outbound($P)->create(['item_id' => $wood->id, 'quantity' => 10]);
        $this->assertSame(70, $wood->fresh()->stockFor($H));
        $this->assertSame(20, $wood->fresh()->stockFor($P));
        $this->assertSame(90, $wood->fresh()->totalStock());
    }

    public function test_is_low_threshold_flag(): void
    {
        $H = Location::factory()->state(['name' => 'Hauptlager'])->create();
        $item = Item::factory()->state(['sku' => 'X', 'name' => 'Test', 'min_stock' => 50])->create();

        Movement::factory()->inbound($H)->create(['item_id' => $item->id, 'quantity' => 60]);
        $this->assertFalse($item->fresh()->isLow());

        Movement::factory()->outbound($H)->create(['item_id' => $item->id, 'quantity' => 15]);
        $this->assertTrue($item->fresh()->isLow()); // 45 < 50
    }
}
