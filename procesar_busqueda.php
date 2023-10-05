
<?php
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

try {
    // Construye la consulta SQL basada en los filtros seleccionados
    $sql = "SELECT * FROM offers WHERE 1 = 1"; // Inicializa la consulta

    // Agrega condiciones según los filtros seleccionados
    if (!empty($keyword)) {
        $sql .= " AND (Otsikko LIKE :keyword OR Kuvaus LIKE :keyword  OR Kunta LIKE :keyword OR Sijainti LIKE :keyword OR YrityksenNimi LIKE :keyword OR Ala LIKE :keyword OR Vaatimukset LIKE :keyword OR YrityksenLinkki LIKE :keyword)";
    }
    if (!empty($sijainti)) {
        $sql .= " AND Sijainti = :sijainti";
    }

    if (!empty($julkaistu)) {
        if ($julkaistu === '24h') {
            $dateLimit = date('Y-m-d H:i:s', strtotime('-1 day'));
        } elseif ($julkaistu === '3d') {
            $dateLimit = date('Y-m-d H:i:s', strtotime('-3 days'));
        } elseif ($julkaistu === '1w') {
            $dateLimit = date('Y-m-d H:i:s', strtotime('-7 days'));
        }
        $sql .= " AND Julkaisupaiva >= :dateLimit";
    }
    if (!empty($palvelusuhde)) {
        $sql .= " AND PalveluSuhde = :palvelusuhde";
    }
    if (!empty($tyokieli)) {
        $sql .= " AND TyoKieli = :tyokieli";
    }
    if (!empty($tyoaika)) {
        $sql .= " AND TyoAika = :tyoaika";
    }
    if (!empty($vaatimukset)) {
        $sql .= " AND Vaatimukset = :vaatimukset";
    }

    // Preparar la consulta
    $stmt = $pdo->prepare($sql);

    // Asignar valores a los parámetros
    if (!empty($keyword)) {
        $stmt->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    }
    if (!empty($sijainti)) {
        $stmt->bindValue(':sijainti', $sijainti, PDO::PARAM_STR);
    }
    if (!empty($julkaistu)) {
        $stmt->bindValue(':dateLimit', $dateLimit, PDO::PARAM_STR);
    }
    if (!empty($palvelusuhde)) {
        $stmt->bindValue(':palvelusuhde', $palvelusuhde, PDO::PARAM_STR);
    }
    if (!empty($tyokieli)) {
        $stmt->bindValue(':tyokieli', $tyokieli, PDO::PARAM_STR);
    }
    if (!empty($tyoaika)) {
        $stmt->bindValue(':tyoaika', $tyoaika, PDO::PARAM_STR);
    }
    if (!empty($vaatimukset)) {
        $stmt->bindValue(':vaatimukset', $vaatimukset, PDO::PARAM_STR);
    }

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        // Construye los resultados de la búsqueda

        foreach ($results as $row) {
            // Utiliza la función generateJobCard para generar la tarjeta de resultado
            $searchResults .= '<div class="res-card">' . generateJobCard($row) . '</div>';
        }

    } else {
        $searchResults .= '<p>No se encontraron resultados para la búsqueda.</p>';
    }
} catch (PDOException $e) {
    // Manejo de errores de PDO
    $searchResults .= '<p>Error en la consulta: ' . $e->getMessage() . '</p>';
}

// Devuelve los resultados como respuesta Ajax
echo $searchResults;
?>
