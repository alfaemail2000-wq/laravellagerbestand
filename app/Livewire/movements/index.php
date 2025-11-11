<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Movement;

class Index extends Component
{
    public $type = '';

    public function render()
    {
        $movements = Movement::with(['item', 'fromLocation', 'toLocation'])
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->latest()
            ->get();

        return view('livewire.movements.index', [
            'movements' => $movements,
        ]);
    }
}
