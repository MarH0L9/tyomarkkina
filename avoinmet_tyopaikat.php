<?php
include 'config.php'; // Incluye la configuración de la conexión a la base de datos
include 'functions/functions.php';

// Inicializa la variable que almacenará los resultados
$searchResults = '';

// Si se envió el formulario de búsqueda
if (isset($_GET['jobSearchText'])) {
    // Obtén la palabra clave de búsqueda desde la URL
    $keyword = $_GET['jobSearchText'];

    // Inicializa un array para almacenar los filtros seleccionados
    $filters = [];

    // Procesar los filtros de búsqueda
    if (isset($_GET['sijainti']) && !empty($_GET['sijainti'])) {
        $filters[] = "Sijainti = '" . $_GET['sijainti'] . "'";
    }

    if (isset($_GET['kunta']) && !empty($_GET['kunta'])) {
        $filters[] = "Kunta = '" . $_GET['kunta'] . "'";
    }

    if (!empty($julkaistu)) {
        $dateLimit = '';
    
        if ($julkaistu === '24h') {
            $dateLimit = date('Y-m-d H:i:s', strtotime('-1 day'));
        } elseif ($julkaistu === '3d') {
            $dateLimit = date('Y-m-d H:i:s', strtotime('-3 days'));
        } elseif ($julkaistu === '1w') {
            $dateLimit = date('Y-m-d H:i:s', strtotime('-7 days'));
        }
    }

    if (isset($_GET['continuity']) && !empty($_GET['continuity'])) {
        $filters[] = "PalveluSuhde = '" . $_GET['continuity'] . "'";
    }

    if (isset($_GET['language']) && !empty($_GET['language'])) {
        $filters[] = "TyoKieli = '" . $_GET['language'] . "'";
    }

    if (isset($_GET['workTime']) && !empty($_GET['workTime'])) {
        $filters[] = "Tyoaika = '" . $_GET['workTime'] . "'";
    }

    if (isset($_GET['employment']) && !empty($_GET['employment'])) {
        $filters[] = "PalveluSuhde = '" . $_GET['employment'] . "'";
    }


    // Construye la parte de la consulta SQL para los filtros seleccionados
    $filterClause = '';
    if (!empty($filters)) {
        $filterClause = " AND " . implode(" AND ", $filters);
    }

   // Consulta SQL para buscar anuncios que contengan la palabra clave y cumplan con los filtros seleccionados
    $sql = "SELECT * FROM offers WHERE (Otsikko LIKE '%$keyword%' OR Kuvaus LIKE '%$keyword%' OR Sijainti LIKE '%$keyword%' OR Kunta LIKE '%$keyword%'  OR YrityksenNimi LIKE '%$keyword%' OR Ala LIKE '%$keyword%' OR Vaatimukset LIKE '%$keyword%' OR YrityksenLinkki LIKE '%$keyword%' )$filterClause";
    
    try {
        $result = $pdo->query($sql); // Utiliza la conexión PDO en lugar de crear una nueva conexión
        
        if ($result->rowCount() > 0) { // Utiliza rowCount en lugar de num_rows con PDO
            // Construye los resultados de la búsqueda
            $searchResults .= '<h2>Tyopaikat:</h2>';
            
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Utiliza FETCH_ASSOC para obtener un array asociativo
                // Utiliza la función generateJobCard para generar la tarjeta de resultado
                $searchResults .= '<div class="res-card">' . generateJobCard($row) . '</div>';
            }
            
        } else {
            $searchResults .= '<p>No se encontraron resultados para la búsqueda: ' . $keyword . '</p>';
        }
    } catch (PDOException $e) {
        // Manejo de errores de la consulta
        echo "Error en la consulta: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Avoinmet työpaikat</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://kit.fontawesome.com/07bb6b2702.js" crossorigin="anonymous"></script>
    <script src="scripts/haku_filter.js"></script>
    <script src="scripts/filtro.js"></script>
    <?php include 'config.php'; ?>
    <?php include 'maakunnat.php'; ?>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mx-auto mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <h1 class="display-4">Avoinmet Työpaikat</h1>
            <form action="avoinmet_tyopaikat.php" method="GET" class="input-group mb-3">
                <div class="input-group">
                    <input type="search" class="form-control form-rounded-3" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon"  name="jobSearchText">
                    <button type="submit" class="btn btn-primary custom-button">Hae <i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- Filtros debajo de la barra de búsqueda en filas -->
    <div class="row mt-4">
        <div class="col-md-2">
            <label for="sijainti" class="form-label" style="font-weight:bold;">Valitse sijainti:</label>
            <select class="form-select" id="sijainti" name="sijainti">
                <option value="">Valitse sijainti</option>
                <?php
                // Decodificar el JSON de maakunnat y kunnat
                $jsonString = '...'; // Reemplaza con el contenido completo del JSON que creamos anteriormente
                $locations = json_decode($jsonString, true);

                if ($locations) {
                    // Iterar a través de las maakunnat y sus kunnat
                    foreach ($locations['Maakunnat'] as $maakunta => $kunnat) {
                        echo '<optgroup label="' . $maakunta . '">';
                        foreach ($kunnat as $kunta) {
                            echo '<option value="' . $maakunta . '-' . $kunta . '">' . $kunta . '</option>';
                        }
                        echo '</optgroup>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="col-md-2">
            <label for="julkaistu" class="form-label"  style="font-weight:bold;">Julkaistu:</label>
            <select class="form-select" id="julkaistu" name="julkaistu">
                <option value="">Valitse aika</option>
                <option value="24h">24 tuntia</option>
                <option value="3d">3 päivää</option>
                <option value="1w">Viikko</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="ala" class="form-label"  style="font-weight:bold;">Ala:</label>
            <select class="form-select" id="ala" name="ala">
                <option value="">Valitse ala</option>
                <!-- Agrega opciones específicas del sector aquí -->
            </select>
        </div>
        <div class="col-md-2">
            <label for="tyoaika" class="form-label"  style="font-weight:bold;">Työaika:</label>
            <select class="form-select" id="tyoaika" name="tyoaika">
                <option value="">Valitse työaika</option>
                <option value="fullTime">Kokopäivätyö</option>
                <option value="partTime">Osapäivätyö</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="tyonkieli" class="form-label"  style="font-weight:bold;">Työn kieli:</label>
            <select class="form-select" id="tyonkieli" name="tyonkieli">
                <option value="">Valitse työn kieli</option>
                <option value="Suomi">Suomi</option>
                <option value="Ruotsi">Ruotsi</option>
                <option value="Englanti">Englanti</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="palvelusuhde" class="form-label"  style="font-weight:bold;">Palvelusuhde:</label>
            <select class="form-select" id="palvelusuhde" name="palvelusuhde">
                <option value="">Valitse palvelusuhde</option>
                <option value="tyosuhde">Työsuhde</option>
                <option value="virkasuhde">Virkasuhde</option>
                <option value="vuokratyo">Vuokratyö</option>
                <option value="tyoharjottelu">Työharjottelu</option>
                <option value="oppisopimus">Oppisopimus</option>
                <option value="franchasing">Franchasing</option>
                <option value="Muu">Muu</option>
            </select>
        </div>
    </div>

    <!-- Resultados de la búsqueda debajo de los filtros -->
    <div class="row mt-5" id="searchResults">
        <div class="col-md-12">
            <?php echo $searchResults; ?>
            
        </div>
    </div>

    <!-- Paginación para resultados -->
    <div class="row mt-3" id="pagination">
        <div class="col-md-12">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="#">Edellinen</a></li>
                    <!-- Genera dinámicamente los números de página aquí -->
                    <li class="page-item"><a class="page-link" href="#">Seuraava</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<?php include 'footer.html'; ?>
</body>
</html>
