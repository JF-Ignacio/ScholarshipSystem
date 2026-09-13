
document.getElementById('filePicture').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    // Optional: quick client-side size check (server MUST still validate this)
    const maxSizeMB = 5;
    if (file.size > maxSizeMB * 1024 * 1024) {
        alert(`File too large. Max size is ${maxSizeMB}MB.`);
        this.value = ""; // reset input
        return;
    }

    const reader = new FileReader();
    reader.onload = function(event) {
        document.getElementById('avatarPreview').src = event.target.result;
    };
    reader.readAsDataURL(file);

    // Auto-submit the form so it uploads right after selection
    document.getElementById('profile_form').submit();
});
