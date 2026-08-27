function hide(inputT, buttonT) {
    const inputText = document.getElementById(inputT);
    const btnToggle = document.getElementById(buttonT);

    if(inputText.type === 'password') {
        inputText.type = 'text';
        btnToggle.textContent = 'Hide';
    }
    else {
        inputText.type = 'password';
        btnToggle.textContent = 'Show';
    }
}
