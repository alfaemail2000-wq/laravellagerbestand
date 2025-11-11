<div>
    <h2 class="text-xl font-bold mb-4">Transfer buchen</h2>

    @if(session()->has('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="submit" class="mb-6 space-y-4">
        <div>
            <label>Artikel</label>
            <select wire:model="item_id" class="border p-1 w-full">
                <option value="">-- auswählen --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                @endforeach
            </select>
            @error('item_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Von Lager</label>
            <select wire:model="from_location_id" class="border p-1 w-full">
                <option value="">-- auswählen --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('from_location_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Zu Lager</label>
            <select wire:model="to_location_id" class="border p-1 w-full">
                <option value="">-- auswählen --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('to_location_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Menge</label>
            <input type="number" wire:model="quantity" class="border p-1 w-full" min="1"/>
            @error('quantity') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Notiz</label>
            <input type="text" wire:model="note" class="border p-1 w-full"/>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Transfer buchen</button>
    </form>

    <h3 class="text-lg font-semibold mb-2">Letzte Transfers</h3>
    <table class="w-full border">
        <thead>
        <tr class="bg-gray-100">
            <th class="border p-1">Artikel</th>
            <th class="border p-1">Von Lager</th>
            <th class="border p-1">Zu Lager</th>
            <th class="border p-1">Menge</th>
            <th class="border p-1">Notiz</th>
            <th class="border p-1">Datum</th>
        </tr>
        </thead>
        <tbody>
        @foreach($movements as $m)
            <tr>
                <td class="border p-1">{{ $m->item->name }}</td>
                <td class="border p-1">{{ $m->fromLocation->name }}</td>
                <td class="border p-1">{{ $m->toLocation->name }}</td>
                <td class="border p-1">{{ $m->quantity }}</td>
                <td class="border p-1">{{ $m->note }}</td>
                <td class="border p-1">{{ $m->created_at->format('d.m.Y H:i') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
