<!doctype html>
<html lang="fi">
<head>
    <title>Sinun Työportaali</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
<?php include 'header.php';?>    
<?php include 'config.php';?>
</head>
<body>

<div class="container mt-4" style="background-color:#a4eea6; text-align:center;"  >
    <h1 style="color:#0B3B0B">Tervetuloa Työportaaliin</h1>
    <h3 style="color:#088A4B">Täältä voit löytää uuden työpaikkasi ja teleportata sinne!</h3>
    <div class="row justify-content-center mt-5">    
            <div class="col-md-8">
            <form action="#" method="GET" class="input-group mb-3 ">
                <div class="input-group">
                    <input type="search" class="form-control form-rounded-3" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon" />
                    <button type="button" class="btn btn-primary custom-button">Hae <i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="container mt-5">
    <div class="row mt-5">
        <div class="col-md-6">
            <h2>TE-palveluiden Oma asiointi</h2>
            <p>Oma asioinnissa voit asioida TE-palveluiden kanssa. Voit ilmoittautua työnhakijaksi, ilmoittaa muutoksesta työtilanteessasi tai hakea starttirahaa. Työnantajana voit hakea palkkatukea ja tehdä maksatushakemuksen.</p>
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
<?php include 'footer.html'; ?>
</body>
</html>
