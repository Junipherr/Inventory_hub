// Category
document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('departmentSelect');
    const categoryGroup = document.getElementById('categoryGroup');
    const categorySelect = document.getElementById('categorySelect');

    // Check if required elements exist before initializing
    if (!departmentSelect || !categoryGroup || !categorySelect) {
        console.warn('Category form elements not found - skipping category initialization');
        return;
    }

    function updateCategories() {
            const selectedDepartment = departmentSelect.value;
            if (!selectedDepartment) {
                categoryGroup.style.display = 'none';
                categorySelect.value = '';
                // Hide all optgroups
                Array.from(categorySelect.children).forEach(child => {
                    if (child.tagName.toLowerCase() === 'optgroup') {
                        child.style.display = 'none';
                    }
                });
                return;
            }

            categoryGroup.style.display = 'block';

            // Show only the optgroup matching the selected department
            Array.from(categorySelect.children).forEach(child => {
                if (child.tagName.toLowerCase() === 'optgroup') {
                    if (child.id === selectedDepartment + '_categories') {
                        child.style.display = 'block';
                    } else {
                        child.style.display = 'none';
                    }
                }
            });

            // Reset category select value
            categorySelect.value = '';
        }

        // Initialize categories on page load
        updateCategories();

        // Update categories on department change
        departmentSelect.addEventListener('change', updateCategories);
    });