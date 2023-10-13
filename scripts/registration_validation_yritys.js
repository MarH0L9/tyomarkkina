document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('registrationFormYritys');
    const inputs = form.querySelectorAll('input[required]');

    inputs.forEach((input) => {
        input.addEventListener('input', function () {
            if (input.checkValidity()) {
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
            }
        });
    });

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    const emailInput = document.getElementById('email');
    const confirmEmailInput = document.getElementById('repeatEmail');

    function validateEmailFormat(emailField) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(emailField.value);
    }

    function checkEmailMatch() {
        if (validateEmailFormat(emailInput) && emailInput.value === confirmEmailInput.value) {
            confirmEmailInput.setCustomValidity('');
            confirmEmailInput.classList.remove('is-invalid');
            confirmEmailInput.classList.add('is-valid');
            emailInput.setCustomValidity('');
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
        } else if (!validateEmailFormat(emailInput)) {
            emailInput.setCustomValidity('Anna kelvollinen sähköpostiosoite.');
            emailInput.classList.add('is-invalid');
            emailInput.classList.remove('is-valid');
        } else {
            confirmEmailInput.setCustomValidity('Sähköposti ei täsmää.');
            confirmEmailInput.classList.add('is-invalid');
            confirmEmailInput.classList.remove('is-valid');
        }
    }

    emailInput.addEventListener('input', checkEmailMatch);
    confirmEmailInput.addEventListener('input', checkEmailMatch);

    const passwordInput = document.getElementById('pssw');
    const confirmPasswordInput = document.getElementById('repeatPssw');

    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (password.length < 8) {
            passwordInput.setCustomValidity('Salasanan on oltava vähintään 8 merkkiä pitkä.');
            passwordInput.classList.add('is-invalid');
            passwordInput.classList.remove('is-valid');
            return;
        } else {
            passwordInput.setCustomValidity('');
            passwordInput.classList.remove('is-invalid');
            passwordInput.classList.add('is-valid');
        }

        if (password === confirmPassword) {
            confirmPasswordInput.setCustomValidity('');
            confirmPasswordInput.classList.remove('is-invalid');
            confirmPasswordInput.classList.add('is-valid');
        } else {
            confirmPasswordInput.setCustomValidity('Salasanat ei ole samallaisia, yritä uudestaan.');
            confirmPasswordInput.classList.add('is-invalid');
            confirmPasswordInput.classList.remove('is-valid');
        }
    }

    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmPasswordInput.addEventListener('input', checkPasswordMatch);
});
