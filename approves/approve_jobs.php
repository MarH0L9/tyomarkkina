<?php
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

    echo "<script>
            alert('Ilmoitus on hyväksytty ja se on aktiivinen.');
            setTimeout(function(){
                window.location.href = '../admin_panel.php';
            }, 1000);
          </script>";

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>