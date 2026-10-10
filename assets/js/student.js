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

document.addEventListener('DOMContentLoaded', () => {
    const profileForm = document.querySelector('[data-ajax-profile]');

    if (!profileForm) return;

    profileForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        const submitButton = profileForm.querySelector('[type="submit"]');
        const originalText = submitButton ? submitButton.textContent : '';

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Saving...';
        }

        try {
            const url = profileForm.dataset.ajaxUrl || profileForm.action;
            const result = await postForm(url, profileForm);

            alert(result.message);

            if (result.success) {
                window.location.reload();
            }
        } catch (error) {
            alert('Something went wrong. Please try again.');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        }
    });
});
