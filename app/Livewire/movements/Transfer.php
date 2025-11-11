<?php


namespace App\Livewire\Movements;

use App\Models\Item;
use App\Models\Location;
use App\Models\Movement;
use Livewire\Component;

class Transfer extends Component
{
    public $item_id, $from_location_id, $to_location_id, $quantity, $note;

    protected $rules = [
        'item_id' => 'required|exists:items,id',
        'from_location_id' => 'required|different:to_location_id|exists:locations,id',
        'to_location_id' => 'required|exists:locations,id',
        'quantity' => 'required|numeric|min:1',
        'note' => 'nullable|string',
    ];

    public function save()
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
        $this->reset(['item_id', 'from_location_id', 'to_location_id', 'quantity', 'note']);
    }

    public function render()
    {
        $items = Item::all();
        $locations = Location::all();
        $movements = Movement::with(['item', 'fromLocation', 'toLocation'])
            ->where('type', 'transfer')
            ->latest()->take(10)->get();

        return view('livewire.movements.transfer', compact('items', 'locations', 'movements'));
    }
}
