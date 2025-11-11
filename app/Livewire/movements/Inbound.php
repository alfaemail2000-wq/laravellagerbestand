<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Illuminate\Validation\ValidationException;

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

        Movement::create([
            'item_id' => $this->item_id,
            'type' => 'in',
            'from_location_id' => null,
            'to_location_id' => $this->to_location_id,
            'quantity' => $this->quantity,
            'note' => $this->note,
        ]);

        session()->flash('success', 'Wareneingang erfolgreich gebucht.');

        $this->reset(['item_id','to_location_id','quantity','note']);
    }

    public function render()
    {
        $movements = Movement::where('type', 'in')->latest()->take(20)->get();
        return view('livewire.movements.inbound', compact('movements'));
    }
}
