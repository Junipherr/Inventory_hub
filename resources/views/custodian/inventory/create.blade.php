<x-main-layout>
    <div class="page-heading">
        <h1 class="page-title">Add Items</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.html"><i class="la la-home font-20"></i></a>
            </li>
        </ol>
    </div>
    <div class="table-responsive">
        <div class="page-content fade-in-up">
            <div class="row">
                <div class="col-md-6">
                    <div class="ibox">
                        <div class="ibox-head">
                            <div class="ibox-title">Add Items</div>
                            <div class="ibox-tools">
                                <a class="ibox-collapse"><i class="fa fa-minus"></i></a>
                            </div>
                        </div>

                        <div class="ibox-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    <strong>Success!</strong> {{ session('success') }}

                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Error!</strong> Please fix the following errors and try submitting again.
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('inventory.store') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="item_name">Item Name</label>
                                    <input type="text" class="form-control" name="item_name">
                                </div>

                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <select name="department" class="form-control" id="departmentSelect">
                                        <option value="">-- Select Department --</option>
                                        <option value="nursing_department">Nursing Department</option>
                                        <option value="computer_department">Computer Department</option>
                                    </select>
                                </div>

                                <div class="form-group" id="categoryGroup" style="display:none;">
                                    <label for="category_id">Category</label>
                                    <select name="category_id" class="form-control" id="categorySelect">
                                        <option value="">-- Select Category --</option>

                                        <optgroup label="Nursing Department" id="nursing_department_categories">
                                            <option value="medical_equipment">Medical Equipment</option>
                                            <option value="lab_materials">Lab Materials</option>
                                            <option value="protective_gear">Protective Gear</option>
                                            <option value="first_aid_supplies">First Aid Supplies</option>
                                        </optgroup>

                                        <optgroup label="Computer Department" id="computer_department_categories">
                                            <option value="computer_hardware">Computer Hardware</option>
                                            <option value="networking_equipment">Networking Equipment</option>
                                            <option value="software_licenses">Software Licenses</option>
                                            <option value="accessories_peripherals">Accessories & Peripherals</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="quantity">Quantity</label>
                                    <input type="number" name="quantity" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea name="description" class="form-control" rows="3"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary">Add Item</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</x-main-layout>
