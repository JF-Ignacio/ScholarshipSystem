// AJAX HELPER

async function postForm(url, form) {
    const formData = new FormData(form);

    const response = await fetch(url, {
        method: "POST",
        body: formData,
        headers: {
            "X-Requested-With" : "XMLHttpRequest"
        }
    });

    return await response.json();
}