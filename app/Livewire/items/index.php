<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Damit beim Ändern der Suche die Seite auf 1 zurückspringt
    public function updatedSearch()
    {
        $this->resetPage();
    }

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function getItemsProperty()
    {
        $search = trim($this->search);

        return Item::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('sku', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10);
    }

    public function confirmDelete($id)
    {
        $this->dispatch('confirm-delete', id: $id);
    }

    public function delete($id)
    {
        if ($item = Item::find($id)) {
            $item->delete();
            session()->flash('success', '🗑️ Artikel wurde gelöscht.');
        }
    }

    public function render()
    {
        return view('livewire.items.index', [
            'items' => $this->items,
        ]);
    }
}
