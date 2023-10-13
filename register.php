<?php
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


require 'config.php'; 

$pdo = new PDO($dsn, $username, $password);


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
    

    if ($stmt->rowCount() > 0) {
        $message = '<div class="alert alert-warning" role="alert">Sähköpostiosotie on käytetty. <a href="login.php">Kirjaudu sisään</a> tai <a href="register.php">muuta sähköposti</a>.</div>';
    } else {
        // Jos sähköposti ei ole käytössä, lisää tiedot tietokantaan
        $token = bin2hex(random_bytes(50)); //validation token

        $query = "INSERT INTO users (email, pwd, verification_token) VALUES (?,?,?)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$Email, $hashedPsw, $token])) {
            $lastUserId = $pdo->lastInsertId();

            // Inserta el perfil del usuario en user_profile
            $etunimi = $_POST['etunimi'];
            $sukunimi = $_POST['sukunimi'];
            $puhelin = $_POST['puhelin'];

            $profileQuery = "INSERT INTO user_profile (user_id, etunimi, sukunimi, Email, puhelin) VALUES (?, ?, ?, ?, ?)";
            $profileStmt = $pdo->prepare($profileQuery);
            if ($profileStmt->execute([$lastUserId, $etunimi, $sukunimi, $Email, $puhelin])) {
                $message = "Tiedot on lisätty järjestelmään onnistuneesti. Voit nyt kirjautua sisään painamalla tästä <a href='login.php'>Kirjaudu sisään</a>.";    
            } else {
                $message = "Käyttäjäprofiilin luomisessa tapahtui virhe:: " . $pdo->errorInfo()[2];
            }            
        } else {
            $message = "Tiedot ei voitu lisätä, virhe: " . $pdo->errorInfo()[2];
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
    <main>
    <fieldset>
        
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo (strpos($message, "virhe") !== false) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="container mt-5" style="margin-top: 10px;">
        <div>
        
        <p>Oletko Yritys?<a href="Register_yritys.php">Luo tili tästä</a></p>
        </div>
        <div class="row justify-content-center">
            
        <div class="col-md-6">
        <legend>Rekisteröinti</legend> 
        
            <form action="register.php" method="POST" class="needs-validation" id="registrationForm" novalidate>
                <div class="mb-3">
                    <label for="validationCustomUsername" class="form-label">Sähköposti*</label>
                    <div class="input-group has-validation">
                        <input type="email" class="form-control" id="validationCustomUsername" name="email" pattern="^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$" placeholder="nimisukunimi@palvelu.fi"  required value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                        <div class="invalid-feedback">
                            Kirjoita sähköposti osoite "user@email.".
                        </div>                        
                    </div>
                </div>

                <div class="mb-3">
                    <label for="repeatEmail" class="form-label">Toista sähköposti*</label>
                    <div class="input-group has-validation">
                        <input type="email" class="form-control" id="repeatEmail" name="repeatEmail" required value="<?php echo isset($_POST['repeatEmail']) ? $_POST['repeatEmail'] : ''; ?>">
                        <div class="invalid-feedback">
                            Sähköpostiosoitteet eivät ole samat.
                        </div>
                    </div>
                </div>


                <div class="mb-3">
                    <label for="validationCustom03" class="form-label">Salasana*</label>
                    <input type="password" class="form-control" id="validationCustom03" name="pssw" minlength="8" required>
                    <div class="invalid-feedback">
                        Salasana pitää olla vähintään 8 merkkiä pitkä.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="validationCustom04" class="form-label">Salasana uudestaan*</label>
                    <input type="password" class="form-control" id="validationCustom04" name="confirm_password"  placeholder="Kirjoita salasana uudestaan" minlength="8" required>
                    <div class="invalid-feedback">
                        Salasanat eivät ole samallai.
                    </div>
                </div>
                <div class="mb-3">
                <label for="etunimi" class="form-label">Etunimi*</label>
                <input type="text" class="form-control" id="etunimi" name="etunimi" required>
                <div class="invalid-feedback">
                    Kirjoita sinun etunimi.
                </div>
            </div>
            <div class="mb-3">
                <label for="sukunimi" class="form-label">Sukunimi*</label>
                <input type="text" class="form-control" id="sukunimi" name="sukunimi" required>
                <div class="invalid-feedback">
                    Kirjoita sinun sukunimi.
                </div>
            </div>
            <div class="mb-3">
                <label for="puhelin" class="form-label">Puhelin</label>
                <input type="puh" class="form-control" id="puhelin" name="puhelin" >
                <div class="invalid-feedback">
                    Kirjoita sinun puhelinnumero.
                </div>
            </div>

                <div class="mb-3">
                    <div class="text-center">
                    <button class="btn btn-primary" type="submit">Rekisteröidy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    </fieldset>
    </main>
    <?php include 'footer.html'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"> </script> 
    <script src="scripts/registration_validation.js"></script>
    
</body>

</html>
