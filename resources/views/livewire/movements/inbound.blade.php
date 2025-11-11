<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Wareneingang (Hauptlager)</h1>

    <form wire:submit.prevent="save" class="space-y-3">
        <select wire:model="item_id" class="border p-2 w-full">
            <option value="">Artikel wählen...</option>
            @foreach ($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
        </select>

        <input wire:model="quantity" placeholder="Menge" class="border p-2 w-full">
        <input wire:model="note" placeholder="Notiz" class="border p-2 w-full">
        <button class="bg-blue-600 text-white px-4 py-2 rounded">Buchen</button>
    </form>

    <h2 class="text-lg font-semibold mt-6">Letzte Eingänge</h2>
    <ul>
        @foreach ($movements as $m)
            <li>📦 {{ $m->item->name }} – {{ $m->quantity }} ({{ $m->note }})</li>
        @endforeach
    </ul>
</div>
