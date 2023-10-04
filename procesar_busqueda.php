<?php
// Incluye el archivo de configuración local
include 'C:\xampp\htdocs\Projects\tunnukset.php';
include 'config.php';

// Inicializa la variable que almacenará los resultados
$searchResults = '';

// Obtén los valores de los filtros desde la solicitud Ajax
$keyword = isset($_GET['jobSearchText']) ? $_GET['jobSearchText'] : '';
$sijainti = isset($_GET['sijainti']) ? $_GET['sijainti'] : '';
$julkaistu = isset($_GET['julkaistu']) ? $_GET['julkaistu'] : '';
$continuity = isset($_GET['continuity']) ? $_GET['continuity'] : '';
$language = isset($_GET['language']) ? $_GET['language'] : '';
$workTime = isset($_GET['workTime']) ? $_GET['workTime'] : '';
$employment = isset($_GET['employment']) ? $_GET['employment'] : '';

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
    $sql .= " AND (Otsikko LIKE '%$keyword%' OR Kuvaus LIKE '%$keyword%' OR Sijainti LIKE '%$keyword%' OR YrityksenNimi LIKE '%$keyword%' OR Ala LIKE '%$keyword%' OR Vaatimukset LIKE '%$keyword%' OR YrityksenLinkki LIKE '%$keyword%')";
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
if (!empty($continuity)) {
    $sql .= " AND Continuity = '$continuity'";
}
if (!empty($language)) {
    $sql .= " AND TyoKieli = '$language'";
}
if (!empty($workTime)) {
    $sql .= " AND WorkTime = '$workTime'";
}
if (!empty($employment)) {
    $sql .= " AND PalveluSuhde = '$employment'";
}

// Ejecuta la consulta SQL
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Construye los resultados de la búsqueda
    $searchResults .= '<ul id="resultsList">';
    while ($row = $result->fetch_assoc()) {
        $searchResults .= '<li>';
        $searchResults .= '<h3><a href="detalle_oferta.php?id=' . $row['ID'] . '">' . $row['Otsikko'] . '</a></h3>';
        $searchResults .= '<p><strong>Sijainti:</strong> ' . $row['Sijainti'] . '</p>';
        // Puedes mostrar más detalles aquí si lo deseas
        $searchResults .= '</li>';
    }
    $searchResults .= '</ul>';
} else {
    $searchResults .= '<p>No se encontraron resultados para la búsqueda.</p>';
}

// Cierra la conexión a la base de datos
$conn->close();

// Devuelve los resultados como respuesta Ajax
echo $searchResults;
?>