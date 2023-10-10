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
$sijainti = $_POST['Sijainti'] ?? '';
$julkaistu = $_POST['Julkaistu'] ?? '';
$palvelusuhde = $_POST['PalveluSuhde'] ?? '';
$tyokieli = $_POST['TyoKieli'] ?? '';
$tyoaika = $_POST['TyoAika'] ?? '';
$vaatimukset = $_POST['Vaatimukset'] ?? '';
$ala = $_POST['Ala'] ?? '';

// Construye la consulta SQL basada en los filtros seleccionados
$sql = "SELECT * FROM jobs WHERE 1 = 1"; // Inicializa la consulta

$params = []; // Esta será nuestra matriz de parámetros

// Agrega condiciones según los filtros seleccionados
if (!empty($keyword)) {
    $sql .= " AND (Otsikko LIKE :keyword OR Kunta LIKE :keyword OR Sijainti LIKE :keyword OR YrityksenNimi LIKE :keyword)";
    $params['keyword'] = '%' . $keyword . '%';
}
if (!empty($sijainti)) {
    $sql .= " AND Sijainti = :sijainti";
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
// Repetir este patrón para el resto de tus variables/filtros
if (!empty($palvelusuhde)) {
    $sql .= " AND PalveluSuhde = :palvelusuhde";
    $params['palvelusuhde'] = $palvelusuhde;
}
if (!empty($tyokieli)) {
    $sql .= " AND TyoKieli = :tyokieli";
    $params['tyokieli'] = $tyokieli;
}
if (!empty($tyoaika)) {
    $sql .= " AND TyoAika = :tyoaika";
    $params['tyoaika'] = $tyoaika;
}
if (!empty($ala)) {
    $sql .= " AND Ala = :ala";
    $params['ala'] = $ala;
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
    echo 'No se encontraron resultados para la búsqueda.';
}

?>
