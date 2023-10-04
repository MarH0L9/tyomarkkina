<!DOCTYPE html>
<html lang="fi">
<head>
    <title>Detalles de la Oferta de Empleo</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css"> <!-- Agrega tus estilos CSS personalizados aquí si es necesario -->
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <?php
                // Verifica si se proporciona un ID válido en la URL
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    // Conecta a la base de datos (asegúrate de tener la configuración en config.php)
                    $conn = new mysqli($server, $username, $password, $database);

                    // Verifica la conexión
                    if ($conn->connect_error) {
                        die("La conexión a la base de datos falló: " . $conn->connect_error);
                    }

                    // Consulta SQL para obtener los detalles de la oferta según el ID
                    $offer_id = $_GET['id'];
                    $sql = "SELECT * FROM offers WHERE ID = $offer_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Muestra los detalles de la oferta
                        $row = $result->fetch_assoc();
                        echo '<h1>' . $row['Otsikko'] . '</h1>';
                        echo '<p><strong>Sijainti:</strong> ' . $row['Sijainti'] . '</p>';
                        echo '<p><strong>Yrityksen Nimi:</strong> ' . $row['YrityksenNimi'] . '</p>';
                        echo '<p><strong>Ala:</strong> ' . $row['Ala'] . '</p>';
                        echo '<p><strong>Palkka:</strong> ' . number_format($row['Palkka'], 2) . ' €</p>';
                        echo '<p><strong>Julkaistu:</strong> ' . date('d.m.Y', strtotime($row['Julkaisupaiva'])) . '</p>';
                        echo '<p><strong>Voimassaolo Päivä:</strong> ' . date('d.m.Y', strtotime($row['VoimassaoloPaiva'])) . '</p>';
                        echo '<p><strong>Vaatimukset:</strong></p>';
                        echo '<p>' . nl2br($row['Vaatimukset']) . '</p>';
                        if (!empty($row['YrityksenLinkki'])) {
                            echo '<p><strong>Yrityksen Linkki:</strong> <a href="' . $row['YrityksenLinkki'] . '" target="_blank">' . $row['YrityksenLinkki'] . '</a></p>';
                        }

                        // Cierra la conexión a la base de datos
                        $conn->close();
                    } else {
                        echo '<p>No se encontraron detalles para la oferta de empleo.</p>';
                    }
                } else {
                    echo '<p>ID de oferta no válido.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'footer.html'; ?>
</body>
</html>
