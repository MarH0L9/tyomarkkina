<?php
require 'config.php'; 
$title = 'Kirjaudu sisään';
$css = 'css/styles.css';
include "header.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$pdo = new PDO($dsn, $username, $password);

$message = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['email'];
    $Password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = :email"; // Only check email here first.
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $Email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($Password, $user['pwd'])) { // Use password_verify() for password hashing
        session_regenerate_id(true);  
        $_SESSION['user_id'] = $user['ID'];
        $_SESSION['login_success'] = true;
        
    } else {
        $message = '<div class="alert alert-danger" role="alert"><i class="bi bi-exclamation-triangle"></i> Sähköposti tai salasana on virheellinen.</div>';
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
</head>
<body>

    <div class="container login" style="margin-top: 200px;">
    <?php if (isset($_SESSION['login_success'])) {
    echo '<div class="alert alert-success" role="alert"> Kirjautuminen onnistui. Sinut ohjataan pääsivulle 2 sekunnin kuluttua...</div>';
    echo '<script>
        setTimeout(function() {
            window.location.href = "index.php";
        }, 2000);
    </script>';
    unset($_SESSION['login_success']);  // Elimina la variable de sesión después de mostrar el mensaje
}
?>
    <fieldset>
            <legend>Kirjaudu sisään </legend>
            <?php if (!empty($message)) : ?>
                <?php echo $message; ?>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <!-- Email input -->
                <div class="form-outline mb-4">
                    <input type="email" class="form-control dos" id="email" name="email" required>
                    <label class="form-label" for="email">Sähköposti</label>
                </div>
                <!-- Password input -->
                <div class="form-outline mb-4">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <label class="form-label" for="password">Salasana</label>
                </div>
                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4">Kirjaudu</button>
                <div class="mb-3">
                    <a href="forgot.php" class="btn btn-link">Unohditko salasanan?</a>
                </div>
            </form>
        </fieldset>
    </div>
    

</body>

<?php include 'footer.html'; ?>
</html>
