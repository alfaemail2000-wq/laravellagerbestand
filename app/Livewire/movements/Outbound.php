<?php

namespace App\Livewire\Movements;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Livewire\Component;

class Outbound extends Component
{
    public $item_id, $from_location_id, $quantity, $note;

    public $items, $locations;

    public function mount()
    {
        $this->items = Item::all();
        $this->locations = Location::all();
    }

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'from_location_id' => 'required|exists:locations,id',
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable|string|max:255',
    ];

    public function submit()
    {
        $this->validate();

        $item = Item::find($this->item_id);
        $location = Location::find($this->from_location_id);

        // 1️⃣ Bestand prüfen
        $currentStock = $item->stockByLocation()[$location->name] ?? 0;

        if ($this->quantity > $currentStock) {
            $this->addError('quantity', 'Nicht genug Bestand im ausgewählten Lager.');
            return;
        }

        // 2️⃣ Logik: Fertigprodukt MUSS aus Hauptlager
        if ($item->sku === 'MD-001' && $location->name !== 'Hauptlager') {
            $this->addError('from_location_id', 'Fertigprodukte dürfen nur aus dem Hauptlager gebucht werden.');
            return;
        }

        // 3️⃣ Logik: Material MUSS aus Produktion
        if ($item->sku !== 'MD-001' && $location->name !== 'Produktion') {
            $this->addError('from_location_id', 'Materialverbrauch erfolgt nur aus dem Produktionslager.');
            return;
        }

        // 4️⃣ Bewegung buchen
        Movement::create([
            'item_id' => $item->id,
            'type' => 'out',
            'from_location_id' => $location->id,
            'quantity' => $this->quantity,
            'note' => $this->note,
        ]);

        session()->flash('success', 'Warenausgang erfolgreich gebucht.');

        $this->reset(['item_id', 'from_location_id', 'quantity', 'note']);
    }

    public function render()
    {
        $movements = Movement::with(['item', 'fromLocation'])
            ->where('type', 'out')
            ->latest()
            ->take(20)
            ->get();

        return view('livewire.movements.outbound', [
            'items' => $this->items,
            'locations' => $this->locations,
            'movements' => $movements,
        ]);
    }
}
