<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<!doctype html>
<html lang="fi">
<head>
    <title>Sinun Työportaali</title>
    <link rel="icon" type="image/x-icon" href="resources/images/logo/favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'config.php';?>
    <?php include 'functions/functions.php';?>
</head>
<body>
<?php include 'header.php'; ?>

<main>
    <div class="text-center">
        <?php
            $session_closed_message = checkSessionClosed();
            if ($session_closed_message) {            
                echo "<div class='alert alert-warning'>$session_closed_message</div>"; 
                echo '<script>
                        setTimeout(function() {
                            window.location.href = "index.php";
                        }, 3000);
                    </script>';           
            }
        ?>
    </div>
    <div class="divConImagen"></div>
    <!-- Contenido centrado -->
    <div class="centrado">
        <div class="text-center">
            <h1 style="color:#0B3B0B">Tervetuloa Työportaaliin</h1>
            <h3 style="color:#0B3B0B; background-color:#a4eea6; padding:5px;">Täältä voit löytää uuden työpaikkasi ja teleportata sinne!</h3>
        </div>
        
            <div class="contenido">
                <div class="text-center">
                    <h1 style="color:#088A4B">Avoinmet Työpaikat</h1>
                </div>           
        </div>
    </div>

    <div class="container mt-5 search">
                <form action="avoinmet_tyopaikat.php" method="GET">
                    <div class="input-group index">
                        <input type="search" name="jobSearchText" class="form-control custom-border" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon" />
                        <button type="submit" class="btn btn-primary custom-button">Hae <i class="fas fa-search"></i></button>
                    </div>
                </form>
    </div>
    </div>

    <div class="container mt-5 carusel">
    <div class="row justify-content-center mt-5">
    <div class="text-center">
    <h2 style="color:#0B3B0B">Uusimmat työtarjoukset</h2>
    </div>
    <hr>
    <div class="col-md-8"><br>
    <div id="offerCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
        <?php
            require 'config.php';

            $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
            $pdo = new PDO($dsn, $username, $password);
            
            // SQL query jossa haetaan 9 uusinta työpaikkaa
            $query = "SELECT * FROM jobs WHERE hyvaksytty = 1 ORDER BY julkaistu DESC LIMIT 9";
            $stmt = $pdo->prepare($query);
            $stmt->execute();

            $first = true;  // Ensimmäinen työpaikka on aktiivinen

            while ($oferta = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="carousel-item ' . ($first ? 'active' : '') . '">';
                echo '<div class="offer-div">';
                echo generateJobCardindex($oferta);  // Generoidaan työpaikkakortti
                echo '</div>';
                echo '</div>';
                
                $first = false;
            }
        ?>
        </div>
        <a class="carousel-control-prev" href="#offerCarousel" role="button" data-slide="prev">
            <i class="fas fa-chevron-left fa-lg" style="color: black;"></i>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#offerCarousel" role="button" data-slide="next">
            <i class="fas fa-chevron-right fa-lg" style="color: black;"></i>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
</div>
</div>





    <div class="container mt-5"><hr>
    <div class="row mt-5">
        <div class="col-md-6">
            <h2 style="color:#0B3B0B;">TE-palveluiden Oma asiointi</h2>
            <p style="color: #033f21;">Oma asioinnissa voit asioida TE-palveluiden kanssa. Voit ilmoittautua työnhakijaksi, ilmoittaa muutoksesta työtilanteessasi tai hakea starttirahaa. Työnantajana voit hakea palkkatukea ja tehdä maksatushakemuksen.</p>
        </div>
        <div class="col-md-6">
            <!-- Imagen -->
            <img src="resources/images/te-logo.png" alt="TE-palvelut" class="img-fluid" style="width:350px;">
        </div>
    </div>

    <!-- Cuadros interactivos -->
    <div class="row mt-5">
    <div class="col-md-6 col-sm-12 mb-3 custom-card">
        <a href="https://asiointi.mol.fi/omaasiointi/" target="_blank" style="display: block;">
            <span style="color:#0B3B0B;">Oma Asiointi - Henkilöasiakkaat</span>
            <i class="fa-solid fa-arrow-up-right-from-square"  style="color:#0B3B0B; float: right;"></i>
        </a>
    </div>
    <div class="col-md-6 col-sm-12 mb-3 custom-card">
        <a href="https://asiointi.mol.fi/tomas/aloitussivu.jsf?kieli=fi" target="_blank" style="display: block;">
            <span style="color:#0B3B0B;">Oma Asiointi Yritykset</span>
            <i class="fa-solid fa-arrow-up-right-from-square" style="color:#0B3B0B; float: right;"></i>
        </a>
    </div>
</div>
</div>
</div>
</div>
</main>


<?php include 'footer.html'; ?>
</body>
</html>
