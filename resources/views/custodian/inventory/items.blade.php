<x-main-layout>
    <!-- Use Font Awesome CDN as in layout.blade.php -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Embedded important CSS from itemsdesign.css -->
    <style>
        /* Custom styles for enhanced design */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .modal-content {
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        th {
            background: linear-gradient(to bottom, #f9fafb, #f3f4f6);
        }
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Responsive table: horizontal scroll on mobile */
        @media (max-width: 640px) {
            .overflow-x-auto {
                -webkit-overflow-scrolling: touch;
                overflow-x: auto;
            }
            table, thead, tbody, th, td, tr {
                display: block;
                width: 100%;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr {
                margin-bottom: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 1px 3px rgba(0,0,0,0.03);
                background: #fff;
                padding: 0.5rem 0;
            }
            td {
                border: none;
                position: relative;
                padding-left: 50%;
                min-height: 40px;
                font-size: 0.95rem;
            }
            td:before {
                position: absolute;
                top: 0.75rem;
                left: 1rem;
                width: 45%;
                white-space: nowrap;
                font-weight: 600;
                color: #6b7280;
                font-size: 0.85rem;
            }
            td:nth-of-type(1):before { content: "Item Details"; }
            td:nth-of-type(2):before { content: "Location"; }
            td:nth-of-type(3):before { content: "Category"; }
            td:nth-of-type(4):before { content: "Quantity"; }
            td:nth-of-type(5):before { content: "Condition"; }
            td:nth-of-type(6):before { content: "Actions"; }
        }

        /* Responsive modals */
        @media (max-width: 640px) {
            .modal-content {
                width: 95vw !important;
                max-width: 95vw !important;
                left: 2.5vw;
                right: 2.5vw;
                top: 10vw;
                padding: 1rem !important;
            }
        }

        /* Responsive buttons and spacing */
        @media (max-width: 640px) {
            .px-6 { padding-left: 1rem !important; padding-right: 1rem !important; }
            .py-4, .py-5, .py-8, .py-16 { padding-top: 1rem !important; padding-bottom: 1rem !important; }
            .text-3xl { font-size: 1.5rem !important; }
            .text-xl { font-size: 1.1rem !important; }
            .rounded-lg, .rounded-xl { border-radius: 0.75rem !important; }
            .space-x-2 > :not([hidden]) ~ :not([hidden]) { margin-left: 0.5rem !important; }
            .flex-col { flex-direction: column !important; }
            .gap-4 { gap: 0.75rem !important; }
        }
    </style>
    
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Inventory Management</h1>
                        <p class="mt-2 text-sm text-gray-600">Manage all inventory items with ease</p>
                    </div>
                    <a href="{{ route('inventory.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Add New Item
                    </a>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Content Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold text-gray-800">All Inventory Items</h2>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            {{ $items->total() }} items
                        </span>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <span>Item Details</span>
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Location
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Quantity
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Condition
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @forelse($items as $item)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150 group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-box text-blue-600"></i>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">
                                                        {{ $item->item_name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 mt-1 line-clamp-2">
                                                        {{ $item->description ? Str::limit($item->description, 60) : 'No description' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $item->room->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                {{ $item->category_id }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <span class="text-sm font-semibold text-gray-900 bg-gray-100 px-3 py-1 rounded-full">
                                                    {{ $item->quantity }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $conditionColors = [
                                                    'Good' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-check-circle'],
                                                    'Fair' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-exclamation-circle'],
                                                    'Poor' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-times-circle'],
                                                ];
                                                $condition = $item->condition ?? 'Unknown';
                                                $colors = $conditionColors[$condition] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-question-circle'];
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                <i class="fas {{ $colors['icon'] }} mr-1"></i>
                                                {{ $condition }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <button onclick="openEditModal({{ $item->id }}, '{{ addslashes($item->item_name) }}', '{{ addslashes($item->description) }}', '{{ $item->quantity }}', '{{ $item->condition }}', '{{ $item->room_id }}', '{{ $item->category_id }}')" 
                                                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                                    <i class="fas fa-edit mr-2 text-blue-600"></i>
                                                    Edit
                                                </button>
                                                <button onclick="openDeleteModal({{ $item->id }}, '{{ addslashes($item->item_name) }}')" 
                                                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-md text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-16 text-center">
                                            <div class="text-center">
                                                <div class="mx-auto h-16 w-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                    <i class="fas fa-inbox text-gray-400 text-2xl"></i>
                                                </div>
                                                <h3 class="text-lg font-medium text-gray-900 mb-2">No items found</h3>
                                                <p class="text-sm text-gray-500 mb-4">Get started by adding your first inventory item</p>
                                                <a href="{{ route('inventory.create') }}" 
                                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-plus mr-2"></i>
                                                    Add First Item
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($items->hasPages())
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of {{ $items->total() }} results
                            </div>
                            <div class="flex space-x-2">
                                {{ $items->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-xl rounded-xl bg-white transform transition-transform duration-300 scale-95 opacity-0 modal-content">
            <div class="absolute top-4 right-4">
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="mt-3">
                <div class="text-center mb-6">
                    <div class="mx-auto h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-edit text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Edit Item Details</h3>
                    <p class="text-sm text-gray-500 mt-1">Update the item information</p>
                </div>
<form id="editForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="edit_item_name" class="block text-sm font-medium text-gray-700 mb-2">Item Name</label>
                        <input type="text" name="item_name" id="edit_item_name" 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                    </div>
                    
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors resize-none"></textarea>
                    </div>
                    
                    <div>
                        <label for="edit_quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" name="quantity" id="edit_quantity" min="1"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                    </div>
                    
                    <div>
                        <label for="edit_condition" class="block text-sm font-medium text-gray-700 mb-2">Condition</label>
                        <select name="condition" id="edit_condition" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_room_id" class="block text-sm font-medium text-gray-700 mb-2">Room Location</label>
                        <select name="room_id" id="edit_room_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="">-- Select Room --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="edit_category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category_id" id="edit_category_id" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                            <option value="">-- Select Category --</option>
                            <option value="computer_hardware_peripherals">💻 Computer Hardware & Peripherals</option>
                            <option value="office_classroom_furniture">🪑 Office and Classroom Furniture</option>
                            <option value="appliances_electronics">📺 Appliances and Electronics</option>
                            <option value="classroom_office_supplies">📚 Classroom/Office Supplies</option>
                            <option value="networking_equipment">🌐 Networking Equipment</option>
                            <option value="security_systems">🔒 Security Systems</option>
                            <option value="laboratory_equipment">🧪 Laboratory Equipment</option>
                            <option value="medical_equipment">🏥 Medical Equipment</option>
                        </select>
                    </div>
                    
                    <div class="flex space-x-3 pt-4">
                        <button type="submit" 
                                class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg shadow-md hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                            <span class="submit-text">Save Changes</span>
                            <span class="loading-text hidden">Saving...</span>
                        </button>
                        <button type="button" 
                                onclick="closeEditModal()"
                                class="flex-1 px-6 py-3 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden transition-opacity duration-300">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-xl rounded-xl bg-white transform transition-transform duration-300 scale-95 opacity-0 modal-content">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm Deletion</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-600">
                        Are you sure you want to delete <span id="deleteItemName" class="font-semibold text-gray-900"></span>? 
                        This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3 space-y-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                            <span class="submit-text">Delete Item</span>
                            <span class="loading-text hidden">Deleting...</span>
                        </button>
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="w-full px-6 py-3 bg-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Enhanced modal functions with animations
        function openEditModal(itemId, itemName, description, quantity, condition, roomId, categoryId) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            const content = modal.querySelector('.modal-content');
            
            form.action = `/inventory/items/${itemId}`;
            document.getElementById('edit_item_name').value = itemName;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_quantity').value = quantity || 1;
            document.getElementById('edit_condition').value = condition || 'Good';
            document.getElementById('edit_room_id').value = roomId || '';
            document.getElementById('edit_category_id').value = categoryId || '';
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = modal.querySelector('.modal-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function openDeleteModal(itemId, itemName) {
            const modal = document.getElementById('deleteModal');
            const content = modal.querySelector('.modal-content');
            
            document.getElementById('deleteItemName').textContent = itemName;
            document.getElementById('deleteForm').action = `/inventory/items/${itemId}`;
            
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const content = modal.querySelector('.modal-content');
            
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const deleteModal = document.getElementById('deleteModal');
            const editModal = document.getElementById('editModal');
            
            if (event.target === deleteModal) {
                closeDeleteModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }

        // Enhanced AJAX form handling with better UX
        function setupFormHandling(formId, successCallback) {
            const form = document.getElementById(formId);
            const submitBtn = form.querySelector('button[type="submit"]');
            const submitText = submitBtn.querySelector('.submit-text');
            const loadingText = submitBtn.querySelector('.loading-text');
            
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Show loading state
                submitBtn.disabled = true;
                submitText.classList.add('hidden');
                loadingText.classList.remove('hidden');
                
                const formData = new FormData(this);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
                
                try {
                    const response = await fetch(this.action, {
                        method: 'POST', // Always use POST for form submissions
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.success) {
                        successCallback(data);
                    } else {
                        throw new Error(data.message || 'Operation failed');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error: ' + error.message);
                } finally {
                    // Reset button state
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    loadingText.classList.add('hidden');
                }
            });
        }

        // Setup form handlers
        setupFormHandling('deleteForm', function(data) {
            if (data.success) {
                closeDeleteModal();
                location.reload();
            }
        });

        setupFormHandling('editForm', function(data) {
            if (data.success) {
                closeEditModal();
                location.reload();
            }
        });

        // Add smooth scrolling for better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Add subtle animations to page elements
            const elements = document.querySelectorAll('.transform');
            elements.forEach(el => {
                el.style.transition = 'all 0.3s ease';
            });
        });
    </script>

    <!-- Embedded CSS already above -->
</x-main-layout>
