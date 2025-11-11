<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;

class Create extends Component
{
    public $sku, $name, $min_stock;

    protected $rules = [
        'sku' => 'required|unique:items,sku',
        'name' => 'required|string|max:255',
        'min_stock' => 'nullable|numeric|min:0',
    ];

    public function save()
    {
        $this->validate();
        Item::create([
            'sku' => $this->sku,
            'name' => $this->name,
            'min_stock' => $this->min_stock,
        ]);
        session()->flash('success', 'Artikel erfolgreich erstellt!');
        return redirect()->route('items.index');
    }

    public function render() { return view('livewire.items.create'); }
}
