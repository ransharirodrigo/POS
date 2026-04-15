function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.querySelector('.overlay').classList.toggle('show');
}

function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show');
    document.querySelector('.overlay').classList.remove('show');
}

function showAlert(type, message, title = '') {
    if (typeof Swal !== 'undefined') {
        const options = {
            text: message,
            icon: type,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false
        };
        if (title) {
            options.title = title;
        }
        Swal.fire(options);
    }
}

function showDeleteConfirm(title, message, onConfirm) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: title,
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
    }
}

function confirmDelete(id, name) {
    const message = 'Are you sure you want to delete ' + name + '? This action cannot be undone.';
    showDeleteConfirm('Confirm Delete', message, function() {
        document.getElementById('delete-form-' + id).submit();
    });
}

function togglePassword(button) {
    const input = button.closest('.input-group').querySelector('input');
    const icon = button.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    if (body.dataset.success) {
        showAlert('success', body.dataset.success, 'Success');
    }
    if (body.dataset.error) {
        showAlert('error', body.dataset.error, 'Error');
    }
    if (body.dataset.warning) {
        showAlert('warning', body.dataset.warning, 'Warning');
    }
    if (body.dataset.errors) {
        const errors = JSON.parse(body.dataset.errors);
        errors.forEach(function(error) {
            showAlert('error', error, 'Error');
        });
    }

    document.querySelectorAll('[id^="createCustomerForm"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const errorDiv = document.getElementById('createFormErrors');
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    errorDiv.innerHTML = Object.values(data.errors).flat().join('<br>');
                    errorDiv.classList.remove('d-none');
                } else if (data.success) {
                    errorDiv.classList.add('d-none');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('createModal'));
                    modal.hide();
                    this.reset();
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });

        const modal = document.getElementById('createModal');
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                form.reset();
                const errorDiv = document.getElementById('createFormErrors');
                if (errorDiv) errorDiv.classList.add('d-none');
            });
        }
    });

    document.querySelectorAll('[id^="editCustomerForm"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const customerId = this.id.replace('editCustomerForm', '');
            const errorDiv = document.getElementById('editFormErrors' + customerId);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.errors) {
                    errorDiv.innerHTML = Object.values(data.errors).flat().join('<br>');
                    errorDiv.classList.remove('d-none');
                } else if (data.success) {
                    errorDiv.classList.add('d-none');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editModal' + customerId));
                    modal.hide();
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});