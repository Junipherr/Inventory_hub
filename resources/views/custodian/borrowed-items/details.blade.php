<x-main-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Borrowed Item Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Item Information</h3>
                        <a href="{{ route('borrowed.items') }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Back to Borrowed Items
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-md font-semibold text-gray-700 mb-3">Item Details</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Item Name:</span>
                                    <span class="text-gray-900">{{ $itemUnit->item->item_name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Unit Number:</span>
                                    <span class="text-gray-900">{{ $itemUnit->unit_number }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Room:</span>
                                    <span class="text-gray-900">{{ $itemUnit->item->room->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Category:</span>
                                    <span class="text-gray-900">{{ $itemUnit->item->category_id ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="text-md font-semibold text-gray-700 mb-3">Borrowing Information</h4>
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Borrowed By:</span>
                                    <span class="text-gray-900">{{ $itemUnit->borrower_name ?? $itemUnit->borrower->name ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Borrow Date:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($itemUnit->borrowed_at)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Expected Return:</span>
                                    <span class="text-gray-900">{{ \Carbon\Carbon::parse($itemUnit->expected_return_date)->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-600">Status:</span>
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Borrowed</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($itemUnit->purpose)
                    <div class="mt-6">
                        <h4 class="text-md font-semibold text-gray-700 mb-3">Purpose</h4>
                        <p class="text-gray-600">{{ $itemUnit->purpose }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
