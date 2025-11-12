<div class="p-6">
    <div class="flex justify-between mb-4">


        {{-- Search --}}
        <input type="text"
               wire:model.debounce.300ms="search"
               placeholder="Suche nach SKU oder Name..."
               class="border p-2 rounded w-1/3">

        {{-- New Item Button --}}
        <a href="{{ route('items.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">
            + Neuer Artikel
        </a>
    </div>

    {{-- Flash success message --}}
    @if (session('success'))
        <div class="mb-3 rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    {{-- Table --}}
    <table class="w-full border-collapse">
        <thead>
        <tr class="bg-gray-100 text-left">
            <th class="p-2">SKU</th>
            <th class="p-2">Name</th>
            <th class="p-2">Min. Bestand</th>
            <th class="p-2">Bestand</th>
            <th class="p-2">Status</th>
            <th class="p-2">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr class="border-b">
                <td class="p-2">{{ $item->sku }}</td>
                <td class="p-2">{{ $item->name }}</td>
                <td class="p-2">{{ $item->min_stock ?? '-' }}</td>
                <td class="p-2">{{ $item->totalStock() }}</td>
                <td class="p-2">
                    @if(!is_null($item->min_stock) && $item->totalStock() < $item->min_stock)
                        <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-sm">Low Stock</span>
                    @elseif(!is_null($item->target_stock) && $item->totalStock() >= $item->target_stock)
                        <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm">Ziel erreicht</span>
                    @else
                        <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm">OK</span>
                    @endif
                </td>
                <td class="p-2 space-x-2">
                    <a href="{{ route('items.show', $item) }}" class="text-blue-600">Details</a>
                    <a href="{{ route('items.edit', $item) }}" class="text-yellow-600">Bearbeiten</a>
                    <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600">
                        Löschen
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="mt-4">{{ $items->links() }}</div>
</div>

{{-- ✅ JS confirm dialog --}}
<script>
    window.addEventListener('confirm-delete', event => {
        if (confirm('❗ Möchtest du diesen Artikel wirklich löschen?')) {
            Livewire.dispatch('deleteConfirmed', { id: event.detail.id });
        }
    });
</script>
