document.getElementById('files').addEventListener('change', function(event) {
    const [file] = event.target.files;
    if (file) {
        const uploadIcon = document.getElementById('upload-icon');
        uploadIcon.src = URL.createObjectURL(file);
        uploadIcon.onload = function() {
            URL.revokeObjectURL(uploadIcon.src); // Free memory
        }
    }
});

document.querySelector('.click_files').addEventListener('click', function() {
    document.getElementById('files').click();
});