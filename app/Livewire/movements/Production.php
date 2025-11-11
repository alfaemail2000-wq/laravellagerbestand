<?php

namespace App\Livewire\Movements;

use App\Models\Item;
use App\Models\Location;
use Livewire\Component;

class Production extends Component
{
    public $quantity = 1;
    public $from_location_id;
    public $to_location_id;

    protected $rules = [
        'quantity' => 'required|numeric|min:1',
        'from_location_id' => 'required|exists:locations,id',
        'to_location_id' => 'required|exists:locations,id|different:from_location_id',
    ];

    public function save()
    {
        $this->validate();

        // Beispiel: Fertigprodukt mit SKU = 'MODULIX'
        $product = Item::where('sku', 'MODULIX')->firstOrFail();
        $product->produce(
            quantity: $this->quantity,
            from: Location::findOrFail($this->from_location_id),
            to: Location::findOrFail($this->to_location_id)
        );

        session()->flash('success', 'Produktion erfolgreich verbucht.');
        $this->reset(['quantity']);
    }

    public function render()
    {
        $locations = Location::all();
        return view('livewire.movements.production', compact('locations'));
    }
}
