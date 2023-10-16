<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar si es administrador
//if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
 //   die("Acceso denegado. Solo para administradores.");
//}

require '../config.php';

if (!isset($_GET['user_id'])) {
    die("Error: ID del usuario no especificado.");
}

$user_id = $_GET['user_id'];

try {
    $pdo = new PDO($dsn, $username, $password);

    $query = "UPDATE tyonantajat SET hyvaksytty = 1 WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();

    echo "<script>
            alert('Yritys on hyväksytty. Clikkaa OK ja sinut viedään takas admin paneliin.');
            setTimeout(function(){
                window.location.href = '../admin_panel.php';
            }, 1000);
          </script>";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>