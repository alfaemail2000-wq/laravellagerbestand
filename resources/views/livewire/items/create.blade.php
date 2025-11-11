<div class="p-6">
    <h1 class="text-xl font-bold mb-4">Neuen Artikel anlegen</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <input wire:model="sku" placeholder="SKU" class="border p-2 w-full">
        @error('sku') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <input wire:model="name" placeholder="Name" class="border p-2 w-full">
        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <input wire:model="min_stock" placeholder="Mindestbestand" class="border p-2 w-full">
        @error('min_stock') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Speichern</button>
    </form>
</div>
