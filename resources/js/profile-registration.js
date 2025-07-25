document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('profileRegistrationForm');
    const registerButton = document.getElementById('registerButton');

    const successMessageContainer = document.getElementById('dynamicSuccessMessage');
    const successMessageText = document.getElementById('successMessageText');
    const errorMessageContainer = document.getElementById('dynamicErrorMessage');
    const errorMessageText = document.getElementById('errorMessageText');

    form.addEventListener('submit', function (event) {
        event.preventDefault();

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
            registerButton.disabled = false;
            registerButton.textContent = 'Register';
        });
    });
});
