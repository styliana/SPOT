document.addEventListener('DOMContentLoaded', () => {
    
    const togglePasswordIcon = document.querySelector('.eye-icon');
    const passwordInput = document.getElementById('password');

    if (togglePasswordIcon && passwordInput) {
        
        togglePasswordIcon.addEventListener('click', () => {
            
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            if (type === 'text') {
                togglePasswordIcon.textContent = 'visibility_off';
            } else {
                togglePasswordIcon.textContent = 'visibility';
            }
        });
    }
});