<div class="p-6">
    <h2 class="text-xl font-bold mb-4">Warenausgang buchen</h2>

    @if(session()->has('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="submit" class="space-y-4 mb-6">
        <div>
            <label>Artikel</label>
            <select wire:model="item_id" class="border p-2 w-full">
                <option value="">-- auswählen --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">
                        {{ $item->name }} ({{ $item->sku }})
                    </option>
                @endforeach
            </select>
            @error('item_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Quelle-Lager</label>
            <select wire:model="from_location_id" class="border p-2 w-full">
                <option value="">-- auswählen --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('from_location_id') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Menge</label>
            <input type="number" wire:model="quantity" class="border p-2 w-full" min="1">
            @error('quantity') <span class="text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Notiz</label>
            <input type="text" wire:model="note" class="border p-2 w-full">
        </div>

        <button class="bg-red-600 text-white px-4 py-2 rounded">
            Ausgang buchen
        </button>
    </form>

    <h3 class="text-lg font-semibold mb-2">Letzte Warenausgänge</h3>

    <table class="w-full border">
        <thead>
        <tr class="bg-gray-100">
            <th class="p-2 border">Artikel</th>
            <th class="p-2 border">Quelle-Lager</th>
            <th class="p-2 border">Menge</th>
            <th class="p-2 border">Notiz</th>
            <th class="p-2 border">Datum</th>
        </tr>
        </thead>
        <tbody>
        @foreach($movements as $m)
            <tr>
                <td class="border p-2">{{ $m->item->name }}</td>
                <td class="border p-2">{{ $m->fromLocation->name }}</td>
                <td class="border p-2">{{ $m->quantity }}</td>
                <td class="border p-2">{{ $m->note }}</td>
                <td class="border p-2">{{ $m->created_at->format('d.m.Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
