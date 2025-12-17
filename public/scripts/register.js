document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('register-form');
    const nameInput = document.getElementById('name');
    const surnameInput = document.getElementById('surname');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm-password');
    const errorDiv = document.getElementById('js-error-message');

    function showError(input, message) {
        if (message) {
            errorDiv.innerText = message;
            errorDiv.style.display = 'block';
            if(input) input.style.borderColor = 'red';
        } else {
            errorDiv.style.display = 'none';
            errorDiv.innerText = '';
            if(input) input.style.borderColor = ''; 
        }
    }

    function validateField(input) {
        const value = input.value.trim();

        if (input === nameInput || input === surnameInput) {
            const nameRegex = /^[a-zA-ZąćęłńóśźżĄĆĘŁŃÓŚŹŻ -]{2,}$/;
            if (!nameRegex.test(value)) {
                return "Names must be at least 2 characters long and contain only letters.";
            }
        }

        if (input === passwordInput) {
            if (value.length < 6) {
                return "Password must be at least 6 characters long.";
            }
        }

        if (input === confirmPasswordInput) {
            if (value !== passwordInput.value) {
                return "Passwords do not match.";
            }
        }

        return ""; 
    }

    let timeout = null;
    
    function handleInput(event) {
        const input = event.target;
        
        clearTimeout(timeout);

        timeout = setTimeout(() => {
            const errorMessage = validateField(input);
            showError(input, errorMessage);
        }, 500);
    }

    if (nameInput) nameInput.addEventListener('input', handleInput);
    if (surnameInput) surnameInput.addEventListener('input', handleInput);
    if (passwordInput) passwordInput.addEventListener('input', handleInput);
    if (confirmPasswordInput) confirmPasswordInput.addEventListener('input', handleInput);

    if (form) {
        form.addEventListener('submit', (e) => {
            const errorName = validateField(nameInput);
            const errorSurname = validateField(surnameInput);
            const errorPass = validateField(passwordInput);
            const errorConfirm = validateField(confirmPasswordInput);

            const firstError = errorName || errorSurname || errorPass || errorConfirm;

            if (firstError) {
                e.preventDefault(); 
                showError(null, firstError);
            }
        });
    }
});