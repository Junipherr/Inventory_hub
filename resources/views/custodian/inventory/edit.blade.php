<x-main-layout>
    <div class="page-heading">
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head">
                    <div class="ibox-title"><h1>Edit Inventory Item</h1></div>
                </div>
                <div class="ibox-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{ route('inventory.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="item_name">Item Name</label>
                            <input type="text" class="form-control" id="item_name" name="item_name" value="{{ old('item_name', $item->item_name) }}" required>
                        </div>
                        <div class="form-group">
                            <label for="department">Department</label>
                            <select name="department" class="form-control" id="departmentSelect" required>
                                <option value="">-- Select Department --</option>
                                <option value="nursing_department" {{ old('department', $item->department) == 'nursing_department' ? 'selected' : '' }}>Nursing Department</option>
                                <option value="computer_department" {{ old('department', $item->department) == 'computer_department' ? 'selected' : '' }}>Computer Department</option>
                            </select>
                        </div>
                        <div class="form-group" id="categoryGroup" style="display:none;">
                            <label for="category_id">Category</label>
                            <select name="category_id" class="form-control" id="categorySelect" required>
                                <option value="">-- Select Category --</option>
                                <optgroup label="Nursing Department" id="nursing_department_categories">
                                    <option value="medical_equipment" {{ old('category_id', $item->category_id) == 'medical_equipment' ? 'selected' : '' }}>Medical Equipment</option>
                                    <option value="lab_materials" {{ old('category_id', $item->category_id) == 'lab_materials' ? 'selected' : '' }}>Lab Materials</option>
                                    <option value="protective_gear" {{ old('category_id', $item->category_id) == 'protective_gear' ? 'selected' : '' }}>Protective Gear</option>
                                    <option value="first_aid_supplies" {{ old('category_id', $item->category_id) == 'first_aid_supplies' ? 'selected' : '' }}>First Aid Supplies</option>
                                </optgroup>
                                <optgroup label="Computer Department" id="computer_department_categories">
                                    <option value="computer_hardware" {{ old('category_id', $item->category_id) == 'computer_hardware' ? 'selected' : '' }}>Computer Hardware</option>
                                    <option value="networking_equipment" {{ old('category_id', $item->category_id) == 'networking_equipment' ? 'selected' : '' }}>Networking Equipment</option>
                                    <option value="software_licenses" {{ old('category_id', $item->category_id) == 'software_licenses' ? 'selected' : '' }}>Software Licenses</option>
                                    <option value="accessories_peripherals" {{ old('category_id', $item->category_id) == 'accessories_peripherals' ? 'selected' : '' }}>Accessories & Peripherals</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="1" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description">{{ old('description', $item->description) }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Item</button>
                        <a href="{{ route('inventory.items') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
