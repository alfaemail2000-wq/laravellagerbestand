<?php

namespace App\Livewire\Items;

use App\Models\Item;
use Livewire\Component;

class Show extends Component
{
    public Item $item;

    public function render()
    {
        return view('livewire.items.show');
    }
}
