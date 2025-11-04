document.addEventListener('DOMContentLoaded', () => {
    
    // 1. Znajdź ikonkę oka i pole hasła
    // Używamy .eye-icon, ponieważ masz go już w swoim HTML-u
    const togglePasswordIcon = document.querySelector('.eye-icon');
    const passwordInput = document.getElementById('password');

    // 2. Upewnij się, że oba elementy istnieją na stronie
    if (togglePasswordIcon && passwordInput) {
        
        // 3. Dodaj "nasłuchiwacz" na kliknięcie ikonki
        togglePasswordIcon.addEventListener('click', () => {
            
            // 4. Sprawdź, jaki jest aktualny typ pola (password czy text)
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            
            // 5. Ustaw nowy typ dla pola hasła
            passwordInput.setAttribute('type', type);
            
            // 6. Zmień ikonkę (z "visibility" na "visibility_off" i odwrotnie)
            if (type === 'text') {
                // Hasło jest widoczne, pokaż ikonę "przekreślonego oka"
                togglePasswordIcon.textContent = 'visibility_off';
            } else {
                // Hasło jest ukryte, pokaż normalną ikonę "oka"
                togglePasswordIcon.textContent = 'visibility';
            }
        });
    }
});