<html lang="fi">
<head>
    <title>Työtarjouksen tiedot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Agrega tus estilos CSS personalizados aquí si es necesario -->
</head>
<body style="background-color=grey;">
    <?php include 'header.php'; ?>
    <main>
    <div class="container mx-auto mt-5" >
        <div class="row justify-content-center mt-5" >
            <div class="col-md-8">
                <?php
                include 'config.php';
                // Verifica si se proporciona un ID válido en la URL
                if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                    // Conecta a la base de datos (asegúrate de tener la configuración en config.php)
                    $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";

                    // Verifica la conexión
                try {
                        $pdo = new PDO($dsn, $username, $password);

                    // Consulta SQL para obtener los detalles de la oferta según el ID
                    $offer_id = $_GET['id'];
                    $stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = :id");
                    $stmt->bindParam(':id', $offer_id, PDO::PARAM_INT);
                    $stmt->execute();

                    if ($stmt->rowCount() > 0) {
                        // Työtarjouksen tiedot
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo '<h1 class="title-bg">' . $row['otsikko'] . '</h1>';
                        echo '<hr>';
                        echo '<div class="image-container">';
                        if (!empty($row['kuva'])) {
                            echo '<img src="' . $row['kuva'] . '" alt="Kuva">';
                        } else {
                            echo '<p>No hay imagen disponible.</p>';
                        }
                        echo '</div>';
                        echo '<hr>';
                        echo '<p><i class="fas fa-map-marker-alt" style="color: #0f0f10;"></i><strong>Sijainti:</strong> ' . $row['sijainti'] . ', ' . $row['kunta'] . '</p>';
                        echo '<p class="company-icon"><strong>Yrityksen Nimi:</strong> ' . $row['yrityksennimi'] . '</p>';
                        echo '<div class="row">';
                        echo '<div class="col-md-6 date-icon"><strong>Julkaistu:</strong> ' . date('d.m.Y', strtotime($row['julkaistu'])) . '</div>';
                        echo '<div class="col-md-6 date-icon"><strong>Voimassaolo Päivä:</strong> ' . date('d.m.Y', strtotime($row['voimassaolopaiva'])) . '</div>';
                        echo '</div>';
                        echo '<hr>';
                        echo '<p><strong>Kuvaus:</strong></p>';
                        echo '<p>' . nl2br($row['kuvaus']) . '</p>';
                        echo '<p><strong>Tarkka Kuvaus:</strong></p>';
                        echo '<p>' . nl2br($row['tarkkakuvaus']) . '</p>';
                        echo '<p><strong>Vaatimukset:</strong></p>';
                        echo '<p>' . nl2br($row['vaatimukset']) . '</p>';
                        if (!empty($row['yrityksenlinkki'])) {
                            if (!empty($row['yrityksenlinkki'])) {
                                echo '<div class="mb-3">';
                                echo '<div class="text-center">';
                                echo '<a href="' . $row['yrityksenlinkki'] . '" target="_blank" class="btn btn-primary"><i class="fa-regular fa-square-check fa-lg"></i> Hae työpaikkaa</a>';
                                echo '</div>';
                                echo '</div>';
                            }
                        }
                    
                    } else {
                        echo '<p>Näillä hakuehdoilla ei löytynyt työtarjouksia. Kokeile uudestaan.</p>';
                    }
                } catch (PDOException $e) {
                    die("La conexión a la base de datos falló: " . $e->getMessage());
                }
            } else {
                echo '<p>ID on virheellinen.</p>';
            }
            ?>
            </div>
        </div>
    </div>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>
