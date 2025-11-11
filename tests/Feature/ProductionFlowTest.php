<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class ProductionFlowTest extends TestCase
{
    use RefreshDatabase;

    protected Location $H;
    protected Location $P;
    protected Item $wood;
    protected Item $paper;
    protected Item $glue;
    protected Item $paint;
    protected Item $screw;
    protected Item $box;
    protected Item $mod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->H = Location::factory()->state(['name' => 'Hauptlager'])->create();
        $this->P = Location::factory()->state(['name' => 'Produktion'])->create();

        $this->wood  = Item::factory()->woodBuche()->create();
        $this->paper = Item::factory()->sandpaper()->create();
        $this->glue  = Item::factory()->glueMl()->create();
        $this->paint = Item::factory()->paintMixMl()->create();
        $this->screw = Item::factory()->screws()->create();
        $this->box   = Item::factory()->box()->create();
        $this->mod   = Item::factory()->modulix()->create();

        // Initiale Bestände in H
        Movement::factory()->inbound($this->H)->create(['item_id' => $this->wood->id,  'quantity' => 100]);
        Movement::factory()->inbound($this->H)->create(['item_id' => $this->paper->id, 'quantity' => 100]);
        Movement::factory()->inbound($this->H)->create(['item_id' => $this->glue->id,  'quantity' => 6000]);
        Movement::factory()->inbound($this->H)->create(['item_id' => $this->paint->id, 'quantity' => 6000]);
        Movement::factory()->inbound($this->H)->create(['item_id' => $this->screw->id, 'quantity' => 1000]);
        Movement::factory()->inbound($this->H)->create(['item_id' => $this->box->id,   'quantity' => 100]);

        // Puffer in P
        foreach ([[$this->wood, 50], [$this->paper, 50], [$this->glue, 3000], [$this->paint, 3000], [$this->screw, 500], [$this->box, 50]] as [$i, $qty]) {
            Movement::factory()->transfer($this->H, $this->P)->create(['item_id' => $i->id, 'quantity' => $qty]);
        }
    }

    public function test_produce_creates_outbound_materials_and_inbound_finished_goods(): void
    {
        // Produce 10 Modulix (BOM je 1):
        $this->mod->produce(10, $this->P, $this->H);

        // Materialien in P reduziert
        $this->assertSame(30,  $this->wood->fresh()->stockFor($this->P));   // 50 - (2*10) = 30
        $this->assertSame(40,  $this->paper->fresh()->stockFor($this->P));  // 50 - (1*10) = 40
        $this->assertSame(2000,$this->glue->fresh()->stockFor($this->P));   // 3000 - (50*10) = 2000
        $this->assertSame(2000,$this->paint->fresh()->stockFor($this->P));  // 3000 - (80*10) = 2000
        $this->assertSame(420, $this->screw->fresh()->stockFor($this->P));  // 500 - (8*10) = 420
        $this->assertSame(40,  $this->box->fresh()->stockFor($this->P));    // 50 - (1*10) = 40

        // Fertigprodukt in H eingelagert
        $this->assertSame(10, $this->mod->fresh()->stockFor($this->H));
        $this->assertSame(10, $this->mod->fresh()->totalStock());

        // Bewegungen entstanden
        $this->assertDatabaseHas('movements', [
            'item_id' => $this->mod->id, 'type' => 'in', 'to_location_id' => $this->H->id, 'quantity' => 10
        ]);
    }

    public function test_produce_throws_if_insufficient_material(): void
    {
        // P hat nur 50 Bretter, Produktion 30 bräuchte 60 Bretter
        $this->expectException(ValidationException::class);
        $this->mod->produce(30, $this->P, $this->H);
    }
}
