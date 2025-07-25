<x-main-layout>
    <div class="container-fluid py-4">

        <div id="dynamicSuccessMessage" style="position: fixed; top: 10px; right: 10px; z-index: 1050; width: auto; max-width: 300px; display: none;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <span id="successMessageText"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td>{{ $profile->name }}</td>
                                    <td>{{ $profile->email }}</td>
                                    <td>{{ $profile->room ? $profile->room->name : '-' }}</td>
                                    <td>{{ $profile->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
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
</x-main-layout>
