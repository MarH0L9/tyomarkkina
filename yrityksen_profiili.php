<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
 

include 'functions/functions.php';
require 'config.php';
$pdo = new PDO($dsn, $username, $password);
$userId = $_SESSION['user_id'];

// Muuttuja, jolla seurataan, onko päivitys onnistunut.
$actualizacionExitosa = false;

//Muuttuja, johon tallennetaan virheilmoitus
$error_message = "";

// Tarkista, onko lomake lähetetty
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vastaanottaa lomakkeen tiedot
    $yrityksen_nimi = $_POST['yrityksen_nimi'];
    $Y_tunnus = $_POST['Y_tunnus'];
    $osoite = $_POST['osoite'];
    $puhelin = $_POST['puhelin'];
    $verkkosivusto = $_POST['verkkosivusto'];

    ////Käsittelee kuvan lataamisen
if (!empty($_FILES['profile_kuva']['name'])) {
    $uploadDir = 'resources/images/companies/'; // Hakemisto, johon kuvat tallennetaan

    // Obtener la extensión del archivo
    $imageFileType = strtolower(pathinfo($_FILES['profile_kuva']['name'], PATHINFO_EXTENSION));
    
    // Genera un nombre único para el archivo
    $uniqueFileName = uniqid() . '.' . $imageFileType;
    $uploadedFile = $uploadDir . $uniqueFileName;
    
    // Tarkista, onko tiedosto kelvollinen kuva
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    
    if (in_array($imageFileType, $allowedExtensions)) {
        // Siirrä tiedosto lataushakemistoon
        if (move_uploaded_file($_FILES['profile_kuva']['tmp_name'], $uploadedFile)) {
            // Kuva ladattiin onnistuneesti, voit tallentaa polun tietokantaan.
            $imagePath = $uploadedFile;
        } else {
            $error_message = "Virhe kuvaa ladattaessa.";
        }
    } else {
        $error_message = "Sallitut tiedostotyypit ovat jpg, jpeg, png ja gif.";
    }
}
   
if (isset($imagePath)) {
    $query = "UPDATE tyonantajat SET yrityksen_nimi = :yrityksen_nimi, Y_tunnus = :Y_tunnus, osoite = :osoite, puhelin = :puhelin, verkkosivusto = :verkkosivusto, profile_kuva = :profile_kuva WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':profile_kuva', $imagePath);
} else {
    $query = "UPDATE tyonantajat SET yrityksen_nimi = :yrityksen_nimi, Y_tunnus = :Y_tunnus, osoite = :osoite, puhelin = :puhelin, verkkosivusto = :verkkosivusto WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
}

$stmt->bindParam(':yrityksen_nimi', $yrityksen_nimi);
$stmt->bindParam(':Y_tunnus', $Y_tunnus);
$stmt->bindParam(':osoite', $osoite);
$stmt->bindParam(':puhelin', $puhelin);
$stmt->bindParam(':verkkosivusto', $verkkosivusto);
$stmt->bindParam(':user_id', $userId);

if ($stmt->execute()) {
    $actualizacionExitosa = true;
} else {
    $error_message = "Virhe päivitettäessä profiilia.";
}
}

$query = "SELECT tp.*, u.email FROM tyonantajat tp
      JOIN users u ON tp.user_id = u.id
      WHERE tp.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

$modificacionRealizada = false;

// Tarkistaa, onko tietokantaan tehty muutos.
if ($actualizacionExitosa) {
    $modificacionRealizada = true;
}


