<div class="max-w-3xl mx-auto p-6 space-y-6">
    <h1 class="text-2xl font-semibold">Artikel bearbeiten</h1>

    @if (session('success'))
        <div class="rounded bg-green-100 p-3 text-green-800">
            {{ session('success') }}
        </div>
    @endif

    <form wire:submit.prevent="update" class="space-y-4 border rounded p-4 bg-white shadow">
        <div>
            <label class="block text-sm text-gray-700">SKU (einzigartig)</label>
            <input type="text" wire:model="sku" class="w-full border rounded p-2">
            @error('sku') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700">Name</label>
            <input type="text" wire:model="name" class="w-full border rounded p-2">
            @error('name') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm text-gray-700">Mindestbestand</label>
            <input type="number" wire:model="min_stock" class="w-full border rounded p-2">
            @error('min_stock') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <div class="flex justify-end">
            <button class="px-4 py-2 rounded bg-green-600 text-white hover:bg-green-700">
                Speichern
            </button>
        </div>
    </form>
</div>
