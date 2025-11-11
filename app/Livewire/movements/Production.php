<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Validation\ValidationException;

class Production extends Component
{
    public $quantity;
    public $from_location_id;
    public $to_location_id;

    public $product;
    public $locations;

    public function mount()
    {
        $this->product = Item::where('sku', 'FINISHED-MODULIX')->first();
        $this->locations = Location::all();
    }

    protected $rules = [
        'quantity' => 'required|integer|min:1',
        'from_location_id' => 'required|exists:locations,id',
        'to_location_id' => 'required|exists:locations,id',
    ];

    public function produce()
    {
        $this->validate();

        try {
            $this->product->produce(
                $this->quantity,
                Location::findOrFail($this->from_location_id),
                Location::findOrFail($this->to_location_id)
            );
        } catch (ValidationException $e) {
            $this->addError('production', $e->getMessage());
            return;
        }

        session()->flash('success', 'Produktion erfolgreich gebucht.');
        $this->reset(['quantity']);
    }

    public function render()
    {
        return view('livewire.movements.production');
    }
}
