<x-main-layout>
    <div class="page-heading">
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title"><h1>Item Details</h1></div>
                </div>
                <div class="ibox-body p-4">
                    <h2 class="text-xl font-semibold mb-4">{{ $item->item_name }}</h2>
                    <p><strong>Department:</strong> {{ $item->department }}</p>
                    <p><strong>Category:</strong> {{ $item->category_id }}</p>
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                    <p><strong>Description:</strong> {{ $item->description }}</p>
                    <div class="mt-4">
                        <h3 class="font-semibold mb-2">QR Code:</h3>
                        <img src="{{ route('inventory.qrcode', $item->id) }}" alt="QR Code for {{ $item->item_name }}" class="mx-auto" />
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('inventory.items') }}" class="btn btn-primary">Back to Items</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
