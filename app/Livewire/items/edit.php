<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;

class Edit extends Component
{
    public Item $item;

    protected $rules = [
        'item.sku' => 'required',
        'item.name' => 'required',
        'item.min_stock' => 'nullable|numeric|min:0',
    ];

    public function save()
    {
        $this->validate();
        $this->item->save();
        session()->flash('success', 'Artikel aktualisiert.');
        return redirect()->route('items.index');
    }

    public function render() { return view('livewire.items.edit'); }
}
