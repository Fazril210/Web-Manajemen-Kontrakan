document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('deleteModal');
    let deleteUrl = '';

    // Update delete buttons to trigger modal
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.onclick = function(e) {
            e.preventDefault();
            deleteUrl = this.href;
            modal.style.display = 'block';
        };
    });

    // Handle cancel button
    document.getElementById('cancelDelete').onclick = function() {
        modal.style.display = 'none';
    };

    // Handle confirm button
    document.getElementById('confirmDelete').onclick = function() {
        window.location.href = deleteUrl;
    };

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
});

document.getElementById('toggleSidebar').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('active');
});
