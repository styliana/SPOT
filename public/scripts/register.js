// Czekamy na załadowanie całej zawartości strony
document.addEventListener('DOMContentLoaded', () => {
    
    // Znajdujemy formularz i interesujące nas pola
    const registerForm = document.getElementById('register-form');
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm-password');
    const errorMessageDiv = document.getElementById('js-error-message');

    // Sprawdzamy, czy wszystkie elementy istnieją na stronie (żeby uniknąć błędów)
    if (registerForm && passwordField && confirmPasswordField && errorMessageDiv) {
        
        // Dodajemy 'nasłuchiwacz' na zdarzenie 'submit' formularza
        registerForm.addEventListener('submit', (event) => {
            
            // Pobieramy aktualne wartości z pól
            const password = passwordField.value;
            const confirmPassword = confirmPasswordField.value;

            // Sprawdzamy, czy hasła są identyczne
            if (password !== confirmPassword) {
                // 1. ZATRZYMUJEMY wysyłanie formularza do serwera
                event.preventDefault(); 
                
                // 2. Wyświetlamy komunikat o błędzie w przygotowanym divie
                errorMessageDiv.textContent = 'Hasła nie są identyczne. Spróbuj ponownie.';
                errorMessageDiv.style.display = 'block'; // Pokazujemy div z błędem
            } else {
                // 3. Jeśli hasła są OK, ukrywamy błąd (jeśli był wcześniej widoczny)
                errorMessageDiv.style.display = 'none';
                errorMessageDiv.textContent = '';
            }
        });
    }
});