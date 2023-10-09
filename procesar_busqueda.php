
<?php
var_dump($_GET);
// Incluye el archivo de configuración local
include 'C:\xampp\htdocs\Projects\tunnukset.php';
include 'config.php';
include 'functions/functions.php';

// Inicializa la variable que almacenará los resultados
$searchResults = '';

// Obtén los valores de los filtros desde la solicitud Ajax
$keyword = isset($_POST['jobSearchText']) ? $_POST['jobSearchText'] : '';
$sijainti = isset($_POST['Sijainti']) ? $_POST['Sijainti'] : '';
$julkaistu = isset($_POST['Julkaistu']) ? $_POST['Julkaistu'] : '';
$palvelusuhde = isset($_POST['PalveluSuhde']) ? $_POST['PalveluSuhde'] : '';
$tyokieli = isset($_POST['TyoKieli']) ? $_POST['TyoKieli'] : '';
$tyoaika = isset($_POST['TyoAika']) ? $_POST['TyoAika'] : '';
$vaatimukset = isset($_POST['Vaatimukset']) ? $_POST['Vaatimukset'] : '';
$ala = isset($_POST['Ala']) ? $_POST['Ala'] : '';

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
$sql = "SELECT * FROM jobs WHERE 1 = 1"; // Inicializa la consulta


// Agrega condiciones según los filtros seleccionados
if (!empty($keyword)) {
    $sql .= " AND (Otsikko LIKE '%$keyword%' OR Kunta LIKE '%$keyword%' OR Sijainti LIKE '%$keyword%' OR YrityksenNimi LIKE '%$keyword%')";
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
    $sql .= " AND julkaistu >= '$dateLimit'";
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
if (!empty($ala)) {
    $sql .= " AND Ala = '$ala'";
}

// Ejecuta la consulta SQL
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Construye los resultados de la búsqueda
    $searchResults = '';
    
    while ($row = $result->fetch_assoc()) {
        // Utiliza la función generateJobCard para generar la tarjeta de resultado
        $searchResults .= '<div class="res-card">' . generateJobCard($row) . '</div>';
    }
     // Devuelve los resultados como respuesta Ajax
     echo $searchResults;
    
} else {
    echo 'No se encontraron resultados para la búsqueda.';
}

// Cierra la conexión a la base de datos
$conn->close();

?>
