function hide(inputT, inputW, buttonT) {
    const inputText = document.getElementById(inputT);
    const confirmInput = document.getElementById(inputW);
    const btnToggle = document.getElementById(buttonT);

    if(inputText.type === 'password') {
        inputText.type = 'text';
        confirmInput.type = 'text';
        btnToggle.textContent = 'Hide password 🔒︎';
        btnToggle.style.backgroundColor = "#010254";
        btnToggle.style.color = "#fdfdff";
    }
    else {
        confirmInput.type = 'password';
        inputText.type = 'password';
        btnToggle.textContent = 'Show password ꗃ';
        btnToggle.style.backgroundColor = "#fdfdff";
        btnToggle.style.color = " #010254";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const resetSuccessPopup = document.getElementById('resetSuccessPopup');

    if (!resetSuccessPopup || resetSuccessPopup.dataset.show !== 'true') {
        return;
    }

    resetSuccessPopup.classList.add('show');
    resetSuccessPopup.parentElement.classList.add('show');
});

