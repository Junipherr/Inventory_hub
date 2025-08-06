<x-main-layout>
    <div class="container-fluid py-4">

        <!-- Dynamic Success Message -->
        <div id="dynamicSuccessMessage" style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <span id="successMessageText"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <!-- Dynamic Error Message -->
        <div id="dynamicErrorMessage" style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> <span id="errorMessageText"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                    {{ __('Profiles') }}
                </h2>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#profileRegistrationModal">
                    Register New Profile
                </button>

                <!-- Profiles Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Room</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($profiles as $profile)
                                <tr>
                                    <td>{{ $profile->name }}</td>
                                    <td>{{ $profile->email }}</td>
                                    <td>{{ $profile->room ? $profile->room->name : '-' }}</td>
                                    <td>{{ $profile->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('profile.edit', $profile->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteProfile({{ $profile->id }})">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No profiles found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Profile Registration Modal -->
                <div class="modal fade" id="profileRegistrationModal" tabindex="-1" aria-labelledby="profileRegistrationModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="profileRegistrationForm" method="POST" action="{{ route('profile.store') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="profileRegistrationModalLabel">Register New Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input id="name" type="text" class="form-control" name="name" required autofocus>
                                        <div class="invalid-feedback" id="error-name"></div>
                                    </div>
                                    {{-- <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input id="email" type="email" class="form-control" name="email" required>
                                        <div class="invalid-feedback" id="error-email"></div>
                                    </div> --}}
                                    <div class="mb-3">
                                        <label for="room_name" class="form-label">Room Name</label>
                                        <input id="room_name" type="text" class="form-control" name="room_name" required>
                                        <div class="invalid-feedback" id="error-room_name"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input id="password" type="password" class="form-control" name="password" required>
                                        <div class="invalid-feedback" id="error-password"></div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                                        <div class="invalid-feedback" id="error-password_confirmation"></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" id="registerButton" class="btn btn-primary">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX handling -->
    <script>
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
                submitButton.innerHTML = 'Register';
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

        // Ensure CSRF token is available
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }
    </script>
</x-main-layout>