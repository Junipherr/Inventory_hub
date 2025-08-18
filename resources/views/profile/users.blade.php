<x-main-layout>
    <div class="container-fluid py-4">
        
        <!-- Enhanced Success/Error Messages -->
        <div id="notificationContainer" style="position: fixed; top: 20px; right: 20px; z-index: 1050; max-width: 400px;">
            <!-- Success Message -->
            <div id="dynamicSuccessMessage" class="alert alert-success alert-dismissible fade show shadow-lg" role="alert" style="display: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>
                        <strong>Success!</strong>
                        <span id="successMessageText" class="ms-1"></span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- Error Message -->
            <div id="dynamicErrorMessage" class="alert alert-danger alert-dismissible fade show shadow-lg" role="alert" style="display: none;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>
                        <strong>Error!</strong>
                        <span id="errorMessageText" class="ms-1"></span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-1">Profile Management</h1>
                        <p class="text-muted mb-0">Manage user profiles and assign rooms efficiently</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#profileRegistrationModal">
                        <i class="fas fa-plus me-2"></i>
                        Add New Profile
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-users text-primary"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ $profiles->count() }}</h5>
                                <p class="text-muted mb-0">Total Profiles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i class="fas fa-door-open text-success"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mb-0">{{ $profiles->where('room_id', '!=', null)->count() }}</h5>
                                <p class="text-muted mb-0">Assigned Rooms</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profiles Grid/List -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent">
                        <h5 class="mb-0">User Profiles</h5>
                    </div>
                    <div class="card-body">
                        @if(!$profiles || $profiles->isEmpty())
                            <div class="text-center py-5">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No profiles found</h5>
                                <p class="text-muted">Get started by adding your first profile</p>
                            </div>
                        @else
                            <!-- Desktop Table View -->
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th class="border-0">
                                                <i class="fas fa-user me-2"></i>Name
                                            </th>
                                            <th class="border-0">
                                                <i class="fas fa-envelope me-2"></i>Email
                                            </th>
                                            <th class="border-0">
                                                <i class="fas fa-door-open me-2"></i>Room
                                            </th>
                                            <th class="border-0">
                                                <i class="fas fa-calendar me-2"></i>Created
                                            </th>
                                            <th class="border-0 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($profiles as $profile)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <i class="fas fa-user-circle text-primary" style="font-size: 1.5rem;"></i>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $profile->name }}</h6>
                                                            <small class="text-muted">User ID: {{ $profile->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="text-muted">{{ $profile->email }}</span>
                                                </td>
                                                <td>
                                                    @if($profile->room)
                                                        <span class="text-dark">
                                                            <i class="fas fa-door-open text-success me-1"></i>
                                                            {{ $profile->room->name }}
                                                        </span>
                                                    @else
                                                        <span class="text-secondary">
                                                            <i class="fas fa-minus-circle me-1"></i>
                                                            Unassigned
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ $profile->created_at->format('M d, Y') }}
                                                    </small>
                                                </td>
                                                <td class="text-end">
                                                    <div class="btn-group" role="group">
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info" 
                                                            onclick="showProfileInfo({{ $profile->id }})"
                                                            data-bs-toggle="tooltip" 
                                                            title="View Profile Info">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                        <button type="button" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                onclick="deleteProfile({{ $profile->id }})"
                                                                data-bs-toggle="tooltip" 
                                                                title="Delete Profile">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Card View -->
                            <div class="d-lg-none">
                                @foreach($profiles as $profile)
                                    <div class="card mb-3 border-0 shadow-sm">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar-sm rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <i class="fas fa-user-circle text-primary" style="font-size: 1.5rem;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $profile->name }}</h6>
                                                    <small class="text-muted">{{ $profile->email }}</small>
                                                </div>
                                            </div>
                                            
                                            <div class="row mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Room</small>
                                                    @if($profile->room)
                                                        <span class="text-dark">
                                                            <i class="fas fa-door-open text-success me-1"></i>
                                                            {{ $profile->room->name }}
                                                        </span>
                                                    @else
                                                        <span class="text-secondary">
                                                            <i class="fas fa-minus-circle me-1"></i>
                                                            Unassigned
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="col-6 text-end">
                                                    <small class="text-muted d-block">Created</small>
                                                    <span>{{ $profile->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-danger flex-fill"
                                                        onclick="deleteProfile({{ $profile->id }})">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Profile Registration Modal -->
        <div class="modal fade" id="profileRegistrationModal" tabindex="-1" aria-labelledby="profileRegistrationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title" id="profileRegistrationModalLabel">
                            <i class="fas fa-user-plus me-2"></i>
                            Register New Profile
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form id="profileRegistrationForm" method="POST" action="{{ route('profile.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-4">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input id="name" type="text" class="form-control" name="name" required autofocus 
                                           placeholder="Enter full name">
                                </div>
                                <div class="invalid-feedback" id="error-name"></div>
                            </div>

                            <div class="mb-4">
                                <label for="room_name" class="form-label fw-semibold">Room Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-door-open"></i>
                                    </span>
                                    <input id="room_name" type="text" class="form-control" name="room_name" required 
                                           placeholder="Enter room name">
                                </div>
                                <div class="invalid-feedback" id="error-room_name"></div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control" name="password" required 
                                           placeholder="Enter secure password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-password"></div>
                            </div>

                            <div class="mb-0">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required 
                                           placeholder="Confirm your password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-password_confirmation"></div>
                            </div>
                        </div>
                        
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                            <button type="submit" id="registerButton" class="btn btn-primary">
                                <i class="fas fa-user-plus me-1"></i>Register Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Profile Modal removed - edit profile functionality no longer available -->
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        // Show profile info function
        function showProfileInfo(profileId) {
            fetch(`/profile/${profileId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.profile) {
                    const modalContent = `
                        <div class="modal fade" id="profileInfoModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-user-circle me-2"></i>
                                            Profile Information
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="card border-0 shadow-sm">
                                                    <div class="card-body">
                                                        <div class="text-center mb-4">
                                                            <div class="avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                                                                <span class="text-primary fw-bold" style="font-size: 2rem;">
                                                                    ${data.profile.name.charAt(0).toUpperCase()}
                                                                </span>
                                                            </div>
                                                            <h5 class="mb-1">${data.profile.name}</h5>
                                                            <p class="text-muted mb-0">${data.profile.email}</p>
                                                        </div>
                                                        
                                                        <div class="row g-3">
                                                            <div class="col-12">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-id-badge text-primary me-2"></i>
                                                                    <div>
                                                                        <small class="text-muted d-block">User ID</small>
                                                                        <span class="fw-semibold">${data.profile.id}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-12">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                                    <div>
                                                                        <small class="text-muted d-block">Email Address</small>
                                                                        <span class="fw-semibold">${data.profile.email}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-12">
                                                                <div class="d-flex align-items-center">
                                                                    ${data.profile.room ? 
                                                                        `<i class="fas fa-door-open text-success me-2"></i>
                                                                        <div>
                                                                            <small class="text-muted d-block">Assigned Room</small>
                                                                            <span class="fw-semibold">${data.profile.room.name}</span>
                                                                        </div>` : 
                                                                        `<i class="fas fa-minus-circle text-secondary me-2"></i>
                                                                        <div>
                                                                            <small class="text-muted d-block">Room Assignment</small>
                                                                            <span class="fw-semibold text-secondary">Not Assigned</span>
                                                                        </div>`
                                                                    }
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-12">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                                    <div>
                                                                        <small class="text-muted d-block">Member Since</small>
                                                                        <span class="fw-semibold">${data.profile.created_at}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            <i class="fas fa-times me-1"></i>Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    // Remove existing modal if present
                    const existingModal = document.getElementById('profileInfoModal');
                    if (existingModal) {
                        existingModal.remove();
                    }

                    // Add modal to document
                    document.body.insertAdjacentHTML('beforeend', modalContent);

                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('profileInfoModal'));
                    modal.show();
                } else {
                    throw new Error(data.message || 'Failed to load profile information');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Failed to load profile information. Please try again.');
            });
        }

        // Handle form submission via AJAX
        document.getElementById('profileRegistrationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitButton = document.getElementById('registerButton');
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Registering...';
            
            // Clear previous errors
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showSuccessMessage(data.message || 'Profile registered successfully!');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('profileRegistrationModal'));
                    modal.hide();
                    
                    // Reset form
                    form.reset();
                    
                    // Reload page to show new profile
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Show error message
                    showErrorMessage(data.message || 'Registration failed. Please try again.');
                    
                    // Display validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(`error-${field}`);
                            const inputElement = document.getElementById(field);
                            
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                            }
                            if (inputElement) {
                                inputElement.classList.add('is-invalid');
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('An error occurred. Please try again.');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-user-plus me-1"></i>Register Profile';
            });
        });

        // Show success message
        function showSuccessMessage(message) {
            const successDiv = document.getElementById('dynamicSuccessMessage');
            const messageText = document.getElementById('successMessageText');
            
            messageText.textContent = message;
            successDiv.style.display = 'block';
            
            setTimeout(() => {
                successDiv.style.display = 'none';
            }, 5000);
        }

        // Show error message
        function showErrorMessage(message) {
            const errorDiv = document.getElementById('dynamicErrorMessage');
            const messageText = document.getElementById('errorMessageText');
            
            messageText.textContent = message;
            errorDiv.style.display = 'block';
            
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        // Delete profile function
        function deleteProfile(profileId) {
            if (confirm('Are you sure you want to delete this profile?')) {
                fetch(`/profile/${profileId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccessMessage('Profile deleted successfully!');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showErrorMessage(data.message || 'Failed to delete profile.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showErrorMessage('An error occurred while deleting the profile.');
                });
            }
        }

        // Handle edit profile
        function openEditModal(profileId) {
            fetch(`/profile/${profileId}/edit`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.profile) {
                    const profile = data.profile;
                    document.getElementById('edit_name').value = profile.name;
                    document.getElementById('edit_room_name').value = profile.room ? profile.room.name : '';
                    
                    // Update form action URL
                    document.getElementById('profileEditForm').action = `/profile/${profileId}`;
                    
                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editProfileModal'));
                    editModal.show();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Failed to load profile data');
            });
        }

        // Handle edit form submission
        document.getElementById('profileEditForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const formData = new FormData(form);
            const submitButton = document.getElementById('updateButton');
            
            // Disable submit button
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Updating...';
            
            // Clear previous errors
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message || 'Profile updated successfully!');
                    
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                    modal.hide();
                    
                    // Reload page to show updated profile
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showErrorMessage(data.message || 'Update failed. Please try again.');
                    
                    // Display validation errors
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const errorElement = document.getElementById(`error-edit-${field}`);
                            const inputElement = document.getElementById(`edit_${field}`);
                            
                            if (errorElement) {
                                errorElement.textContent = data.errors[field][0];
                            }
                            if (inputElement) {
                                inputElement.classList.add('is-invalid');
                            }
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('An error occurred while updating the profile.');
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-save me-1"></i>Save Changes';
            });
        });

        // Password toggle functionality
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('password_confirmation');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password toggle functionality for edit form
        document.getElementById('toggleEditPassword').addEventListener('click', function() {
            const password = document.getElementById('edit_password');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('toggleEditPasswordConfirm').addEventListener('click', function() {
            const password = document.getElementById('edit_password_confirmation');
            const icon = this.querySelector('i');
            
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Ensure CSRF token is available
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }

        // Add smooth animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate cards on load
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'all 0.3s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });

          document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('profileRegistrationForm');
    const registerButton = document.getElementById('registerButton');

    const successMessageContainer = document.getElementById('dynamicSuccessMessage');
    const successMessageText = document.getElementById('successMessageText');
    const errorMessageContainer = document.getElementById('dynamicErrorMessage');
    const errorMessageText = document.getElementById('errorMessageText');

    let isSubmitting = false;
    let lastSubmissionTime = 0;
    const SUBMISSION_COOLDOWN = 2000; // 2 seconds cooldown

    form.addEventListener('submit', function (event) {
        event.preventDefault();

        // Prevent duplicate submissions
        if (isSubmitting || Date.now() - lastSubmissionTime < SUBMISSION_COOLDOWN) {
            errorMessageText.textContent = 'Please wait before submitting again.';
            errorMessageContainer.style.display = 'block';
            return;
        }

        isSubmitting = true;
        lastSubmissionTime = Date.now();

        // Clear previous errors
        ['name', 'room_name', 'password'].forEach(field => {
            const input = form.querySelector(`[name="${field}"]`);
            input.classList.remove('is-invalid');
            const errorDiv = document.getElementById(`error-${field}`);
            if (errorDiv) {
                errorDiv.textContent = '';
            }
        });
        errorMessageContainer.style.display = 'none';
        successMessageContainer.style.display = 'none';

        registerButton.disabled = true;
        registerButton.textContent = 'Registering...';

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': formData.get('_token'),
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errData => {
                    throw errData;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                successMessageText.textContent = data.message || 'Profile registered successfully.';
                successMessageContainer.style.display = 'block';

                // Reset form
                form.reset();

                // Close modal after short delay
                setTimeout(() => {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('profileRegistrationModal'));
                    if (modal) {
                        modal.hide();
                    }
                    successMessageContainer.style.display = 'none';
                }, 2000);
            }
        })
        .catch(errorData => {
            if (errorData.errors) {
                Object.keys(errorData.errors).forEach(field => {
                    const input = form.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                    }
                    const errorDiv = document.getElementById(`error-${field}`);
                    if (errorDiv) {
                        errorDiv.textContent = errorData.errors[field][0];
                    }
                });
            } else {
                errorMessageText.textContent = errorData.message || 'An error occurred.';
                errorMessageContainer.style.display = 'block';
            }
        })
        .finally(() => {
            isSubmitting = false;
            registerButton.disabled = false;
            registerButton.textContent = 'Register';
        });
    });
});

    </script>



    <style>
        .avatar-sm {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }
        
        .btn-group .btn {
            transition: all 0.2s ease;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .modal-content {
            border-radius: 0.75rem;
        }
        
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        
        .form-control {
            border-left: none;
        }
        
        .form-control:focus {
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }
        
        .form-control:focus + .input-group-text {
            border-color: #86b7fe;
        }

        .password-info-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .password-info-card .card-body {
            padding: 1.25rem;
        }

        .password-status-badge {
            display: inline-block;
            padding: 0.25em 0.75em;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .password-status-badge.set {
            background-color: #d4edda;
            color: #155724;
        }

        .password-status-badge.not-set {
            background-color: #fff3cd;
            color: #856404;
        }

        .password-update-info {
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        #currentPassword {
            background-color: #fff;
        }

        #currentPassword:read-only {
            cursor: default;
        }

        #toggleCurrentPassword {
            border-left: none;
            background-color: #fff;
        }

        #toggleCurrentPassword:hover {
            background-color: #f8f9fa;
        }

        #toggleCurrentPassword:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
    </style>
</x-main-layout>