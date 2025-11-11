<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Transfer zwischen Lagern</h1>

    <form wire:submit.prevent="save" class="space-y-3">
        <select wire:model="item_id" class="border p-2 w-full">
            <option value="">Artikel wählen...</option>
            @foreach ($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>

        <div class="grid grid-cols-2 gap-2">
            <select wire:model="from_location_id" class="border p-2">
                <option value="">Von Lager...</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>

            <select wire:model="to_location_id" class="border p-2">
                <option value="">Nach Lager...</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <input wire:model="quantity" placeholder="Menge" class="border p-2 w-full">
        <input wire:model="note" placeholder="Notiz" class="border p-2 w-full">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Buchen</button>
    </form>

    <h2 class="text-lg font-semibold mt-6">Letzte Transfers</h2>
    <ul class="mt-2">
        @foreach ($movements as $m)
            <li>🔄 {{ $m->item->name }}: {{ $m->quantity }}
                ({{ $m->fromLocation->name ?? '?' }} → {{ $m->toLocation->name ?? '?' }})</li>
        @endforeach
    </ul>
</div>
