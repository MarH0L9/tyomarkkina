<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'yritys') {
    // Jos käyttäjä ei ole kirjautunut sisään yritystunnuksilla, ohjaa kirjautumissivulle
    header('Location: login.php');
    exit();
}

require 'config.php';

// Tarkistaa, onko URL-osoitteessa annettu voimassa oleva työtarjouksen tunnus.L
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $jobId = $_GET['id'];

    try {
        //Luo yhteyden tietokantaan
        $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // työtarjouksen yksityiskohtiin
        $query = "SELECT * FROM jobs WHERE id = :job_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        // Tarkista, löytyikö työtarjous
        if ($job = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Tämän jälkeen voit käyttää $job:n tietoja muutoslomakkeen näyttämiseen.
        } else {
            // Jos työtarjousta ei löydy, voit näyttää virheilmoituksen tai ohjata toiselle sivulle.
            echo "Työtarjousta ei löydy.";
        }
    } catch (PDOException $e) {
        
        echo "Error de base de datos: " . $e->getMessage();
    }
} else {
    // Jos voimassa olevaa työtarjouksen tunnusta ei ole annettu, voit näyttää virheilmoituksen tai ohjata toiselle sivulle.
    echo '<div class="alert alert-warning" role="alert">Virheellinen työtarjouksen ID.</div>';
}
?>


<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="script/haku_filter.js"></script>
    <?php include 'maakunnat.php'; ?>
</head>
<?php include 'header.php'; ?>
<body>
<main>
<div class="container mt-5">
    <div class="row justify-content-center">
    <div id="successMessage" class="alert alert-success" role="alert" style="display: none;">
                Päivitys onnistui. Kentät on päivitetty.
                </div>  
        <div class="col-md-6">
                
            <h2><i class="fa-regular fa-edit fa-lm"></i> Muokkaa työtarjousta</h2><hr>
            
            <form action="paivita_tyotarjous.php" method="POST" class="mt-4" enctype="multipart/form-data">

            <div class="mb-3">
                <label for="kuva" class="form-label">Uusi kuva</label>
                <input type="file" class="form-control" id="kuva" name="kuva">
            </div>


         <div class="mb-3">
            <label for="otsikko" class="form-label">Otsikko</label>
            <input type="text" class="form-control" id="otsikko" name="otsikko" value="<?php echo $job['otsikko']; ?>">
        </div>

        <div class="mb-3">
            <label for="kuvaus" class="form-label">Kuvaus</label>
            <textarea class="form-control" id="kuvaus" name="kuvaus"><?php echo $job['kuvaus']; ?></textarea>
        </div>

        <div class="mb-3">
            <label for="tarkkakuvaus" class="form-label">Tarkka kuvaus</label>
            <textarea class="form-control" id="tarkkakuvaus" name="tarkkakuvaus"><?php echo $job['tarkkakuvaus']; ?></textarea>
        </div>
            <div class="row">
            <p>Nykyinen sijainti:<strong><?php echo $job['sijainti']; ?> , <?php echo $job['kunta'];?></strong></p>
                    <div class="col-md-6 mb-3">
            <label for="sijainti" class="form-label" style="font-weight:bold;">Sijainti</label>
            
            <select class="form-select" id="sijainti" name="sijainti">
                <option value="">Valitse sijainti</option>
                <?php
                // Decodificar el JSON de maakunnat y kunnat
                $jsonString = '...'; // Replace with the full content of the JSON you created earlier
                $locations = json_decode($jsonString, true);

                if ($locations) {
                    // Iterate through the maakunnat and their kunnat
                    foreach ($locations['Maakunnat'] as $maakunta => $kunnat) {
                        echo '<optgroup label="' . $maakunta . '">';
                        foreach ($kunnat as $kunta) {
                            $selected = ($maakunta . '-' . $kunta == $job['sijainti']) ? 'selected' : '';
                            echo '<option value="' . $maakunta . '-' . $kunta . '" ' . $selected . '>' . $kunta . '</option>';
                        }
                        echo '</optgroup>';
                    }
                }
                ?>
            </select>
        </div>

                <div class="col-md-6 mb-3">
                    <label for="kunta" class="form-label">Kunta</label>
                    <input type="text" class="form-control" id="kunta" name="kunta" value="<?php echo $job['kunta']; ?>">
                </div>
            </div>
    <div class="row">
        <div class="col-md-6 mb-3">
                    <label for="tehtava" class="form-label">Tehtävä</label>
                    <select class="form-select" id="tehtava" name="tehtava" value="<?php echo $job['tehtava'];?>">:
                        <option value="Asennus, huolto ja kunnossapito">Asennus, huolto ja kunnossapito</option>
                        <option value="Asiakaspalvelu">Asiakaspalvelu</option>
                        <option value="Asiantuntijatyö ja konsultointi">Asiantuntijatyö ja konsultointi</option>
                        <option value="Hallinto ja yleiset toimistotyöt">Hallinto ja yleiset toimistotyöt</option>
                        <option value="Henkilöstöala">Henkilöstöala</option>
                        <option value="Hyvinvointi- ja henkilöpalvelut">Hyvinvointi- ja henkilöpalvelut</option>
                        <option value="Johtotehtävät">Johtotehtävät</option>
                        <option value="Julkinen sektori ja järjestöt">Julkinen sektori ja järjestöt</option>
                        <option value="Kiinteistöala">Kiinteistöala</option>
                        <option value="Kuljetus, logistiikka ja liikenne">Kuljetus, logistiikka ja liikenne</option>
                        <option value="Kulttuuri-, viihde- ja taidealat">Kulttuuri-, viihde- ja taidealat</option>
                        <option value="Lakiala">Lakiala</option>
                        <option value="Markkinointi">Markkinointi</option>
                        <option value="Markkinointi, mainonta, media ja viestintä">Markkinointi, mainonta, media ja viestintä</option>
                        <option value="Myynti- ja kaupan ala">Myynti- ja kaupan ala</option>
                        <option value="Opetusala">Opetusala</option>
                        <option value="Opiskelijoiden työpaikat">Opiskelijoiden työpaikat</option>
                        <option value="Rakennusala">Rakennusala</option>
                        <option value="Ravintola- ja matkailuala">Ravintola- ja matkailuala</option>
                        <option value="Siivous, puhtaanapito ja jätehuolto">Siivous, puhtaanapito ja jätehuolto</option>
                        <option value="Sosiaali- ja hoiva-ala">Sosiaali- ja hoiva-ala</option>
                        <option value="Taloushallinto ja pankkiala">Taloushallinto ja pankkiala</option>
                        <option value="Teollisuus ja teknologia">Teollisuus ja teknologia</option>
                        <option value="Terveys- ja sosiaalipalvelut">Terveys- ja sosiaalipalvelut</option>
                    </select>
                </div>

        <div class="col-md-6 mb-3">
            <label for="tyoaika" class="form-label">Työaika</label>
            <input type="text" class="form-control" id="tyoaika" name="tyoaika" value="<?php echo $job['tyoaika']; ?>">
        </div>
    </div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="palkka" class="form-label">Palkka</label>
        <input type="text" class="form-control" id="palkka" name="palkka" value="<?php echo $job['palkka']; ?>">
    </div>

    <div class="col-md-6 mb-3">
        <label for="voimassaolopaiva" class="form-label">Viimeinen hakupäivä</label>
        <input type="date" class="form-control" id="voimassaolopaiva" name="voimassaolopaiva" value="<?php echo $job['voimassaolopaiva']; ?>">
    </div>
