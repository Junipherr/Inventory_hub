<x-main-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800">Inventory Items</h2>
                        <a href="{{ route('inventory.create') }}" 
                           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-plus mr-2"></i>Add New Item
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Items Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($items as $item)
                                    <tr class="hover:bg-gray-50 transition duration-200">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $item->room->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->category_id }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($item->condition == 'Good') bg-green-100 text-green-800
                                                @elseif($item->condition == 'Fair') bg-yellow-100 text-yellow-800
                                                @elseif($item->condition == 'Poor') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ $item->condition ?? 'Unknown' }}
                                            </span>
                                        </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="openEditModal({{ $item->id }}, '{{ $item->item_name }}', '{{ $item->description }}', '{{ $item->quantity }}', '{{ $item->condition }}', '{{ $item->room_id }}', '{{ $item->category_id }}')" 
                                                        class="text-indigo-600 hover:text-indigo-900 transition duration-200">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button onclick="openDeleteModal({{ $item->id }}, '{{ $item->item_name }}')" 
                                                        class="text-red-600 hover:text-red-900 transition duration-200">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center">
                                            <div class="text-gray-500">
                                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                                <p class="text-lg">No items found</p>
                                                <p class="text-sm">Get started by adding your first inventory item</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Edit Item</h3>
                <form id="editForm" method="POST" class="mt-4 space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label for="edit_item_name" class="block text-sm font-medium text-gray-700">Item Name</label>
                        <input type="text" name="item_name" id="edit_item_name" 
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    </div>
                    
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="edit_description" rows="3" 
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border"></textarea>
                    </div>
                    
                    <div>
                        <label for="edit_quantity" class="block text-sm font-medium text-gray-700">Quantity</label>
                        <input type="number" name="quantity" id="edit_quantity" min="1"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                    </div>
                    
                    <div>
                        <label for="edit_condition" class="block text-sm font-medium text-gray-700">Condition</label>
                        <select name="condition" id="edit_condition" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                            <option value="Good">Good</option>
                            <option value="Fair">Fair</option>
                            <option value="Poor">Poor</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit_room_id" class="block text-sm font-medium text-gray-700">Room Location</label>
                        <select name="room_id" id="edit_room_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
                            <option value="">-- Select Room --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="edit_category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="edit_category_id" 
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2 border">
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
                                class="flex-1 px-4 py-2 bg-indigo-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                        <button type="button" 
                                onclick="closeEditModal()"
                                class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Delete Item</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete <span id="deleteItemName" class="font-semibold"></span>? 
                        This action cannot be undone.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Delete
                        </button>
                        <button type="button" 
                                onclick="closeDeleteModal()"
                                class="mt-2 px-4 py-2 bg-gray-300 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
        function openEditModal(itemId, itemName, description, quantity, condition, roomId, categoryId) {
            document.getElementById('editForm').action = `/inventory/items/${itemId}`;
            document.getElementById('edit_item_name').value = itemName;
            document.getElementById('edit_description').value = description || '';
            document.getElementById('edit_quantity').value = quantity || 1;
            document.getElementById('edit_condition').value = condition || 'Good';
            document.getElementById('edit_room_id').value = roomId || '';
            document.getElementById('edit_category_id').value = categoryId || '';
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openDeleteModal(itemId, itemName) {
            document.getElementById('deleteItemName').textContent = itemName;
            document.getElementById('deleteForm').action = `/inventory/items/${itemId}`;
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
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

        // Handle delete form submission with AJAX
        document.getElementById('deleteForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            
            fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting item');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error deleting item: ' + error.message);
            });
        });

        // Handle edit form submission with AJAX
        document.getElementById('editForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            // Disable submit button to prevent double submission
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => {
                // Clone response to read it twice if needed
                const responseClone = response.clone();
                
                return response.json()
                    .catch(() => {
                        // If JSON parsing fails, read as text to debug
                        return responseClone.text().then(text => {
                            console.error('Server response:', text);
                            throw new Error('Server returned HTML instead of JSON. Check console for details.');
                        });
                    })
                    .then(data => {
                        if (!response.ok) {
                            throw new Error(data.message || 'Server error');
                        }
                        return data;
                    });
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessages = Object.values(data.errors).flat().join('\n');
                        alert('Validation errors:\n' + errorMessages);
                    } else {
                        alert(data.message || 'Error updating item');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating item: ' + error.message);
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });
    </script>

    <!-- Add Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Link custom CSS for items styling -->
    <link rel="stylesheet" href="{{ asset('assets/css/itemsdesign.css') }}">
</x-main-layout>