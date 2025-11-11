<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $items = Item::query()
            ->where('name', 'like', "%{$this->search}%")
            ->orWhere('sku', 'like', "%{$this->search}%")
            ->paginate(10);

        return view('livewire.items.index', compact('items'));
    }
}
