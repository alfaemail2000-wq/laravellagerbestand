<div class="p-6 space-y-6">

    {{-- Navigation / Tabs --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @php
            $tabs = [
                ['name' => 'Artikelübersicht', 'route' => 'items.index', 'icon' => '📦'],
                ['name' => 'Wareneingang', 'route' => 'movements.inbound', 'icon' => '⬅️'],
                ['name' => 'Warenausgang', 'route' => 'movements.outbound', 'icon' => '➡️'],
                ['name' => 'Transfer', 'route' => 'movements.transfer', 'icon' => '🔁'],
                ['name' => 'Produktion', 'route' => 'movements.production', 'icon' => '🏭'],
            ];
        @endphp

        @foreach($tabs as $tab)
            <a href="{{ route($tab['route']) }}"
               class="px-4 py-2 rounded font-medium transition-colors duration-200
                      {{ request()->routeIs($tab['route']) ? 'bg-blue-600 text-white shadow' : 'bg-gray-200 hover:bg-gray-300' }}">
                {{ $tab['icon'] }} {{ $tab['name'] }}
            </a>
        @endforeach
    </div>

    {{-- Suche & Aktionen --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
        <input type="text"
               wire:model.live="search"
               placeholder="Suche nach SKU oder Name..."
               class="border rounded p-2 w-full md:w-1/3 shadow-sm focus:ring-2 focus:ring-blue-400">

        <div class="flex gap-2">
            <button wire:click="exportCsv"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow transition">
                📄 CSV exportieren
            </button>

            <a href="{{ route('items.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                + Neuer Artikel
            </a>
        </div>
    </div>

    {{-- Flash / Erfolgsmeldung --}}
    @if (session('success'))
        <div class="rounded bg-green-100 text-green-800 p-3 shadow mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabelle --}}
    <div class="overflow-x-auto shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">SKU</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Name</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Min. Bestand</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Gesamt</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Hauptlager</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Produktion</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Zielbestand</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Status</th>
                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Aktionen</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
                @php
                    $stocks = $item->stockByLocation();
                @endphp
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-2">{{ $item->sku }}</td>
                    <td class="px-4 py-2">{{ $item->name }}</td>
                    <td class="px-4 py-2">{{ $item->min_stock ?? '-' }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $item->totalStock() }}</td>
                    <td class="px-4 py-2">{{ $stocks['Hauptlager'] ?? 0 }}</td>
                    <td class="px-4 py-2">{{ $stocks['Produktion'] ?? 0 }}</td>
                    <td class="px-4 py-2">{{ $item->target_stock ?? '-' }}</td>
                    <td class="px-4 py-2">
                        @if(!is_null($item->min_stock) && $item->totalStock() < $item->min_stock)
                            <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-sm font-medium">Low Stock</span>
                        @elseif(!is_null($item->target_stock) && $item->totalStock() >= $item->target_stock)
                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm font-medium">Ziel erreicht</span>
                        @else
                            <span class="px-2 py-1 rounded bg-gray-100 text-gray-700 text-sm font-medium">OK</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 flex gap-2">
                        <a href="{{ route('items.show', $item) }}" class="text-blue-600 hover:underline">Details</a>
                        <a href="{{ route('items.edit', $item) }}" class="text-yellow-600 hover:underline">Bearbeiten</a>
                        <button wire:click="confirmDelete({{ $item->id }})"
                                class="text-red-600 hover:underline">Löschen</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>

{{-- JS Confirm Dialog --}}
<script>
    window.addEventListener('confirm-delete', event => {
        if (confirm('❗ Möchtest du diesen Artikel wirklich löschen?')) {
            Livewire.dispatch('deleteConfirmed', { id: event.detail.id });
        }
    });
</script>
