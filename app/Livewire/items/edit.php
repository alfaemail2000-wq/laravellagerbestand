<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Illuminate\Validation\Rule;

class Edit extends Component
{
    public Item $item;

    public $sku;
    public $name;
    public $min_stock;

    public function mount(Item $item)
    {
        $this->item = $item;
        $this->sku = $item->sku;
        $this->name = $item->name;
        $this->min_stock = $item->min_stock;
    }

    protected function rules()
    {
        return [
            'sku' => [
                'required',
                'string',
                'max:255',
                // make SKU unique except for this item
                Rule::unique('items', 'sku')->ignore($this->item->id),
            ],
            'name' => 'required|string|max:255',
            'min_stock' => 'nullable|numeric|min:0',
        ];
    }

    public function update()
    {
        $this->validate();

        $this->item->update([
            'sku' => $this->sku,
            'name' => $this->name,
            'min_stock' => $this->min_stock,
        ]);

        session()->flash('success', '✅ Artikel erfolgreich aktualisiert.');

        return redirect()->route('items.index');
    }

    public function render()
    {
        return view('livewire.items.edit');
    }
}
