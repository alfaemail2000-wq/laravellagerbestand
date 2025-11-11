<div>
    <h2 class="text-xl font-bold mb-4">Produktion buchen</h2>

    @if(session()->has('success'))
        <div class="bg-green-200 text-green-800 p-2 mb-4 rounded">{{ session('success') }}</div>
    @endif

    @error('production')
    <div class="bg-red-200 text-red-800 p-2 mb-4 rounded">{{ $message }}</div>
    @enderror

    <form wire:submit.prevent="produce" class="mb-6 space-y-4">
        <div>
            <label>Menge produzieren</label>
            <input type="number" wire:model="quantity" class="border p-1 w-full" min="1"/>
            @error('quantity') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Quelle-Lager (Material)</label>
            <select wire:model="from_location_id" class="border p-1 w-full">
                <option value="">-- auswählen --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('from_location_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <div>
            <label>Ziel-Lager (Fertigprodukt)</label>
            <select wire:model="to_location_id" class="border p-1 w-full">
                <option value="">-- auswählen --</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
            @error('to_location_id') <span class="text-red-600">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="bg-purple-500 text-white px-3 py-1 rounded">Produktion buchen</button>
    </form>
</div>
