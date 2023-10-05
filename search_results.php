<?php
include 'config.php'; // Incluye el archivo de configuración que contiene la conexión a la base de datos
include 'functions/functions.php';

// Inicializa la variable que almacenará los resultados
$searchResults = '';

// Si se envió el formulario de búsqueda
if (isset($_GET['submit'])) {
    // Obtén los valores de los filtros desde la solicitud
    $keyword = isset($_GET['jobSearchText']) ? $_GET['jobSearchText'] : '';
    $sijainti = isset($_GET['Sijainti']) ? $_GET['Sijainti'] : '';
    $julkaistu = isset($_GET['Julkaistu']) ? $_GET['Julkaistu'] : '';
    $palvelusuhde = isset($_GET['PalveluSuhde']) ? $_GET['PalveluSuhde'] : '';
    $tyokieli = isset($_GET['TyoKieli']) ? $_GET['TyoKieli'] : '';
    $tyoaika = isset($_GET['TyoAika']) ? $_GET['TyoAika'] : '';
    $vaatimukset = isset($_GET['Vaatimukset']) ? $_GET['Vaatimukset'] : '';

    // No es necesario verificar la conexión aquí, ya que se ha establecido en config.php

    // Construye la consulta SQL basada en los filtros seleccionados
    $sql = "SELECT * FROM Offers WHERE 1 = 1"; // Inicializa la consulta

    // Agrega condiciones según los filtros seleccionados
    if (!empty($keyword)) {
        $sql .= " AND (Otsikko LIKE '%$keyword%' OR Kuvaus LIKE '%$keyword%'  OR Kunta LIKE '%$keyword%' OR Sijainti LIKE '%$keyword%' OR YrityksenNimi LIKE '%$keyword%' OR Ala LIKE '%$keyword%' OR Vaatimukset LIKE '%$keyword%' OR YrityksenLinkki LIKE '%$keyword%')";
    }
    if (!empty($sijainti)) {
        $sql .= " AND Sijainti = '$sijainti'";
    }
    // Agrega las demás condiciones según los filtros aquí...

    // Ejecuta la consulta SQL
    $result = $pdo->query($sql); // Utiliza la conexión $pdo que se definió en config.php

    if ($result->rowCount() > 0) { // Cambio de $result->num_rows a $result->rowCount()
        // Construye los resultados de la búsqueda
        $searchResults .= '<h2>Tyopaikat:</h2>';
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) { // Utiliza PDO::FETCH_ASSOC para obtener un arreglo asociativo
            // Utiliza la función generateJobCard para generar la tarjeta de resultado
            $searchResults .= '<div class="res-card">' . generateJobCard($row) . '</div>';
        }
        
    } else {
        $searchResults .= '<p>No se encontraron resultados para la búsqueda.</p>';
    }
}

// No es necesario cerrar la conexión aquí, ya que se cerrará automáticamente cuando el script termine

?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Search Results</title>
    <!-- Agrega tus enlaces a hojas de estilo y otros recursos aquí -->
</head>
<body>
<?php include 'header.php'; ?>
<div class="container mx-auto mt-5">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <h1 class="display-4">Search Results</h1>
            <form action="search_results.php" method="GET" class="input-group mb-3">
                <div class="input-group">
                    <input type="search" class="form-control form-rounded-3" placeholder="Kirjoita Ammatti tai työtehtävä" aria-label="Search" aria-describedby="search-addon"  name="jobSearchText">
                    <button type="submit" class="btn btn-primary custom-button" name="submit">Hae <i class="fas fa-search"></i></button>
                </div>
            </form>
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
            <!-- Agrega tus controles de paginación aquí -->
        </div>
    </div>
</div>
<?php include 'footer.html'; ?>
<!-- Agrega tus scripts JavaScript aquí -->
</body>
</html>
