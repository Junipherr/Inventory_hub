<x-main-layout>
    <div class="page-heading my-4">
        <div class="page-content fade-in-up">
            <div class="ibox">
                <div class="ibox-head mb-3">
                    <div class="ibox-title">
                        <h1 class="text-primary">Edit Item</h1>
                    </div>
                </div>

                <form action="{{ route('inventory.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="item_name" class="form-label">Item Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('item_name') is-invalid @enderror" 
                                       id="item_name" 
                                       name="item_name" 
                                       value="{{ old('item_name', $item->item_name) }}" 
                                       required>
                                @error('item_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('category_id') is-invalid @enderror" 
                                       id="category_id" 
                                       name="category_id" 
                                       value="{{ old('category_id', $item->category_id) }}" 
                                       required>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="room_id" class="form-label">Room <span class="text-danger">*</span></label>
                                <select class="form-control @error('room_id') is-invalid @enderror" 
                                        id="room_id" 
                                        name="room_id" 
                                        required>
                                    <option value="">Select Room</option>
                                    @foreach(\App\Models\Room::all() as $room)
                                        <option value="{{ $room->id }}" 
                                                {{ old('room_id', $item->room_id) == $room->id ? 'selected' : '' }}>
                                            {{ $room->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ old('quantity', $item->quantity) }}" 
                                       min="0" 
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" 
                                  name="description" 
                                  rows="3">{{ old('description', $item->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="department" class="form-label">Department</label>
                        <input type="text" 
                               class="form-control @error('department') is-invalid @enderror" 
                               id="department" 
                               name="department" 
                               value="{{ old('department', $item->department) }}">
                        @error('department')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="qr_code" class="form-label">QR Code</label>
                        <input type="text" 
                               class="form-control @error('qr_code') is-invalid @enderror" 
                               id="qr_code" 
                               name="qr_code" 
                               value="{{ old('qr_code', $item->qr_code) }}" 
                               readonly>
                        @error('qr_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group text-end">
                        <a href="{{ route('inventory.items') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Items
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-main-layout>
