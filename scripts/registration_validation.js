// Añade un evento de escucha para todos los campos requeridos
const form = document.getElementById('registrationForm');
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

// Verifica si el correo electrónico ya está en uso
const warningMessage = document.querySelector('.alert.alert-warning');

if (warningMessage) {
    // Si el correo electrónico ya está en uso, coloca el cursor en el campo de correo electrónico
    const emailInput = document.getElementById('validationCustomUsername');
    if (emailInput) {
        emailInput.focus();
    }
}

// Validación de las contraseñas
const passwordInput = document.getElementById('validationCustom03');
const confirmPasswordInput = document.getElementById('validationCustom04');

// Validación de los correos electrónicos
const emailInput = document.getElementById('validationCustomUsername');
const confirmEmailInput = document.getElementById('repeatEmail');

function checkEmailMatch() {
    if (emailInput.value === confirmEmailInput.value) {
        confirmEmailInput.setCustomValidity('');
        confirmEmailInput.classList.remove('is-invalid');
        confirmEmailInput.classList.add('is-valid');
    } else {
        confirmEmailInput.setCustomValidity('Sähköpostit eivät täsmää.');
        confirmEmailInput.classList.add('is-invalid');
        confirmEmailInput.classList.remove('is-valid');
    }
}

emailInput.addEventListener('input', checkEmailMatch);
confirmEmailInput.addEventListener('input', checkEmailMatch);

// Expresión regular para validar que el campo contiene solo letras y espacios en blanco
const nameRegex = /^[A-Za-z\s]+$/;

// Función para verificar si un campo cumple con la expresión regular
function validateName(input) {
    if (nameRegex.test(input.value)) {
        input.setCustomValidity('');
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    } else {
        input.setCustomValidity('Vain kirjaimet ja välilyönnit ovat sallittuja.');
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
    }
}

// Agregar eventos de escucha para la validación de 'etunimi' y 'sukunimi'
const etunimiInput = document.getElementById('etunimi');
const sukunimiInput = document.getElementById('sukunimi');

etunimiInput.addEventListener('input', function () {
    validateName(etunimiInput);
});

sukunimiInput.addEventListener('input', function () {
    validateName(sukunimiInput);
});

// Eventos de escucha para la validación de las contraseñas
passwordInput.addEventListener('input', checkPasswordMatch);
confirmPasswordInput.addEventListener('input', checkPasswordMatch);

function checkPasswordMatch() {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    if (password === confirmPassword) {
        confirmPasswordInput.setCustomValidity('');
        confirmPasswordInput.classList.remove('is-invalid');
        confirmPasswordInput.classList.add('is-valid');
    } else {
        confirmPasswordInput.setCustomValidity('Salasanat eivät täsmää.');
        confirmPasswordInput.classList.add('is-invalid');
        confirmPasswordInput.classList.remove('is-valid');
    }
}
