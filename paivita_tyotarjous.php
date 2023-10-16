<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturar datos desde el formulario
    $otsikko = $_POST['otsikko'];
    $kuvaus = $_POST['kuvaus'];
    $tarkkakuvaus = $_POST['tarkkakuvaus'];
    $sijainti = $_POST['sijainti'];
    $kunta = $_POST['kunta'];
    $tehtava = $_POST['tehtava'];
    $tyoaika = $_POST['tyoaika'];
    $palkka = $_POST['palkka'];
    $voimassaolopaiva = $_POST['voimassaolopaiva'];
    $yrityksenlinkki = $_POST['yrityksenlinkki'];
    $jobId = $_POST['jobId'];

    try {
        $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para actualizar la oferta de trabajo
        $query = "UPDATE jobs SET otsikko = ?, kuvaus = ?, tarkkakuvaus = ?, sijainti = ?, kunta = ?, tehtava = ?, tyoaika = ?, palkka = ?, voimassaolopaiva = ?, yrityksenlinkki = ?  WHERE id = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$otsikko, $kuvaus, $tarkkakuvaus, $sijainti, $kunta, $tehtava, $tyoaika, $palkka, $voimassaolopaiva, $yrityksenlinkki, $jobId]);

        // Redireccionar al usuario a yrityksen_profiili.php con un mensaje
        $_SESSION['message'] = "Päivitys onnistui. Kentät on päivitetty.";
        header('Location: yrityksen_profiili.php?updated=true');
        exit();

    } catch (PDOException $e) {
        echo "Error de base de datos: " . $e->getMessage();
    }
}
?>