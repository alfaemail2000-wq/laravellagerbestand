<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">📊 Bewegungen</h1>

    <select wire:model="type" class="border p-2 mb-4">
        <option value="">Alle Typen</option>
        <option value="in">Eingang</option>
        <option value="out">Ausgang</option>
        <option value="transfer">Transfer</option>
    </select>

    <table class="w-full border-collapse border">
        <thead class="bg-gray-100">
        <tr>
            <th class="p-2 border">Artikel</th>
            <th class="p-2 border">Typ</th>
            <th class="p-2 border">Von</th>
            <th class="p-2 border">Nach</th>
            <th class="p-2 border text-right">Menge</th>
            <th class="p-2 border">Notiz</th>
        </tr>
        </thead>
        <tbody>
        @foreach($movements as $m)
            <tr>
                <td class="p-2 border">{{ $m->item->name }}</td>
                <td class="p-2 border">{{ $m->type }}</td>
                <td class="p-2 border">{{ $m->fromLocation->name ?? '-' }}</td>
                <td class="p-2 border">{{ $m->toLocation->name ?? '-' }}</td>
                <td class="p-2 border text-right">{{ $m->quantity }}</td>
                <td class="p-2 border">{{ $m->note }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
