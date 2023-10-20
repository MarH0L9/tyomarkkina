<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require 'config.php'; 

$pdo = new PDO($dsn, $username, $password);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['email'];
    $psw = $_POST['pssw'];
    $userType = 'Yritys';
    $hashedPsw = password_hash($psw, PASSWORD_DEFAULT);
    
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($checkEmailQuery);
    $stmt->execute([$Email]);
    
    if ($stmt->rowCount() > 0) {
        $message = '<div class="alert alert-warning" role="alert">Sähköpostiosotie on käytetty. <a href="login.php">Kirjaudu sisään</a> tai <a href="Register_yritys.php">muuta sähköposti</a>.</div>';
    } else {
        $token = bin2hex(random_bytes(50));

        $query = "INSERT INTO users (email, pwd, verification_token, user_type) VALUES (?,?,?,?)";
        $stmt = $pdo->prepare($query);

        if ($stmt->execute([$Email, $hashedPsw, $token, $userType])) {
            $lastUserId = $pdo->lastInsertId();
            $yrityksen_nimi = $_POST['yrityksen_nimi'];
            $Y_tunnus = $_POST['Y_tunnus'];

            $businessQuery = "INSERT INTO tyonantajat (user_id, yrityksen_nimi, Y_tunnus) VALUES (?,?,?)";
            $businessStmt = $pdo->prepare($businessQuery);
            if ($businessStmt->execute([$lastUserId, $yrityksen_nimi, $Y_tunnus])) {
                $message = "Tiedot on lisätty järjestelmään onnistuneesti. Voit nyt kirjautua sisään painamalla tästä <a href='login.php'>Kirjaudu sisään</a>.";    
            } else {
                $deleteUser = "DELETE FROM users WHERE id = ?";
                $deleteStmt = $pdo->prepare($deleteUser);
                $deleteStmt->execute([$lastUserId]);

                $message = "Liiketoimintaprofiilin luomisessa tapahtui virhe: " . $pdo->errorInfo()[2];
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
    <fieldset>
        
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo (strpos($message, "virhe") !== false) ? 'alert-danger' : 'alert-success'; ?>" role="alert">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        <div class="container mt-5" style="margin-top: 10px;">
        <p>Oletko Yksityishenkilö?<a href="register.php">Luo tili tästä</a></p>
        
        <div class="row justify-content-center">
            
        <div class="col-md-6">
        <legend>Yrityksen Rekisteröinti</legend><hr> 
        
        <form action="register_yritys.php" method="POST" class="needs-validation" id="registrationFormYritys" novalidate>
                <!-- Campos de registro para empresas aquí. Estos pueden variar dependiendo de los requerimientos, aquí hay un ejemplo -->

                <div class="mb-3">
                    <label for="email" class="form-label">Sähköposti*</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Anna kelvollinen sähköpostiosoite email@email.
                    </div>
                </div>

                 <div class="mb-3">
                    <label for="repeatEmail" class="form-label">Toista sähköposti*</label>
                    <input type="email" class="form-control" id="repeatEmail" name="repeatEmail" required>
                    <div class="invalid-feedback">
                        Toista sähköposti.
                    </div>
                </div>

                <!-- Contraseña -->
                <div class="mb-3">
                    <label for="pssw" class="form-label">Salasana*</label>
                    <input type="password" class="form-control" id="pssw" name="pssw" required>
                    <div class="invalid-feedback">
                        Salasana on pakollinen.
                    </div>
                </div>

                <div class="mb-3">
                    <label for="repeatPssw" class="form-label">Toista Salasana*</label>
                    <input type="password" class="form-control" id="repeatPssw" name="repeatPssw" required>
                    <div class="invalid-feedback">
                        Toista salasana.
                    </div>
            </div>
            <div class="mb-3">
                <label for="yrityksen_nimi" class="form-label">Yrityksen nimi*</label>
                <input type="text" class="form-control" id="yrityksen_nimi" name="yrityksen_nimi" required>
                <div class="invalid-feedback">
                    Kirjoita yrityksen nimi.
                </div>
            </div>

                 <!-- Y-tunnus -->
                <div class="mb-3">
                    <label for="Y_tunnus" class="form-label">Y-tunnus*</label>
                    <input type="text" class="form-control" id="Y_tunnus" name="Y_tunnus" required>
                    <div class="invalid-feedback">
                        Kirjoita Y-tunnus.
                    </div>
                </div>


                

                <div class="mb-3">
                    <div class="text-center">
                    <button class="btn btn-primary" type="submit">Luo yritys tili</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    </fieldset>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"> </script> 
     <script src="scripts/registration_validation_yritys.js"></script>       
    

</body>
<?php include 'footer.html'; ?>
</html>