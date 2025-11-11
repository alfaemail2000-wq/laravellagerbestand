<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $item->name }}</h1>
    <p><strong>SKU:</strong> {{ $item->sku }}</p>
    <p><strong>Mindestbestand:</strong> {{ $item->min_stock ?? '–' }}</p>
    <p><strong>Aktueller Bestand:</strong> {{ $item->totalStock() ?? 0 }}</p>

    <p>Aktueller Bestand: {{ $item->stock }}</p> <!-- aktueller Bestand -->
</div>
