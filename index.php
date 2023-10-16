<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<!doctype html>
<html lang="fi">
<head>
    <title>Sinun Työportaali</title>
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
                        }, 2000);
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
    <div class="container mt-5"><hr>
    <div class="row mt-5">
        <div class="col-md-6">
            <h2>TE-palveluiden Oma asiointi</h2>
            <p>Oma asioinnissa voit asioida TE-palveluiden kanssa. Voit ilmoittautua työnhakijaksi, ilmoittaa muutoksesta työtilanteessasi tai hakea starttirahaa. Työnantajana voit hakea palkkatukea ja tehdä maksatushakemuksen.</p>
        </div>
        <div class="col-md-6">
            <!-- Image -->
            <img src="resources/images/te-logo.png" alt="TE-palvelut" class="img-fluid" style="width:350px;">
        </div>
    </div>

    <!-- Cuadros interactivos -->
    <div class="row mt-5">
    <div class="col-md-6 col-sm-12 mb-3 custom-card">
        <a href="https://asiointi.mol.fi/omaasiointi/" target="_blank" style="display: block;">
            <span>Oma Asiointi - Henkilöasiakkaat</span>
            <i class="fa-solid fa-arrow-up-right-from-square" style="color: #000000; float: right;"></i>
        </a>
    </div>
    <div class="col-md-6 col-sm-12 mb-3 custom-card">
        <a href="https://asiointi.mol.fi/tomas/aloitussivu.jsf?kieli=fi" target="_blank" style="display: block;">
            <span>Oma Asiointi Yritykset</span>
            <i class="fa-solid fa-arrow-up-right-from-square" style="color: #000000; float: right;"></i>
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