</div>
<div class="row">
    <div class="col mb-3">
        <label for="yrityksenlinkki" class="form-label">Linkki</label>
        <input type="text" class="form-control" id="yrityksenlinkki" name="yrityksenlinkki" value="<?php echo $job['yrityksenlinkki'];?>">
    </div>   
    </div>

    <div class="row">
        
        <div class="row">
        <div class="col mb-3">
            <label for="contact_details" class="form-label">Työtarjouksen yhteys tiedot</label>
            <textarea class="form-control" id="contact_details" name="contact_details" rows="5" placeholder="Nimi, puhelin, sähköposti,..."><?php echo isset($job['contact_details']) ? $job['contact_details'] : ''; ?></textarea>
        </div>
        </div>
    <input type="hidden" name="jobId" value="<?php echo $jobId; ?>">      
         
<div class="mb-3 text-center" >
<button type="submit" class="btn btn-warning"><i class="fa-solid fa-wrench fa-lg"></i></i> Päivitä</button>
</div>
            </form>
            
        </div>
    </div>
</div>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="scripts/haku_filter.js"></script>
<script src="scripts/individual-filters.js"></script>
<script src="scripts/filtro.js"></script>
<script>
    $(document).ready(function() {
    $('form').on('submit', function(e) {

        if ($('#sijainti').val() === "") {
            alert("Valitse sijainti ennen päivitystä.");
            e.preventDefault();
            return;
        } else {
            $('#sijainti').css('background-color', '');
        }

        e.preventDefault();
        const formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'paivita_tyotarjous.php',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                const data = JSON.parse(response);

                if (data.success) {
                    // Muestra el mensaje de éxito
                    $('html, body').animate({ scrollTop: 0 }, 'fast');
                    $('#successMessage').slideDown();

                    setTimeout(function() {
                        window.location.href = 'yrityksen_profiili.php?updated=true';
                    }, 3000);
                } else {
                    // Aquí puedes agregar un mensaje de error similar si lo deseas
                    alert('Hubo un error al actualizar los datos.');
                }
            }
        });
    });
});
</script>
<?php include 'footer.html'; ?>
</body>
</html>