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
                        @if($profiles->isEmpty())
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
                                                        <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            <span class="text-primary fw-bold">
                                                                {{ strtoupper(substr($profile->name, 0, 1)) }}
                                                            </span>
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
                                                        <span class="badge bg-light text-dark">
                                                            <i class="fas fa-door-open me-1"></i>
                                                            {{ $profile->room->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-minus me-1"></i>
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
                                                        <a href="javascript:void(0)" 
                                                           onclick="openEditModal({{ $profile->id }})"
                                                           class="btn btn-sm btn-outline-primary" 
                                                           data-bs-toggle="tooltip" 
                                                           title="Edit Profile">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
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
                                                <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                    <span class="text-primary fw-bold">
                                                        {{ strtoupper(substr($profile->name, 0, 1)) }}
                                                    </span>
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
                                                        <span class="badge bg-light text-dark">
                                                            {{ $profile->room->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">Unassigned</span>
                                                    @endif
                                                </div>
                                                <div class="col-6 text-end">
                                                    <small class="text-muted d-block">Created</small>
                                                    <span>{{ $profile->created_at->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                            
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('profile.edit', $profile->id) }}" 
                                                   class="btn btn-sm btn-outline-primary flex-fill">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
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

        <!-- Edit Profile Modal -->
        <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-primary text-white border-0">
                        <h5 class="modal-title" id="editProfileModalLabel">
                            <i class="fas fa-user-edit me-2"></i>
                            Edit Profile
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <form id="profileEditForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-4">
                                <label for="edit_name" class="form-label fw-semibold">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input id="edit_name" type="text" class="form-control" name="name" required 
                                           placeholder="Enter full name">
                                </div>
                                <div class="invalid-feedback" id="error-edit-name"></div>
                            </div>

                            <div class="mb-4">
                                <label for="edit_room_name" class="form-label fw-semibold">Room Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-door-open"></i>
                                    </span>
                                    <input id="edit_room_name" type="text" class="form-control" name="room_name" required 
                                           placeholder="Enter room name">
                                </div>
                                <div class="invalid-feedback" id="error-edit-room_name"></div>
                            </div>

                            <div class="mb-4">
                                <label for="edit_password" class="form-label fw-semibold">New Password (optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="edit_password" type="password" class="form-control" name="password"
                                           placeholder="Enter new password">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleEditPassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-edit-password"></div>
                            </div>

                            <div class="mb-0">
                                <label for="edit_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="edit_password_confirmation" type="password" class="form-control" 
                                           name="password_confirmation" placeholder="Confirm new password">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleEditPasswordConfirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="invalid-feedback" id="error-edit-password_confirmation"></div>
                            </div>
                        </div>
                        
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i>Cancel
                            </button>
                            <button type="submit" id="updateButton" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

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
    </style>
</x-main-layout>
