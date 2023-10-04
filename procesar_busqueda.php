
<?php
// Incluye el archivo de configuración local
include 'C:\xampp\htdocs\Projects\tunnukset.php';
include 'config.php';
include 'functions/functions.php';

// Inicializa la variable que almacenará los resultados
$searchResults = '';

// Obtén los valores de los filtros desde la solicitud Ajax
$keyword = isset($_GET['jobSearchText']) ? $_GET['jobSearchText'] : '';
$sijainti = isset($_GET['Sijainti']) ? $_GET['Sijainti'] : '';
$julkaistu = isset($_GET['Julkaistu']) ? $_GET['Julkaistu'] : '';
$palvelusuhde = isset($_GET['PalveluSuhde']) ? $_GET['PalveluSuhde'] : '';
$tyokieli = isset($_GET['TyoKieli']) ? $_GET['TyoKieli'] : '';
$tyoaika = isset($_GET['TyoAika']) ? $_GET['TyoAika'] : '';
$vaatimukset = isset($_GET['Vaatimukset']) ? $_GET['Vaatimukset'] : '';

// Conecta a la base de datos (asegúrate de tener la configuración en config.php)
$conn = new mysqli($server, $username, $password, $database);

// Verifica la conexión
if ($conn->connect_error) {
    die("La conexión a la base de datos falló: " . $conn->connect_error);
}

// Inicializa la variable para la fecha límite
$dateLimit = '';

// Calcula la fecha límite en función de la categoría seleccionada

// Construye la consulta SQL basada en los filtros seleccionados
$sql = "SELECT * FROM offers WHERE 1 = 1"; // Inicializa la consulta

// Agrega condiciones según los filtros seleccionados
if (!empty($keyword)) {
    $sql .= " AND (Otsikko LIKE '%$keyword%' OR Kuvaus LIKE '%$keyword%'  OR Kunta LIKE '%$keyword%' OR Sijainti LIKE '%$keyword%' OR YrityksenNimi LIKE '%$keyword%' OR Ala LIKE '%$keyword%' OR Vaatimukset LIKE '%$keyword%' OR YrityksenLinkki LIKE '%$keyword%')";
}
if (!empty($sijainti)) {
    $sql .= " AND Sijainti = '$sijainti'";
}

if (!empty($julkaistu)) {
    if ($julkaistu === '24h') {
        $dateLimit = date('Y-m-d H:i:s', strtotime('-1 day'));
    } elseif ($julkaistu === '3d') {
        $dateLimit = date('Y-m-d H:i:s', strtotime('-3 days'));
    } elseif ($julkaistu === '1w') {
        $dateLimit = date('Y-m-d H:i:s', strtotime('-7 days'));
    }
    $sql .= " AND Julkaisupaiva >= '$dateLimit'";
}
if (!empty($palvelusuhde)) {
    $sql .= " AND PalveluSuhde = '$palvelusuhde'";
}
if (!empty($tyokieli)) {
    $sql .= " AND TyoKieli = '$tyokieli'";
}
if (!empty($tyoaika)) {
    $sql .= " AND TyoAika = '$tyoaika'";
}
if (!empty($vaatimukset)) {
    $sql .= " AND Vaatimukset = '$vaatimukset'";
}

// Ejecuta la consulta SQL
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Construye los resultados de la búsqueda
    
    while ($row = $result->fetch_assoc()) {
        // Utiliza la función generateJobCard para generar la tarjeta de resultado
        $searchResults .= '<div class="res-card">' . generateJobCard($row) . '</div>';
    }
    
} else {
    $searchResults .= '<p>No se encontraron resultados para la búsqueda.</p>';
}

// Cierra la conexión a la base de datos
$conn->close();

// Devuelve los resultados como respuesta Ajax
echo $searchResults;
?>
