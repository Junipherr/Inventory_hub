<x-main-layout>
    <div class="container-fluid py-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
            Viewer Dashboard
        </h2>

        @forelse ($rooms as $room)
            <div class="mb-6">
                <h3 class="text-lg font-bold mb-2">{{ $room->name }}</h3>
                @if ($room->items->isEmpty())
                    <p class="text-gray-500">No items assigned to this room.</p>
                @else
                    <ul class="list-disc list-inside">
                        @foreach ($room->items as $item)
                            <li class="mb-2">
                                <div class="font-semibold">{{ $item->item_name }}</div>
                                <div class="text-sm text-gray-600">{{ $item->description }}</div>
                                <div class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</div>
                                @if ($item->units->isNotEmpty())
                                    <div class="mt-1">
                                        <span class="font-semibold">Units:</span>
                                        <ul class="list-disc list-inside ml-4">
                                            @foreach ($item->units as $unit)
                                                <li>{{ $unit->unit_name ?? 'Unnamed Unit' }} - Quantity: {{ $unit->quantity ?? 'N/A' }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @empty
            <p>No rooms found.</p>
        @endforelse
    </div>
</x-main-layout>
