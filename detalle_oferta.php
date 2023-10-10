<html lang="fi">
<head>
    <title>Detalles de la Oferta de Empleo</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Agrega tus estilos CSS personalizados aquí si es necesario -->
</head>
<body style="background-color=grey;">
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-5" >
        <div class="row justify-content-center mt-5" >
            <div class="col-md-8">
                <?php
                include 'config.php';
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
                    $sql = "SELECT * FROM jobs WHERE ID = $offer_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Muestra los detalles de la oferta
                        $row = $result->fetch_assoc();
                        echo '<h1 class="title-bg">' . $row['Otsikko'] . '</h1>';
                        echo '<p><i class="fas fa-map-marker-alt" style="color: #0f0f10;"></i><strong>Sijainti:</strong> ' . $row['Sijainti'] . ', ' . $row['Kunta'] . '</p>';
                        echo '<p class="company-icon"><strong>Yrityksen Nimi:</strong> ' . $row['YrityksenNimi'] . '</p>';
                        echo '<div class="row">';
                        echo '<div class="col-md-6 date-icon"><strong>Julkaistu:</strong> ' . date('d.m.Y', strtotime($row['julkaistu'])) . '</div>';
                        echo '<div class="col-md-6 date-icon"><strong>Voimassaolo Päivä:</strong> ' . date('d.m.Y', strtotime($row['VoimassaoloPaiva'])) . '</div>';
                        echo '</div>';
                        echo '<p><strong>Kuvaus:</strong></p>';
                        echo '<p>' . nl2br($row['Kuvaus']) . '</p>';
                        echo '<p><strong>Tarkka Kuvaus:</strong></p>';
                        echo '<p>' . nl2br($row['tarkkakuvaus']) . '</p>';
                        echo '<p><strong>Vaatimukset:</strong></p>';
                        echo '<p>' . nl2br($row['Vaatimukset']) . '</p>';
                        if (!empty($row['YrityksenLinkki'])) {
                            if (!empty($row['YrityksenLinkki'])) {
                                echo '<a href="' . $row['YrityksenLinkki'] . '" target="_blank" class="btn btn-primary">Hae työpaikkaa</a>';
                            }
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
