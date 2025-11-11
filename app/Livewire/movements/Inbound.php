<?php


namespace App\Livewire\Movements;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Livewire\Component;

class Inbound extends Component
{
    public $item_id, $quantity, $note;
    public $location;

    public function mount()
    {
        $this->location = Location::where('name', 'Hauptlager')->first();
    }

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'quantity' => 'required|numeric|min:1',
        'note' => 'nullable|string',
    ];

    public function save()
    {
        $this->validate();
        Movement::create([
            'item_id' => $this->item_id,
            'type' => 'in',
            'to_location_id' => $this->location->id,
            'quantity' => $this->quantity,
            'note' => $this->note,
        ]);
        session()->flash('success', 'Eingang gebucht.');
        $this->reset(['item_id', 'quantity', 'note']);
    }

    public function render()
    {
        $items = Item::all();
        $movements = Movement::with('item')->where('type', 'in')->latest()->take(10)->get();

        return view('livewire.movements.inbound', compact('items', 'movements'));
    }
}
