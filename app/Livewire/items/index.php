<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    // Listen for deleteConfirmed event from the browser
    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function getItemsProperty()
    {
        return Item::query()
            ->where('sku', 'like', "%{$this->search}%")
            ->orWhere('name', 'like', "%{$this->search}%")
            ->orderBy('name')
            ->paginate(10);
    }

    public function confirmDelete($id)
    {
        // 🔥 Simple Livewire v3 event dispatch (no toBrowser needed)
        $this->dispatch('confirm-delete', id: $id);
    }

    public function delete($id)
    {
        $item = Item::find($id);

        if ($item) {
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