//Omat mainokset:
$queryAnuncios = "SELECT * FROM jobs WHERE antajaid = :antajaid";
$stmtAnuncios = $pdo->prepare($queryAnuncios);
$stmtAnuncios->bindParam(':antajaid', $userId);
$stmtAnuncios->execute();
$anuncios = $stmtAnuncios->fetchAll(PDO::FETCH_ASSOC);
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
<?php include 'header.php'; ?>
<body>
    <main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <h2><i class="fa-regular fa-building fa-lm"></i> Yrityksen Profiili</h2><hr>

                <!-- Päivitys onnistui (näytetään vain päivityksen yhteydessä) -->
                <?php if ($actualizacionExitosa) : ?>
                    <div class="alert alert-success mt-4" role="alert" id="successMessage">
                        Profiili päivitetty onnistuneesti.
                    </div>
                <?php endif; ?>

                <form action="yrityksen_profiili.php" method="POST" class="mt-4" enctype="multipart/form-data">
                    <!-- Tähän voit sisällyttää esimerkiksi tyonantajat-taulukon kentät: -->
                    <div class="row"> 

                        <div class="mb-3">
                        
                        <label for="profile_image" class="form-label">Profiilikuva</label><br>
                        <div class="text-center">
                        <img  src="<?php echo isset($profile['profile_kuva']) ? $profile['profile_kuva'] : 'resources/images/companies/placeholder.jpg'; ?>" alt="Profiilikuva" id="profile_image" class="yritys-image-size">   </div>
                        </div>
                        </div><br>
                        <button id="toggleImageDiv" type="button"><i class="fa-solid fa-plus fa-md"></i></button>
                        <label for="profile_kuva" class="form-label">Selaa uusi Profiili kuva</label>

                        <div class="mb-3" id="imageDiv">                    
                            
                            <input type="file" class="form-control" id="profile_kuva" name="profile_kuva">
                        </div>
                        <br>
                        <div class="row mb-3">
                            <div class="col-md-12">
                            <label for="email" class="form-label">Sähköposti</label>
                            <input type="text" class="form-control" id="email" name="email" value="<?php echo $profile ? $profile['email'] : ''; ?>" readonly>
                        </div>
                        </div>
                        
                        <div class="row mb-3">
                        <div class="col-md-6 mb-3">
                            <label for="yrityksen_nimi" class="form-label">Yrityksen Nimi</label>
                            <input type="text" class="form-control" id="yrityksen_nimi" name="yrityksen_nimi" value="<?php echo $profile ? $profile['yrityksen_nimi'] : ''; ?>">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="Y_tunnus" class="form-label">Y-Tunnus</label>
                            <input type="text" class="form-control" id="Y_tunnus" name="Y_tunnus" value="<?php echo $profile ? $profile['Y_tunnus'] : ''; ?>">
                        </div>
                        </div>
                       
                    <div class="row mb-3">
                        <div class="col-md-12">
                        <label for="osoite" class="form-label">Osoite</label>
                        <input type="text" class="form-control" id="osoite" name="osoite" value="<?php echo $profile ? $profile['osoite'] : ''; ?>">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="puhelin" class="form-label">Puhelin</label>
                            <input type="text" class="form-control" id="puhelin" name="puhelin" value="<?php echo $profile ? $profile['puhelin'] : ''; ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="verkkosivusto" class="form-label">Verkkosivusto</label>
                            <input type="text" class="form-control" id="verkkosivusto" name="verkkosivusto" value="<?php echo $profile ? $profile['verkkosivusto'] : ''; ?>">
                        </div>
                    </div>
                    <div class="text-end">
                            <a href="reset_password.php" class="btn btn-primary btn-sm active " role="button" aria-pressed="true" ><i class="fa-regular fa-pen-to-square fa-lg"></i></i> Vaihda Salasana</a>
                        </div>
                    
                    <div class="mb-3">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary custom-save">
                                <i class="fa-regular fa-floppy-disk"></i> Tallenna
                            </button>
                        </div>
                    </div>
                </form>
            
                <div class="col">
                    <h3>Voimassa työmainokset</h3><hr>
                    <?php if (!empty($anuncios)) : ?>
                        <div class="offer-list">
                            <?php foreach ($anuncios as $anuncio) : ?>
                                <div class="offer-item mb-3">
                                    <div class="offer-item-header center-text">
                                        <h5><?php echo $anuncio['otsikko']; ?></h5>
                                        <p>Yritys: <?php echo $anuncio['yrityksennimi']; ?></p>
                                        <p><?php echo $anuncio['sijainti']; ?>, <?php echo $anuncio['kunta']; ?></p>
                                        <p>Vimeinen hakupäivä: <?php echo $anuncio['voimassaolopaiva']; ?></p>
                                <div class="row"> 
                                        <div class="col">                            
                                    <button type="button" class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $anuncio['id']; ?>">
                                        Tarkista työtarjous
                                    </button>
                                    </div>
                                    <div class="col"> 
                                    <a href="muokkaa_tyotarjous.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-warning btn-md">Muokkaa</a>
                                    </div>
                                    <div class="col">
                                    <a href="poista_oferta.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-danger btn-md">Poista</a>
                                    </div>
                                </div>
                                </div>
                                </div>
                                <!-- Modal tarjouksen yksityiskohtien näyttämistä varten -->
                                <div class="modal fade" id="jobModal<?php echo $anuncio['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $anuncio['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                                <!-- Tässä sinun on lisättävä modaalin sisältö, joka näyttää työn tiedot -->                                            <div class="modal-header">
                                                <h5 class="modal-title" id="jobModalLabel<?php echo $anuncio['id']; ?>"><strong><?php echo $anuncio['otsikko']; ?></strong></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <!-- Lisää tähän tiedot työstä, jonka haluat näyttää -->
                                                <p><strong>Yritys:</strong> <?php echo $anuncio['yrityksennimi']; ?></p>
                                                <p><strong>Kuvaus:</strong> <?php echo $anuncio['kuvaus']; ?></p>
                                                <p><strong>Sijainti:</strong> <?php echo $anuncio['sijainti']; ?>, <?php echo $anuncio['kunta']; ?></p>
                                                <p><strong>Tehtävä:</strong> <?php echo $anuncio['tehtava']; ?></p>
                                                <p><strong>Ala:</strong> <?php echo $anuncio['ala']; ?></p>
                                                <p><strong>Työaika:</strong> <?php echo $anuncio['tyoaika']; ?></p>
                                                <p><strong>Palvelussuhde:</strong> <?php echo $anuncio['palvelusuhde']; ?></p>
                                                <p><strong>Palkka:</strong> <?php echo $anuncio['palkka']; ?></p>
                                                <p><strong>Työkieli:</strong> <?php echo $anuncio['tyokieli']; ?></p>
                                                <p><strong>Viimeinen hakupäivä:</strong> <?php echo $anuncio['voimassaolopaiva']; ?></p>
                                                <p><strong>Vaatimukset:</strong> <?php echo $anuncio['vaatimukset']; ?></p>
                                                <p><strong>Yrityksen linkki:</strong> <?php echo $anuncio['yrityksenlinkki']; ?></p>
                                            </div>
                                            <!-- Lisää painikkeita tai lisäsisältöä tarpeen mukaan -->
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <p>Ei voimassa olevia työtarjouksia.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Toiminto, joka piilottaa onnistumisviestin tietyn ajan kuluttua.
        function hideSuccessMessage() {
            var successMessage = document.getElementById('successMessage');
            if (successMessage) {
                setTimeout(function () {
                    successMessage.style.display = 'none';
                }, 2000);
            }
        }

        // Toiminto, joka piilottaa virheilmoituksen tietyn ajan kuluttua.
        function hideErrorMessage() {
            var errorMessage = document.getElementById('errorMessage');
            if (errorMessage) {
                setTimeout(function () {
                    errorMessage.style.display = 'none';
                }, 2000);
            }
        }

        


        // Kutsuu funktioita viestien piilottamiseksi, kun sivu latautuu.
        window.addEventListener('load', hideSuccessMessage);
        window.addEventListener('load', hideErrorMessage);
        
        $(document).ready(function() {
        // Oculta el mensaje de éxito después de 5 segundos
        $('#successMessage').delay(3000).fadeOut('slow');

        $('#toggleImageDiv').click(function() {
            $('#imageDiv').toggle();

            // Cambia el texto del botón
            if ($('#imageDiv').is(":visible")) {
                $(this).html('<i class="fa-solid fa-minus fa-md"></i>');
            } else {
                $(this).html('<i class="fa-solid fa-plus fa-md"></i>');
            }
        });
    });
        
    </script>
    <?php include 'footer.html'; ?>
</body>
</html>
