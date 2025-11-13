<div class="p-6">
    <!-- 🔹 Navigation Tabs -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('items.index') }}"
           class="px-4 py-2 rounded {{ request()->routeIs('items.index') ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
            📦 Artikelübersicht
        </a>

        <a href="{{ route('movements.inbound') }}"
           class="px-4 py-2 rounded {{ request()->routeIs('movements.inbound') ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
            ⬅️ Wareneingang
        </a>

        <a href="{{ route('movements.outbound') }}"
           class="px-4 py-2 rounded {{ request()->routeIs('movements.outbound') ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
            ➡️ Warenausgang
        </a>

        <a href="{{ route('movements.transfer') }}"
           class="px-4 py-2 rounded {{ request()->routeIs('movements.transfer') ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
            🔁 Transfer
        </a>

        <a href="{{ route('movements.production') }}"
           class="px-4 py-2 rounded {{ request()->routeIs('movements.production') ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">
            🏭 Produktion
        </a>
    </div>

    <!-- 🔹 Suche & Aktionen -->
    <div class="flex justify-between mb-4 flex-wrap gap-2">
        <input type="text"
               wire:model.live="search"
               placeholder="Suche nach SKU oder Name..."
               class="border p-2 rounded w-1/3 min-w-[200px]">

        <div class="flex gap-2">
            <button wire:click="exportCsv"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                📄 CSV exportieren
            </button>

            <a href="{{ route('items.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Neuer Artikel
            </a>
        </div>
    </div>

    <!-- ✅ Erfolgsmeldung -->
    @if (session('success'))
        <div class="mb-3 rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <!-- 📦 Tabelle -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">SKU</th>
                <th class="p-2">Name</th>
                <th class="p-2">Min. Bestand</th>
                <th class="p-2">Gesamt</th>
                <th class="p-2">Hauptlager</th>
                <th class="p-2">Produktion</th>
                <th class="p-2">Target Stock</th>
                <th class="p-2">Status</th>
                <th class="p-2">Aktionen</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                @php
                    $stocks = $item->stockByLocation();
                @endphp
                <tr class="border-b">
                    <td class="p-2">{{ $item->sku }}</td>
                    <td class="p-2">{{ $item->name }}</td>
                    <td class="p-2">{{ $item->min_stock ?? '-' }}</td>
                    <td class="p-2 font-semibold">{{ $item->totalStock() }}</td>
                    <td class="p-2">{{ $stocks['Hauptlager'] ?? 0 }}</td>
                    <td class="p-2">{{ $stocks['Produktion'] ?? 0 }}</td>
                    <td class="p-2">{{ $item->target_stock ?? '-' }}</td>

                    <!-- Status -->
                    <td class="p-2">
                        @if(!is_null($item->min_stock) && $item->totalStock() < $item->min_stock)
                            <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-sm">Low Stock</span>
                        @elseif(!is_null($item->target_stock) && $item->totalStock() >= $item->target_stock)
                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm">Ziel erreicht</span>
                        @else
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm">OK</span>
                        @endif
                    </td>

                    <!-- Aktionen -->
                    <td class="p-2 flex gap-2">
                        <a href="{{ route('items.show', $item) }}" class="text-blue-600 hover:text-blue-800" title="Details">
                            <x-heroicon-o-eye class="h-5 w-5"/>
                        </a>

                        <a href="{{ route('items.edit', $item) }}" class="text-yellow-600 hover:text-yellow-800" title="Bearbeiten">
                            <x-heroicon-o-pencil class="h-5 w-5"/>
                        </a>

                        <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-800" title="Löschen">
                            <x-heroicon-o-trash class="h-5 w-5"/>
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- 📄 Pagination -->
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

<!-- ⚡ JS Confirm Dialog -->
<script>
    window.addEventListener('confirm-delete', event => {
        if (confirm('❗ Möchtest du diesen Artikel wirklich löschen?')) {
            Livewire.dispatch('deleteConfirmed', { id: event.detail.id });
        }
    });
</script>
