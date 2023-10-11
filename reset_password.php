<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 
require 'config.php';

$message = "";

if (isset($_POST['submit'])) {
    $oldPassword = $_POST['old_password'];
    $newPassword = $_POST['new_password'];
    $repeatPassword = $_POST['repeat_password'];

    // Obtiene la contraseña actual del usuario desde la base de datos
    $pdo = new PDO($dsn, $username, $password);
    $stmt = $pdo->prepare("SELECT pwd FROM users WHERE ID = :ID");  // Cambia user_id por ID aquí
    $stmt->bindParam(':ID', $_SESSION['user_id']);  // Y aquí
    $stmt->execute();
    $currentPassword = $stmt->fetchColumn();

    // Verifica la contraseña antigua
    if (password_verify($oldPassword, $currentPassword)) {
        // Verifica si la nueva contraseña y repetir contraseña coinciden
        if ($newPassword == $repeatPassword) {
            // Actualiza la contraseña
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET pwd = :password WHERE ID = :ID");
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':ID', $_SESSION['user_id']);
            $stmt->execute();
            $message = "<div class='alert alert-success'>Uusi salasana on tallennettu oikein.</div>";
            header("refresh:1.5;url=oma_profiili.php");  // 1.5 sekundin kuluttua ohjaa käyttäjän oma_profiili.php-sivulle
            exit;  // script loppuu tähän
        } else {
            $message = "<div class='alert alert-danger'>salasanat eivät täsmää.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Vanha salasana on väärä.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6"> <!-- Esta línea controla el ancho del formulario, ajusta '6' como desees. -->
            <form action="reset_password.php" method="post">
                <div class="mb-3">
                    <label for="old_password" class="form-label">Nykyinen salasana:</label>
                    <input type="password" class="form-control" name="old_password" required>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label">Uusi Salasana:</label>
                    <input type="password" class="form-control" name="new_password" required>
                </div>
                <div class="mb-3">
                    <label for="repeat_password" class="form-label">Uusi salasana uudelleen:</label>
                    <input type="password" class="form-control" name="repeat_password" required>
                </div>
                <div class="mb-3 text-center"> <!-- Esta línea centra el botón en el medio del formulario. -->
                    <button type="submit" name="submit" class="btn btn-primary">Vaihda</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php
    if ($message) {
        echo "<p>$message</p>";
    }
    ?>
    <?php include 'footer.html'; ?>
</body>

</html>






