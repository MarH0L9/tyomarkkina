<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'yritys') {
    // Jos käyttäjä ei ole yritys, ohjaa hänet kirjautumissivulle
    header('Location: login.php');
    exit();
}

// Lisää config.php-tiedosto
include 'config.php';

try {
    // Rakentaa yhteyden tietokantaan config.php-tiedoston avulla
    $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Ottaa lomakkeen tiedot ja tallenna ne muuttujiin
    $otsikko = $_POST['otsikko'];
    $kuvaus = $_POST['kuvaus'];
    $tarkkakuvaus = $_POST['tarkkakuvaus'];
    $yrityksen_nimi = $_POST['yrityksennimi'];
    $sijainti = $_POST['sijainti'];
    $kunta = $_POST['kunta'];
    $tehtava = $_POST['tehtava'];
    $ala = $_POST['ala'];
    $tyoaika = $_POST['tyoaika'];
    $palvelusuhde = $_POST['palvelusuhde'];
    $palkka = $_POST['palkka'];
    $tyokieli = implode(", ", $_POST['tyokieli']);
    $voimassaolopaiva = $_POST['voimassaolopaiva'];
    $vaatimukset = $_POST['vaatimukset'];
    $yrityksenlinkki = $_POST['yrityksenlinkki'];
    $contact_details = $_POST['contact_details'];
    $antajaid = $_SESSION['user_id'];

    $uploadDir = 'resources/images/jobs/';
     // Procesa la carga de la imagen
     if (!empty($_FILES['kuva']['name'])) {
        $imageFileType = strtolower(pathinfo($_FILES['kuva']['name'], PATHINFO_EXTENSION));
        $uniqueFileName = uniqid() . '.' . $imageFileType;
        $uploadedFile = $uploadDir . $uniqueFileName;

           // Verifica si el archivo es una imagen válida
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
         
        if (in_array($imageFileType, $allowedExtensions)) {
            // Mueve el archivo al directorio de carga
            if (move_uploaded_file($_FILES['kuva']['tmp_name'], $uploadedFile)) {
                // La imagen se cargó con éxito, puedes guardar la ruta en la base de datos
                $image_url = $uploadedFile;
            } else {
                $error_message = "Virhe kuvaa ladattaessa.";
            }
        } else {
            $error_message = "Sallitut tiedostotyypit ovat jpg, jpeg, png ja gif.";
        }
    
    } else {
        // No se cargó una imagen, establece el campo de imagen en NULL
        $image_url = null;
    }
    

    // Valmistele yhteys tietokantaan ja suorita SQL-kysely
    $sql = "INSERT INTO jobs (otsikko, kuvaus, tarkkakuvaus, yrityksennimi, sijainti, kunta, tehtava, ala, tyoaika, palvelusuhde, palkka, julkaistu, tyokieli, voimassaolopaiva, vaatimukset, yrityksenlinkki, contact_details, hyvaksytty,antajaid, kuva)
            VALUES (:otsikko, :kuvaus, :tarkkakuvaus, :yrityksennimi, :sijainti, :kunta, :tehtava, :ala, :tyoaika, :palvelusuhde, :palkka, NOW(), :tyokieli, :voimassaolopaiva, :vaatimukset, :yrityksenlinkki, :contact_details, 0, :antajaid, :kuva)";

    // Käytä PDO-esivalmistettua lausetta
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':otsikko', $otsikko);
    $stmt->bindParam(':kuvaus', $kuvaus);
    $stmt->bindParam(':tarkkakuvaus', $tarkkakuvaus);
    $stmt->bindParam(':yrityksennimi', $yrityksen_nimi);
    $stmt->bindParam(':sijainti', $sijainti);
    $stmt->bindParam(':kunta', $kunta);
    $stmt->bindParam(':tehtava', $tehtava);
    $stmt->bindParam(':ala', $ala);
    $stmt->bindParam(':tyoaika', $tyoaika);
    $stmt->bindParam(':palvelusuhde', $palvelusuhde);
    $stmt->bindParam(':palkka', $palkka);
    $stmt->bindParam(':tyokieli', $tyokieli);
    $stmt->bindParam(':voimassaolopaiva', $voimassaolopaiva);
    $stmt->bindParam(':vaatimukset', $vaatimukset);
    $stmt->bindParam(':yrityksenlinkki', $yrityksenlinkki);
    $stmt->bindParam(':contact_details', $contact_details);
    $stmt->bindParam(':antajaid', $antajaid);
    $stmt->bindParam(':kuva', $image_url);
    $stmt->execute();

    $pdo = null; // Sulje yhteys tietokantaan
   
    //Ilmoitus jossa kerrotaan että ilmoitus on lähetetty onnistuneesti 
   $exit_message = "Kiitos! Ilmoituksesi on lähetetty onnistuneesti. Ylläpitäjämme tarkastavat sen, ja jos kaikki on kunnossa, se julkaistaan.";

   
   echo '<div class="alert alert-success" role="alert">' . $exit_message . '</div>';

   //Vie käyttäjä etusivulle 2 sekunnin kuluttua
   echo '<script>
           setTimeout(function() {
               window.location.href = "index.php";
           }, 2000);
         </script>';
} catch (PDOException $e) {
   // Näytä virheilmoitus
   echo "Virhe, ilmoitus ei ole lähetetty: " . $e->getMessage();
}
?>