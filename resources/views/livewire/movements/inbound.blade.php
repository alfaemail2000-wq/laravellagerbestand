<div class="p-6 space-y-6">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        ⬅️ Wareneingang buchen
    </h2>

    {{-- ✅ Erfolgsmeldung --}}
    @if (session()->has('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-md shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- 🧾 Formular --}}
    <form wire:submit.prevent="submit" class="bg-white rounded-lg shadow p-6 space-y-4 border">
        <div>
            <label class="block font-semibold mb-1">Artikel</label>
            <select wire:model="item_id" class="border rounded-lg w-full p-2">
                <option value="">-- Artikel auswählen --</option>
                @foreach($items as $item)
                    <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->sku }})</option>
                @endforeach
            </select>
            @error('item_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Ziel-Lager</label>
            <select wire:model="to_location_id" class="border rounded-lg w-full p-2">
                <option value="">-- Lager auswählen --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('to_location_id') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Menge</label>
            <input type="number" wire:model="quantity" class="border rounded-lg w-full p-2" min="1" />
            @error('quantity') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-semibold mb-1">Notiz</label>
            <input type="text" wire:model="note" class="border rounded-lg w-full p-2" placeholder="optional..." />
            @error('note') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            📦 Wareneingang buchen
        </button>
    </form>

    {{-- 🕒 Letzte Wareneingänge --}}
    <div class="mt-8">
        <h3 class="text-xl font-semibold mb-3">Letzte Wareneingänge</h3>
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden shadow">
            <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="p-2 text-left">SKU</th>
                <th class="p-2 text-left">Artikel</th>
                <th class="p-2 text-left">Lager</th>
                <th class="p-2 text-left">Menge</th>
                <th class="p-2 text-left">Notiz</th>
                <th class="p-2 text-left">Datum</th>
            </tr>
            </thead>
            <tbody>
            @foreach($movements as $m)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $m->item->sku }}</td>
                    <td class="p-2">{{ $m->item->name }}</td>
                    <td class="p-2">{{ $m->toLocation->name ?? '-' }}</td>
                    <td class="p-2">{{ $m->quantity }}</td>
                    <td class="p-2">{{ $m->note }}</td>
                    <td class="p-2">{{ $m->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- 📦 Aktueller Bestand im Hauptlager --}}
    <div class="mt-8">
        <h3 class="text-xl font-bold mb-3">📦 Aktueller Bestand im Hauptlager</h3>
        <table class="w-full border border-gray-200 rounded-lg overflow-hidden shadow">
            <thead class="bg-gray-100 text-gray-700">
            <tr>
                <th class="p-2 text-left">SKU</th>
                <th class="p-2 text-left">Artikelname</th>
                <th class="p-2 text-left">Bestand (Hauptlager)</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="p-2">{{ $item->sku }}</td>
                    <td class="p-2">{{ $item->name }}</td>
                    <td class="p-2 font-semibold text-blue-700">
                        {{ $hauptlagerBestand[$item->id] ?? 0 }}

                        {{-- 🔽 Movement-Typen unter dem Bestand --}}
                        @if(isset($hauptlagerBewegungen[$item->id]))
                            <div class="text-xs text-gray-600 mt-1">
                                @foreach($hauptlagerBewegungen[$item->id] as $bewegung)
                                    • {{ ucfirst($bewegung->type) }} ({{ $bewegung->quantity }})<br>
                                @endforeach
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
