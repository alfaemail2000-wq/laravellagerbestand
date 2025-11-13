<?php

namespace App\Livewire\Items;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;
use Illuminate\Support\Facades\Response;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = ['deleteConfirmed' => 'delete'];

    public function getItemsProperty()
    {
        return Item::query()
            ->where(fn($q) => $q
                ->where('sku', 'like', "%{$this->search}%")
                ->orWhere('name', 'like', "%{$this->search}%"))
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

    /** 🧾 CSV-Export der Bestände */
    public function exportCsv()
    {
        $filename = 'bestand_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $items = Item::with('movements')->get();

        $rows = [];
        foreach ($items as $item) {
            $total = $item->totalStock();
            $stocks = $item->stockByLocation();

            if (empty($stocks)) {
                $rows[] = [
                    $item->sku,
                    $item->name,
                    '-',
                    0,
                    $total,
                ];
            } else {
                foreach ($stocks as $location => $qty) {
                    $rows[] = [
                        $item->sku,
                        $item->name,
                        $location,
                        $qty,
                        $total,
                    ];
                }
            }
        }

        // CSV erzeugen
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['SKU', 'Name', 'Location', 'Bestand', 'Gesamt']);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return Response::streamDownload(function() use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        return view('livewire.items.index', [
            'items' => $this->items,
        ]);
    }
}
