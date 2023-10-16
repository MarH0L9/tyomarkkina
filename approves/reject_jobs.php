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

// Verificar si el ID del trabajo ha sido enviado
if (!isset($_GET['job_id'])) {
    die("Virhe: Ilmoituksen ID puuttuu.");
}

$job_id = $_GET['job_id'];

try {
    $pdo = new PDO($dsn, $username, $password);

      // Preparar la consulta SQL para eliminar la entrada en la tabla jobs
    $queryJob = "DELETE FROM jobs WHERE id = :job_id";
    $stmtJob = $pdo->prepare($queryJob);
    
    // Vincular el ID del trabajo a la consulta y ejecutarla
    $stmtJob->bindParam(':job_id', $job_id, PDO::PARAM_INT);
    $stmtJob->execute();

} catch (PDOException $e) {
    // En caso de error, revertimos las operaciones
    $pdo->rollBack();
    die("Error deleting job: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .modal-header {
            background-color: #007bff;
            color: white;
        }
        .btn-primary {
            background-color: #f00;
            border-color: #f00;
        }
        .modal-body {
            font-size: 20px;
        }
    </style>
</head>
<body>

<div class="modal" tabindex="-1" id="confirmationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ilmoitus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Työtarjous ei ole hyväksytty ja on poistettu tietokannasta.</p>
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
