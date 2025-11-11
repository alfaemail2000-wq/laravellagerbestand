<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Produktion starten</h1>

    <form wire:submit.prevent="save" class="space-y-3">
        <div class="grid grid-cols-2 gap-2">
            <select wire:model="from_location_id" class="border p-2">
                <option value="">Material aus Lager...</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>

            <select wire:model="to_location_id" class="border p-2">
                <option value="">Fertige Ware in Lager...</option>
                @foreach ($locations as $loc)
                    <option value="{{ $loc->id }}">{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <input wire:model="quantity" placeholder="Produktionsmenge" class="border p-2 w-full">
        <button class="bg-green-600 text-white px-4 py-2 rounded">Produzieren</button>
    </form>
</div>
