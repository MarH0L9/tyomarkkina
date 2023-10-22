<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
} 

require 'config.php';
include 'functions/functions.php'; 
$pdo = new PDO($dsn, $username, $password);
$userId = $_SESSION['user_id'];

// Obtener datos del formulario
$etunimi = $_POST['etunimi'];
$sukunimi = $_POST['sukunimi'];
$Email = $_POST['Email'];
$puhelin = $_POST['puhelin'];
$viimeisin_tyotehtava = $_POST['viimeisin_tyotehtava'];
$viimeisin_tyonantaja = $_POST['viimeisin_tyonantaja'];
$aikaisempi_kokemus = $_POST['aikaisempi_kokemus'];
$koulutustaso = $_POST['koulutustaso'];
$linkedin_url = $_POST['linkedin_url'];


// Verificar si el registro ya existe
$stmt = $pdo->prepare("SELECT user_id FROM user_profile WHERE user_id = :user_id");
$stmt->bindParam(":user_id", $userId); 
$stmt->execute();
$row = $stmt->fetch();

if ($row) { // Si el registro ya existe, actualízalo
    $stmt = $pdo->prepare("UPDATE user_profile 
                           SET etunimi = :etunimi, sukunimi = :sukunimi, Email = :Email, puhelin = :puhelin, viimeisin_tyotehtava = :viimeisin_tyotehtava, viimeisin_tyonantaja = :viimeisin_tyonantaja, aikaisempi_kokemus = :aikaisempi_kokemus, koulutustaso = :koulutustaso, linkedin_url = :linkedin_url
                           WHERE user_id = :user_id");

} else { // Si el registro no existe, insértalo
    $stmt = $pdo->prepare("INSERT INTO user_profile (user_id, etunimi, sukunimi, Email, puhelin, viimeisin_tyotehtava, viimeisin_tyonantaja, aikaisempi_kokemus, koulutustaso, linkedin_url) 
                           VALUES (:user_id, :etunimi, :sukunimi, :Email, :puhelin, :viimeisin_tyotehtava, :viimeisin_tyonantaja, :aikaisempi_kokemus, :koulutustaso, :linkedin_url)");
}

$stmt->bindParam(':user_id', $userId);
$stmt->bindParam(':etunimi', $etunimi);
$stmt->bindParam(':sukunimi', $sukunimi);
$stmt->bindParam(':Email', $Email);
$stmt->bindParam(':puhelin', $puhelin);
$stmt->bindParam(':viimeisin_tyotehtava', $viimeisin_tyotehtava);
$stmt->bindParam(':viimeisin_tyonantaja', $viimeisin_tyonantaja);
$stmt->bindParam(':aikaisempi_kokemus', $aikaisempi_kokemus);
$stmt->bindParam(':koulutustaso', $koulutustaso);
$stmt->bindParam(':linkedin_url', $linkedin_url);

if ($stmt->execute()) {
    setSessionMessage(true);  // En caso de éxito
} else {
    $errorInfo = $stmt->errorInfo();
    setSessionMessage(false, $errorInfo[2]);  // En caso de error
}

header("Location: oma_profiili.php");
exit();
?>