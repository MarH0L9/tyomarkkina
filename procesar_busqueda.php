<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';
include 'functions/functions.php';

$dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password);

// Saa hakuehdot
$keyword = $_POST['jobSearchText'] ?? '';
$sijainti = $_POST['sijainti'] ?? '';
$julkaistu = $_POST['julkaistu'] ?? '';
$palvelusuhde = $_POST['palvelusuhde'] ?? '';
$tyokieli = $_POST['tyokieli'] ?? '';
$tyoaika = $_POST['tyoaika'] ?? '';
$vaatimukset = $_POST['vaatimukset'] ?? '';
$tehtava = $_POST['tehtava'] ?? '';


// Rakentaa SQL-kyselyn
$sql = "SELECT * FROM jobs WHERE 1 = 1 AND hyvaksytty = 1";

$params = []; // Parametrien arvot

// Lisää parametrit ja SQL-lausekkeet
if (!empty($keyword)) {
    $sql .= " AND (otsikko LIKE :keyword OR kunta LIKE :keyword OR sijainti LIKE :keyword OR tyokieli LIKE :keyword OR yrityksenNimi LIKE :keyword)";
    $params['keyword'] = '%' . $keyword . '%';
}
if (!empty($sijainti)) {
    $sql .= " AND sijainti = :sijainti";
    $params['sijainti'] = $sijainti;
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
    $sql .= " AND julkaistu >= :dateLimit";
    $params['dateLimit'] = $dateLimit;
}
if (!empty($palvelusuhde)) {
    $sql .= " AND palvelusuhde = :palvelusuhde";
    $params['palvelusuhde'] = $palvelusuhde;
}
if (!empty($tyokieli)) {

    // 
    $sql .= " AND (tyokieli LIKE :tyokieli OR tyokieli LIKE :tyokieli2 OR tyokieli LIKE :tyokieli3)";
    $params['tyokieli'] = '%' . $tyokieli . '%';                    
    $params['tyokieli2'] = '%' . $tyokieli . ',%';                  
    $params['tyokieli3'] = '%,' . $tyokieli;                        
}

if (!empty($tyoaika)) {
    $sql .= " AND tyoaika = :tyoaika";
    $params['tyoaika'] = $tyoaika;
}
if (!empty($tehtava)) {
    $sql .= " AND tehtava = :tehtava";
    $params['tehtava'] = $tehtava;
}


//Jos halutaan vaan ensinmäiset 5 tulosta
// $offset = (int)($_POST['offset'] ?? 0);  // Convertir a entero
//$sql .= " ORDER BY julkaistu DESC LIMIT 5 OFFSET $offset"; 

$sql .= " ORDER BY julkaistu DESC";

// Suorittaa SQL kyselyn
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