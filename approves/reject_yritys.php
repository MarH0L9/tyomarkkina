<?php
// Iniciar la sesión
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Asegurarte de que solo un administrador pueda acceder a esta página
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

require '../config.php';

// Verificar si el ID del usuario ha sido enviado
if (!isset($_GET['user_id'])) {
    header("Location: ../admin_panel.php");
    exit();
}

$user_id = $_GET['user_id'];

try {
    $pdo = new PDO($dsn, $username, $password);

    // Empezamos una transacción. Esto nos permite hacer varias operaciones y, si algo sale mal, revertir todas las operaciones.
    $pdo->beginTransaction();

    // Preparar la consulta SQL para eliminar la entrada en la tabla tyonantajat
    $queryYritys = "DELETE FROM tyonantajat WHERE user_id = :user_id";
    $stmtYritys = $pdo->prepare($queryYritys);
    
    // Vincular el ID del usuario a la consulta y ejecutarla
    $stmtYritys->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtYritys->execute();

    // Ahora, eliminar el usuario
    $queryUser = "DELETE FROM users WHERE id = :user_id";
    $stmtUser = $pdo->prepare($queryUser);
    
    // Vincular el ID del usuario a la consulta y ejecutarla
    $stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmtUser->execute();

    // Si todo sale bien, confirmamos las operaciones en la base de datos
    $pdo->commit();


} catch (PDOException $e) {
    // En caso de error, revertimos las operaciones
    $pdo->rollBack();
    
    // Mostrar mensaje de error
    die("Error deleting user: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <!-- Asegúrate de incluir los estilos y scripts de Bootstrap si aún no están incluidos -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<!-- Modal structure -->
<div class="modal" tabindex="-1" id="confirmationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Käyttäja ei ole hyvätsytty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Käyttäjä ei olet hyväksytty ja on poistettu tietokannasta.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="redirectToAdminPanel()">OK</button>
            </div>
        </div>
    </div>
</div>

<script>
    function redirectToAdminPanel() {
        location.href = "../admin_panel.php";
    }

    var modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    modal.show();
</script>

</body>
</html>