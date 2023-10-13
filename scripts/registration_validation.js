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
 const emailInput = document.getElementById('email');
 const confirmEmailInput = document.getElementById('confirmEmail');

 function checkEmailMatch() {
     if (emailInput.value === confirmEmailInput.value) {
         confirmEmailInput.setCustomValidity('');
         confirmEmailInput.classList.remove('is-invalid');
         confirmEmailInput.classList.add('is-valid');
     } else {
         confirmEmailInput.setCustomValidity('Los correos electrónicos no coinciden.');
         confirmEmailInput.classList.add('is-invalid');
         confirmEmailInput.classList.remove('is-valid');
     }
 }

 emailInput.addEventListener('input', checkEmailMatch);
 confirmEmailInput.addEventListener('input', checkEmailMatch);

 // Función que verifica si las contraseñas coinciden en vivo
 function checkPasswordMatch() {
     const password = passwordInput.value;
     const confirmPassword = confirmPasswordInput.value;

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

 // Eventos de escucha para la validación de las contraseñas
 passwordInput.addEventListener('input', checkPasswordMatch);
 confirmPasswordInput.addEventListener('input', checkPasswordMatch);