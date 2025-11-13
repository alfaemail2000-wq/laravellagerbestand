<div class="min-h-screen flex items-center justify-center bg-gray-100 p-6">

    <div class="bg-white shadow-lg rounded-2xl p-8 w-full max-w-xl">

        {{-- Titel --}}
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-2 mb-6">
            <x-heroicon-o-cube class="w-8 h-8 text-blue-600" />
            {{ $item->name }}
        </h1>

        <div class="space-y-4 text-gray-700">

            {{-- SKU --}}
            <div class="flex items-center gap-3">
                <x-heroicon-o-hashtag class="w-6 h-6 text-gray-500" />
                <p>
                    <span class="font-semibold">SKU:</span>
                    {{ $item->sku }}
                </p>
            </div>

            {{-- Mindestbestand --}}
            <div class="flex items-center gap-3">
                <x-heroicon-o-exclamation-circle class="w-6 h-6 text-yellow-500" />
                <p>
                    <span class="font-semibold">Mindestbestand:</span>
                    {{ $item->min_stock ?? '–' }}
                </p>
            </div>

            {{-- Gesamtbestand --}}
            <div class="flex items-center gap-3">
                <x-heroicon-o-archive-box class="w-6 h-6 text-green-600" />
                <p>
                    <span class="font-semibold">Gesamtbestand:</span>
                    {{ $item->totalStock() }}
                </p>
            </div>

            {{-- Hauptlagerbestand (sofern vorhanden) --}}
            <div class="flex items-center gap-3">
                <x-heroicon-o-building-storefront class="w-6 h-6 text-blue-600" />
                <p>
                    <span class="font-semibold">Bestand Hauptlager:</span>
                    {{ $item->stock }}
                </p>
            </div>

        </div>

        {{-- Zurück Button --}}
        <div class="mt-8 text-center">
            <a href="{{ route('items.index') }}"
               class="inline-flex items-center gap-2 bg-gray-700 hover:bg-gray-800 text-white px-5 py-2.5 rounded-xl transition">
                <x-heroicon-o-arrow-left class="w-5 h-5" />
                Zurück zur Übersicht
            </a>
        </div>

    </div>
</div>
