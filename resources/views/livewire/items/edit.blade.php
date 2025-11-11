<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Artikel bearbeiten</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <input wire:model="item.sku" class="border p-2 w-full">
        <input wire:model="item.name" class="border p-2 w-full">
        <input wire:model="item.min_stock" class="border p-2 w-full">

        <button class="bg-green-600 text-white px-4 py-2 rounded">Speichern</button>
    </form>
</div>
