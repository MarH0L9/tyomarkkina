<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Incluye el archivo de configuración local y las funciones
include 'config.php';
include 'functions/functions.php';

$dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password);
// Obtén los valores de los filtros desde la solicitud Ajax
$keyword = $_POST['jobSearchText'] ?? '';
$sijainti = $_POST['sijainti'] ?? '';
$julkaistu = $_POST['julkaistu'] ?? '';
$palvelusuhde = $_POST['palvelusuhde'] ?? '';
$tyokieli = $_POST['tyokieli'] ?? '';
$tyoaika = $_POST['tyoaika'] ?? '';
$vaatimukset = $_POST['vaatimukset'] ?? '';
$tehtava = $_POST['tehtava'] ?? '';


// Construye la consulta SQL basada en los filtros seleccionados
$sql = "SELECT * FROM jobs WHERE 1 = 1 AND hyvaksytty = 1"; // Inicializa la consulta

$params = []; // Esta será nuestra matriz de parámetros

// Agrega condiciones según los filtros seleccionados
if (!empty($keyword)) {
    $sql .= " AND (otsikko LIKE :keyword OR kunta LIKE :keyword OR sijainti LIKE :keyword OR tyokieli LIKE :keyword OR yrityksenNimi LIKE :keyword)";
    $params['keyword'] = '%' . $keyword . '%';
}
if (!empty($sijainti)) {
    $sql .= " AND sijainti = :sijainti";
    $params['sijainti'] = $sijainti;
}

if (!empty($julkaistu)) {
    $dateLimit = ''; // inicializar
    if ($julkaistu === '24h') {
        $dateLimit = date('Y-m-d H:i:s', strtotime('-1 day'));
    } elseif ($julkaistu === '3d') {
        $dateLimit = date('Y-m-d H:i:s', strtotime('-3 days'));
    } elseif ($julkaistu === '1w') {
        $dateLimit = date('Y-m-d H:i:s', strtotime('-7 days'));
    }
    $sql .= " AND julkaistu >= :dateLimit";
    $params['dateLimit'] = $dateLimit;
}
if (!empty($palvelusuhde)) {
    $sql .= " AND palvelusuhde = :palvelusuhde";
    $params['palvelusuhde'] = $palvelusuhde;
}
if (!empty($tyokieli)) {
    // Utiliza el operador LIKE para buscar el idioma en la lista de idiomas
    $sql .= " AND (tyokieli LIKE :tyokieli OR tyokieli LIKE :tyokieli2 OR tyokieli LIKE :tyokieli3)";
    $params['tyokieli'] = '%' . $tyokieli . '%';                    // Etsii kieli joka puolella
    $params['tyokieli2'] = '%' . $tyokieli . ',%';                   //kieli jos on ensinmäinen listalla
    $params['tyokieli3'] = '%,' . $tyokieli;                        // Kieli jos on viimeinen listalla
}

if (!empty($tyoaika)) {
    $sql .= " AND tyoaika = :tyoaika";
    $params['tyoaika'] = $tyoaika;
}
if (!empty($tehtava)) {
    $sql .= " AND tehtava = :tehtava";
    $params['tehtava'] = $tehtava;
}



// Ejecuta la consulta SQL
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($results) {
    $searchResults = '';
    
    foreach ($results as $row) {
        $searchResults .= '<div class="res-card">' . generateJobCard($row) . '</div>';
    }
    
    echo $searchResults;
} else {
    echo '<div class="alert alert-danger" role="alert">';
    echo '<i class="fas fa-exclamation-triangle"></i>';
    echo ' Näillä hakuehdoilla ei löytynyt työtarjouksia. Kokeile uudestaan.';
    echo '</div>';
}

?>
