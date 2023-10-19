<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ottaa vastaan lomakkeen tiedot
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

         // Tarkistaa jos käyttäjä on ladannut uuden kuvan
         if (!empty($_FILES['kuva']['name'])) {
            $image_name = 'resources/images/companies/';
            $image_url = $image_name . basename($_FILES['kuva']['name']);

            // Siirrä ladattu kuva kansioon
            if (move_uploaded_file($_FILES['kuva']['tmp_name'], $image_url)) {
                // Kuva on ladattu onnistuneesti, päivitä tietokanta
                $query = "UPDATE jobs SET otsikko = ?, kuvaus = ?, tarkkakuvaus = ?, sijainti = ?, kunta = ?, tehtava = ?, tyoaika = ?, palkka = ?, voimassaolopaiva = ?, yrityksenlinkki = ?, kuva = ? WHERE id = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$otsikko, $kuvaus, $tarkkakuvaus, $sijainti, $kunta, $tehtava, $tyoaika, $palkka, $voimassaolopaiva, $yrityksenlinkki, $image_url, $jobId]);
            } else {
                echo "Virhe kuvaa ladattaessa.";
            }
        } else {
            // Kuva ei ole ladattu, päivitä tietokanta ilman kuvaa
            $query = "UPDATE jobs SET otsikko = ?, kuvaus = ?, tarkkakuvaus = ?, sijainti = ?, kunta = ?, tehtava = ?, tyoaika = ?, palkka = ?, voimassaolopaiva = ?, yrityksenlinkki = ? WHERE id = ?";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$otsikko, $kuvaus, $tarkkakuvaus, $sijainti, $kunta, $tehtava, $tyoaika, $palkka, $voimassaolopaiva, $yrityksenlinkki, $jobId]);
        }

        // Uudelleenohjaus yrityksen_profiili.php-sivulle
        $_SESSION['message'] = "Päivitys onnistui. Kentät on päivitetty.";
        header('Location: yrityksen_profiili.php?updated=true');
        exit();

    } catch (PDOException $e) {
        echo "Virhe tietokannassa: " . $e->getMessage();
    }
}
