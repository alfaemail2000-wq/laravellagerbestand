<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;

class Inbound extends Component
{
    public $item_id;
    public $to_location_id;
    public $quantity;
    public $note;

    public $items;
    public $locations;

    public function mount()
    {
        $this->items = Item::all();
        $this->locations = Location::all();
    }

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'to_location_id' => 'required|exists:locations,id',
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable|string|max:255',
    ];

    public function submit()
    {
        $this->validate();

        $location = Location::find($this->to_location_id);

        // ✅ Nur Hauptlager zulassen
        if (!$location || strtolower($location->name) !== 'hauptlager') {
            $this->addError('to_location_id', 'Wareneingänge dürfen nur im Hauptlager gebucht werden.');
            return;
        }

        Movement::create([
            'item_id'          => $this->item_id,
            'type'             => 'in',
            'from_location_id' => null,
            'to_location_id'   => $this->to_location_id,
            'quantity'         => $this->quantity,
            'note'             => $this->note,
        ]);

        session()->flash('success', '✅ Wareneingang erfolgreich im Hauptlager gebucht.');

        $this->reset(['item_id', 'to_location_id', 'quantity', 'note']);
    }

    public function render()
    {
        $movements = Movement::with(['item', 'toLocation'])
            ->where('type', 'in')
            ->latest()
            ->take(20)
            ->get();

        // 📦 Bestände im Hauptlager berechnen (einfach per vorhandener Funktion)
        $hauptlager = Location::where('name', 'Hauptlager')->first();
        $items = Item::all();
        $hauptlagerBestand = [];

        if ($hauptlager) {
            foreach ($items as $item) {
                $hauptlagerBestand[$item->id] = $item->stockFor($hauptlager);
            }

            // 🔥 Alle Bewegungen pro Artikel fürs Hauptlager laden
            $hauptlagerBewegungen = Movement::where('to_location_id', $hauptlager->id)
                ->with('item')
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('item_id');
        }

        return view('livewire.movements.inbound', [
            'movements' => $movements,
            'items' => $items,
            'hauptlagerBestand' => $hauptlagerBestand,
            'hauptlagerBewegungen' => $hauptlagerBewegungen,
        ]);
    }
}
