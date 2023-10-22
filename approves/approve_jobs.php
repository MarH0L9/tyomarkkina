<?php
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <!-- CSS de Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
</head>
<body>';


if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//Verificar si es administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    echo '<div class="alert alert-danger" role="alert">
       Access denied. Vain admin käyttäjä voi hyväksyä ilmoituksia.
       </div>';
exit();
}

require '../config.php';

echo '<div class="alert alert-success" role="alert" style="margin-left:30%; margin-right:30%; margin-top:10%;">
        Ilmoitus on hyväksytty ja se on aktiivinen. Paina ok.<br>
        <a href="../admin_panel.php" class="btn btn-primary ml-3">OK</a>
    </div>';

if (!isset($_GET['job_id'])) {
    die("Virhe: Ilmoituksen ID puuttuu.");
}

$job_id = $_GET['job_id'];

try {
    $pdo = new PDO($dsn, $username, $password);

    $query = "UPDATE jobs SET hyvaksytty = 1 WHERE id = :job_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':job_id', $job_id, PDO::PARAM_INT);
    $stmt->execute();

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}

echo '</body>
</html>';
?>