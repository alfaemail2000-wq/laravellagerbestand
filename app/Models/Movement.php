<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id','type','from_location_id','to_location_id','quantity','note'
    ];

    public function item() { return $this->belongsTo(Item::class); }
    public function fromLocation() { return $this->belongsTo(Location::class, 'from_location_id'); }
    public function toLocation()   { return $this->belongsTo(Location::class, 'to_location_id'); }

    protected static function booted(): void
    {
        static::creating(function (Movement $m) {
            if (! in_array($m->type, ['in','out','transfer'], true)) {
                throw ValidationException::withMessages(['type' => 'Ungültiger Bewegungstyp.']);
            }
            if ($m->quantity < 1) {
                throw ValidationException::withMessages(['quantity' => 'Menge muss > 0 sein.']);
            }

            // Struktur-Check
            $ok =
                ($m->type === 'in'       && $m->to_location_id     && ! $m->from_location_id) ||
                ($m->type === 'out'      && $m->from_location_id   && ! $m->to_location_id) ||
                ($m->type === 'transfer' && $m->from_location_id   && $m->to_location_id && $m->from_location_id !== $m->to_location_id);

            if (! $ok) {
                throw ValidationException::withMessages(['type' => 'Kombination von Typ/Locations ungültig.']);
            }

            // Negativbestände bei out/transfer (Quelle) verhindern
            if (in_array($m->type, ['out','transfer'], true) && $m->from_location_id) {
                $current = $m->item->stockFor(Location::findOrFail($m->from_location_id));
                if ($current - $m->quantity < 0) {
                    throw ValidationException::withMessages(['quantity' => 'Bestand reicht nicht aus.']);
                }
            }
        });

        // Low-Stock-Mails können hier (created) ausgelöst werden, falls konfiguriert.
        // Siehe deine bestehende Notification-Implementierung.
        // 📬 Hier fügst du den Notification-Teil ein:
        static::created(function (Movement $movement) {
            $item = $movement->item;

            // LOW STOCK check
            if ($item->min_stock && $item->totalStock() < $item->min_stock) {
                \Illuminate\Support\Facades\Notification::route('mail', env('LOW_STOCK_MAIL_TO'))
                    ->notify(new \App\Notifications\LowStockNotification($item));
            }

            // TARGET STOCK check (für fertiges Produkt MD-001)
            if ($item->sku === 'MD-001') {
                $target = (int)(env('TARGET_STOCK', $item->target_stock ?? 0));
                $total = $item->totalStock();

                if ($total >= $target && ! cache("target_notified_{$item->id}")) {
                    \Illuminate\Support\Facades\Notification::route('mail', env('TARGET_STOCK_MAIL_TO'))
                        ->notify(new \App\Notifications\TargetStockReachedNotification($item));

                    // Zwischenspeichern, damit Mail nicht mehrfach kommt
                    cache(["target_notified_{$item->id}" => true], now()->addHours(12));
                }

                // Reset, wenn Bestand wieder unter Ziel fällt
                if ($total < $target) {
                    cache()->forget("target_notified_{$item->id}");
                }
            }
        });


    }
}
