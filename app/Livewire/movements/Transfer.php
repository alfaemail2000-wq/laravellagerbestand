<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;

class Transfer extends Component
{
    public $item_id;
    public $from_location_id;
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
        'from_location_id' => 'required|exists:locations,id|different:to_location_id',
        'to_location_id' => 'required|exists:locations,id|different:from_location_id',
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable|string|max:255',
    ];

    public function submit()
    {
        $this->validate();

        Movement::create([
            'item_id' => $this->item_id,
            'type' => 'transfer',
            'from_location_id' => $this->from_location_id,
            'to_location_id' => $this->to_location_id,
            'quantity' => $this->quantity,
            'note' => $this->note,
        ]);

        session()->flash('success', 'Transfer erfolgreich gebucht.');
        $this->reset(['item_id','from_location_id','to_location_id','quantity','note']);
    }

    public function render()
    {
        $movements = Movement::where('type','transfer')->latest()->take(20)->get();
        return view('livewire.movements.transfer', compact('movements'));
    }
}
