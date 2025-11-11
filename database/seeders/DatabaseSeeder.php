<?php
/*
namespace Database\Seeders;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1) Fixe Lager
            $haupt = Location::factory()->hauptlager()->create();
            $prod  = Location::factory()->produktion()->create();

            // 2) Fixe Items (Materialien + Fertigprodukt) via Factory-States
            $wood   = Item::factory()->woodBuche()->create();
            $paper  = Item::factory()->sandpaper()->create();
            $glue   = Item::factory()->glueMl()->create();
            $paint  = Item::factory()->paintMixMl()->create();
            $screw  = Item::factory()->screws()->create();
            $box    = Item::factory()->box()->create();
            $mod    = Item::factory()->modulix()->create();


// 3) Anfangsbestände (Wareneingang ins Hauptlager)
            Movement::factory()->inbound($haupt)->create(['item_id' => $wood->id,  'quantity' => 500,  'note' => 'Initiale Lieferung Buche']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $paper->id, 'quantity' => 300,  'note' => 'Initiale Lieferung Schleifpapier']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $glue->id,  'quantity' => 10000,'note' => 'Holzleim in ml']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $paint->id, 'quantity' => 8000, 'note' => 'Lackfarben gesamt ml']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $screw->id, 'quantity' => 2000, 'note' => 'Schrauben 3cm']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $box->id,   'quantity' => 500,  'note' => 'Holzkisten']);

// 4) Transfer für Produktionsstart
            foreach ([[$wood, 200], [$paper, 120], [$glue, 4000], [$paint, 2000], [$screw, 800], [$box, 200]] as [$item, $qty]) {
                Movement::factory()->transfer($haupt, $prod)->create([
                    'item_id'  => $item->id,
                    'quantity' => $qty,
                    'note'     => 'Startpuffer Produktion',
                ]);
            }

            // 5) Beispiel-Produktion (z. B. 25 Stück)
            //    Verbräuche aus Produktion + Wareneingang des Fertigprodukts im Produktionslager
            $mod->produce(
                quantity: 25,
                from: $prod,
                to: $haupt // fertige Ware geht i. d. R. zurück ins Hauptlager / Versand
            );
        });
    }
}*/


namespace Database\Seeders;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            // 1) Fixe Lager
            $haupt = Location::factory()->hauptlager()->create();
            $prod = Location::factory()->produktion()->create();

            // 2) Fixe Items (Materialien + Fertigprodukt) via Factory-States
            $wood = Item::factory()->woodBuche()->create();
            $paper = Item::factory()->sandpaper()->create();
            $glue = Item::factory()->glueMl()->create();
            $paint = Item::factory()->paintMixMl()->create();
            $screw = Item::factory()->screws()->create();
            $box = Item::factory()->box()->create();

            // Hier mit Zielbestand für das fertige Produkt
            $mod = Item::factory()->modulix()->create(['target_stock' => 20]);

            // 3) Anfangsbestände (Wareneingang ins Hauptlager)
            Movement::factory()->inbound($haupt)->create(['item_id' => $wood->id, 'quantity' => 500, 'note' => 'Initiale Lieferung Buche']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $paper->id, 'quantity' => 300, 'note' => 'Initiale Lieferung Schleifpapier']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $glue->id, 'quantity' => 10000, 'note' => 'Holzleim in ml']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $paint->id, 'quantity' => 8000, 'note' => 'Lackfarben gesamt ml']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $screw->id, 'quantity' => 2000, 'note' => 'Schrauben 3cm']);
            Movement::factory()->inbound($haupt)->create(['item_id' => $box->id, 'quantity' => 500, 'note' => 'Holzkisten']);

            // 4) Transfer für Produktionsstart
            foreach ([[$wood, 200], [$paper, 120], [$glue, 4000], [$paint, 2000], [$screw, 800], [$box, 200]] as [$item, $qty]) {
                Movement::factory()->transfer($haupt, $prod)->create([
                    'item_id' => $item->id,
                    'quantity' => $qty,
                    'note' => 'Startpuffer Produktion',
                ]);
            }

            // 5) Beispiel-Produktion (z. B. 25 Stück)
            $mod->produce(
                quantity: 25,
                from: $prod,
                to: $haupt // fertige Ware geht i. d. R. zurück ins Hauptlager / Versand
            );
        });
    }
}

