<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['sku','name','min_stock','target_stock'];

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }

    /** Bestand an einer Location */
    public function stockFor(Location $loc): int
    {
        $in = $this->movements()
            ->where(fn ($q) => $q
                ->where(fn ($w) => $w->where('type','in')->where('to_location_id', $loc->id))
                ->orWhere(fn ($w) => $w->where('type','transfer')->where('to_location_id', $loc->id))
            )->sum('quantity');

        $out = $this->movements()
            ->where(fn ($q) => $q
                ->where(fn ($w) => $w->where('type','out')->where('from_location_id', $loc->id))
                ->orWhere(fn ($w) => $w->where('type','transfer')->where('from_location_id', $loc->id))
            )->sum('quantity');

        return (int) ($in - $out);
    }

    /** Gesamtbestand über alle Locations */
    public function totalStock(): int
    {
        $in = $this->movements()->whereIn('type', ['in','transfer'])
            ->sum('quantity');
        $out = $this->movements()->whereIn('type', ['out','transfer'])
            ->sum('quantity');

        return (int) ($in - $out);
    }

    /** Bestand pro Location (z. B. Hauptlager, Produktion, etc.) */
    public function stockByLocation(): array
    {
        $locations = \App\Models\Location::all();
        $result = [];

        foreach ($locations as $loc) {
            $result[$loc->name] = $this->stockFor($loc);
        }

        return $result;
    }

    public function isLow(): bool
    {
        return $this->totalStock() < (int) $this->min_stock;
    }

    /**
     * PRODUKTION: erzeugt das Fertigprodukt ("FINISHED-MODULIX").
     * - Verbraucht Materialien gemäß BOM aus $from (Produktion)
     * - Bucht fertige Produkte als Wareneingang nach $to
     */
    public function produce(int $quantity, Location $from, Location $to): void
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages(['quantity' => 'Menge muss >= 1 sein.']);
        }

        if ($this->sku !== 'FINISHED-MODULIX') {
            throw ValidationException::withMessages(['item' => 'Die Produktionsfunktion ist an das Fertigprodukt gebunden.']);
        }

        // Stückliste (pro 1 Modulix)
        $bom = [
            'WOOD-BUCHE-2CM' => 2,   // Bretter
            'SANDPAPER-P80'  => 1,   // Blatt
            'GLUE-WOOD-ML'   => 50,  // ml
            'PAINT-MIX-ML'   => 80,  // ml gesamt
            'SCREW-3CM'      => 8,   // Stück
            'BOX-HOLZKISTE'  => 1,   // Stück
        ];

        // Material-Items auflösen
        /** @var array<string, Item> $materials */
        $materials = Item::query()
            ->whereIn('sku', array_keys($bom))
            ->get()
            ->keyBy('sku')
            ->all();

        // Verfügbarkeitsprüfung
        foreach ($bom as $sku => $perUnit) {
            if (! isset($materials[$sku])) {
                throw ValidationException::withMessages(['bom' => "Material mit SKU {$sku} existiert nicht."]);
            }
            $need = $perUnit * $quantity;
            $have = $materials[$sku]->stockFor($from);
            if ($have < $need) {
                throw ValidationException::withMessages([
                    'stock' => "Nicht genug Bestand für {$materials[$sku]->name} ({$sku}) in {$from->name}: benötigt {$need}, vorhanden {$have}."
                ]);
            }
        }

        // Bewegungen atomar anlegen
        DB::transaction(function () use ($bom, $materials, $quantity, $from, $to) {
            // 1) Verbräuche (out) je Material
            foreach ($bom as $sku => $perUnit) {
                $mat = $materials[$sku];
                Movement::create([
                    'item_id'          => $mat->id,
                    'type'             => 'out',
                    'from_location_id' => $from->id,
                    'to_location_id'   => null,
                    'quantity'         => $perUnit * $quantity,
                    'note'             => "Verbrauch für Produktion Modulix x{$quantity}",
                ]);
            }

            // 2) Fertigprodukt Wareneingang
            Movement::create([
                'item_id'          => $this->id,
                'type'             => 'in',
                'from_location_id' => null,
                'to_location_id'   => $to->id,
                'quantity'         => $quantity,
                'note'             => 'Produktion abgeschlossen',
            ]);
        });
    }
}
