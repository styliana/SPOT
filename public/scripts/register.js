document.addEventListener('DOMContentLoaded', () => {
    
    const registerForm = document.getElementById('register-form');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm-password');
    const errorMessageDiv = document.getElementById('js-error-message');

    if (registerForm && passwordField && confirmPasswordField && errorMessageDiv) {

        registerForm.addEventListener('submit', (event) => {
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;
            if (password !== confirmPassword) {
                event.preventDefault(); 
                
                errorMessageDiv.textContent = 'Hasła nie są identyczne. Spróbuj ponownie.';
                errorMessageDiv.style.display = 'block';
            } else {
                errorMessageDiv.style.display = 'none';
                errorMessageDiv.textContent = '';
            }
        });
    }
});