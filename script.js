// Optional: Add JavaScript for client-side validation (if needed).
document.getElementById('jsonForm').addEventListener('submit', function (e) {
    const fileInput = document.getElementById('jsonFile');
    if (!fileInput.value.endsWith('.json')) {
        e.preventDefault();
        alert('Please upload a valid JSON file.');
    }
});
