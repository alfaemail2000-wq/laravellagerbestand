<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Artikelübersicht</h1>

    <input type="text" wire:model.live="search" placeholder="Suche nach SKU oder Name..." class="border p-2 rounded w-full mb-4">

    <table class="table-auto w-full border-collapse">
        <thead>
        <tr class="bg-gray-100">
            <th class="border p-2">SKU</th>
            <th class="border p-2">Name</th>
            <th class="border p-2">Mindestbestand</th>
            <th class="border p-2">Aktionen</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td class="border p-2">{{ $item->sku }}</td>
                <td class="border p-2">{{ $item->name }}</td>
                <td class="border p-2">{{ $item->min_stock ?? '–' }}</td>
                <td class="border p-2">
                    <a href="{{ route('items.show', $item) }}" class="text-blue-500 hover:underline">Details</a> |
                    <a href="{{ route('items.edit', $item) }}" class="text-yellow-600 hover:underline">Bearbeiten</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="mt-4">{{ $items->links() }}</div>
</div>
