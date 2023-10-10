<?php
require 'config.php'; 

$pdo = new PDO($dsn, $username, $password);

function escape($pdo, $value) {
    return mysqli_real_escape_string($pdo, htmlspecialchars($value));
}

$message = ''; // Muuttuja joka näyttää viestin käyttäjälle
$stmt = null; // Inicializar $stmt

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['email'];
    $psw = $_POST['pssw'];

    $hashedPsw = password_hash($psw, PASSWORD_DEFAULT);  // Hashea la contraseña
    // Chekataan onko sähköposti jo käytössä
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($checkEmailQuery);
    $stmt->execute([$Email]);
    $result = $stmt->fetchAll();

    if ($stmt->rowCount() > 0) {
        $message = '<div class="alert alert-warning" role="alert">Sähköpostiosotie on käytetty. <a href="login.php">Kirjaudu sisään</a> tai <a href="register.php">muuta sähköposti</a>.</div>';
    } else {
        // Jos sähköposti ei ole käytössä, lisää tiedot tietokantaan
        $token = bin2hex(random_bytes(50)); //validation token

        $query = "INSERT INTO users (email, pwd, verification_token) VALUES (?, ?,?)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$Email, $hashedPsw, $token])) {
            $message = "Tiedot on lisätty järjestelmään onnistuneesti. Voit nyt kirjautua sisään painamalla tästä <a href='login.php'>Kirjaudu sisään</a>.";
        } else {
            $message = "Tiedot ei voitu lisätä, virhe: " . mysqli_error($yhteys);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <?php include 'header.php'; ?>
</head>
<body>
<div class="container regi" style="margin-top: 100px;">
    <fieldset>
        <legend>Rekisteröinti</legend>
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo (strpos($message, "virhe") !== false) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="container mt-5" style="margin-top: 10px;">
            <form action="register.php" method="POST" class="needs-validation" id="registrationForm" novalidate>
                <div class="mb-3">
                    <label for="validationCustomUsername" class="form-label">Sähköposti</label>
                    <div class="input-group has-validation">
                        <input type="email" class="form-control" id="validationCustomUsername" name="email" pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" placeholder="nimisukunimi@palvelu.fi"  required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                        <div class="invalid-feedback">
                            Kirjoita sähköposti osoite "user@email.".
                        </div>
                        
                    </div>
                </div>
                <div class="mb-3">
                    <label for="validationCustom03" class="form-label">Salasana</label>
                    <input type="password" class="form-control" id="validationCustom03" name="pssw" minlength="8" required>
                    <div class="invalid-feedback">
                        Salasana pitää olla vähintään 8 merkkiä pitkä.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="validationCustom04" class="form-label">Salasana uudestaan</label>
                    <input type="password" class="form-control" id="validationCustom04" name="confirm_password"  placeholder="Kirjoita salasana uudestaan" minlength="8" required>
                    <div class="invalid-feedback">
                        Salasanat eivät ole samallai.
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary" type="submit">Rekisteröidy</button>
                </div>
            </form>
        </div>
    </fieldset>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"> </script> 
    <script>
        // lisää tapahtumankuuntelija kaikille vaadituille kentille
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

          // Tarkista onko sähköposti jo käytössä
    const warningMessage = document.querySelector('.alert.alert-warning');
    
    if (warningMessage) {
        // Jos sähköposti on jo käytössä, siirrä kursori sähköposti kenttään
        const emailInput = document.getElementById('validationCustomUsername');
        if (emailInput) {
            emailInput.focus();
        }
    }


        //Salasanojen tarkistus
        const passwordInput = document.getElementById('validationCustom03');
        const confirmPasswordInput = document.getElementById('validationCustom04');

        // Funktio joka tarkistaa onko salasanat samallaisia livenä
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

        // Tapahtumankuuntelija salasanojen tarkistusta varten
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    </script>
</body>
<?php include 'footer.html'; ?>
</html>