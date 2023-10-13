<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'functions/functions.php';
require 'config.php';
$pdo = new PDO($dsn, $username, $password);
$userId = $_SESSION['user_id'];

// Verifica si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibe los datos del formulario
    $yrityksen_nimi = $_POST['yrityksen_nimi'];
    $Y_tunnus = $_POST['Y_tunnus'];
    $osoite = $_POST['osoite'];
    $puhelin = $_POST['puhelin'];
    $verkkosivusto = $_POST['verkkosivusto'];

    // Actualiza los campos en la base de datos
    $query = "UPDATE tyonantajat SET yrityksen_nimi = :yrityksen_nimi, Y_tunnus = :Y_tunnus, osoite = :osoite, puhelin = :puhelin, verkkosivusto = :verkkosivusto WHERE user_id = :user_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':yrityksen_nimi', $yrityksen_nimi);
    $stmt->bindParam(':Y_tunnus', $Y_tunnus);
    $stmt->bindParam(':osoite', $osoite);
    $stmt->bindParam(':puhelin', $puhelin);
    $stmt->bindParam(':verkkosivusto', $verkkosivusto);
    $stmt->bindParam(':user_id', $userId);

    if ($stmt->execute()) {
        // Marca la actualización como exitosa
        $actualizacionExitosa = true;
    } else {
        // Si ocurre un error, muestra un mensaje de error
        $_SESSION['error_message'] = "Virhe päivitettäessä profiilia.";
    }
}

// Recupera los datos del perfil de la empresa para mostrar en el formulario
$query = "SELECT tp.*, u.email FROM tyonantajat tp
          JOIN users u ON tp.user_id = u.id
          WHERE tp.user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$profile = $stmt->fetch(PDO::FETCH_ASSOC);


//Omat mainokset:
$queryAnuncios = "SELECT * FROM jobs WHERE antajaid = :antajaid";
$stmtAnuncios = $pdo->prepare($queryAnuncios);
$stmtAnuncios->bindParam(':antajaid', $userId);
$stmtAnuncios->execute();
$anuncios = $stmtAnuncios->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
</head>
<?php include 'header.php'; ?>
<body>
    <main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2><i class="fa-regular fa-building fa-lm"></i> Yrityksen Profiili</h2><hr>
                <form action="yrityksen_profiili.php" method="POST" class="mt-4">
                    <!-- Aquí puedes incluir los campos de la tabla tyonantajat, por ejemplo: -->
                <div class="row"> 
                    <div class="mb-3">
                        <label for="email" class="form-label">Sähköposti</label>
                        <input type="text" class="form-control" id="email" name="email" value="<?php echo $profile ? $profile['email'] : ''; ?>" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="yrityksen_nimi" class="form-label">Yrityksen Nimi</label>
                        <input type="text" class="form-control" id="yrityksen_nimi" name="yrityksen_nimi" value="<?php echo $profile ? $profile['yrityksen_nimi'] : ''; ?>">
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="Y_tunnus" class="form-label">Y-Tunnus</label>
                        <input type="text" class="form-control" id="Y_tunnus" name="Y_tunnus" value="<?php echo $profile ? $profile['Y_tunnus'] : ''; ?>">
                    </div>
                    <div class="text-end">
                        <a href="reset_password.php" class="btn btn-primary btn-sm active " role="button" aria-pressed="true" ><i class="fa-regular fa-pen-to-square fa-lg"></i></i> Vaihda Salasana</a>
                    </div>
                </div>
                    <div class="mb-3">
                        <label for="osoite" class="form-label">Osoite</label>
                        <input type="text" class="form-control" id="osoite" name="osoite" value="<?php echo $profile ? $profile['osoite'] : ''; ?>">
                    </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="puhelin" class="form-label">Puhelin</label>
                        <input type="text" class="form-control" id="puhelin" name="puhelin" value="<?php echo $profile ? $profile['puhelin'] : ''; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="verkkosivusto" class="form-label">Verkkosivusto</label>
                        <input type="text" class="form-control" id="verkkosivusto" name="verkkosivusto" value="<?php echo $profile ? $profile['verkkosivusto'] : ''; ?>">
                    </div>
                </div>
                    <div class="mb-3">
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary custom-save">
                            <i class="fa-regular fa-floppy-disk"></i> Tallenna
                        </button>
                    </div>
                    </div><hr>
                </form>
            
                <div class="col-md-6">
    <h3>Voimassa työmainokset</h3><hr>
    <?php if (!empty($anuncios)) : ?>
        <div class="offer-list">
            <?php foreach ($anuncios as $anuncio) : ?>
                <div class="offer-item mb-3">
                    <?php echo $anuncio['otsikko']; ?> - <?php echo $anuncio['yrityksennimi']; ?>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $anuncio['id']; ?>">
                        Tarkista työtarjous
                    </button>
                    <a href="muokkaa_tyotarjous.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-warning btn-sm">Muokkaa</a>
                    <a href="poista_oferta.php?id=<?php echo $anuncio['id']; ?>" class="btn btn-danger btn-sm">Poista</a>
                </div>

                <!-- Modal para mostrar los detalles de la oferta -->
                <div class="modal fade" id="jobModal<?php echo $anuncio['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $anuncio['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="jobModalLabel<?php echo $anuncio['id']; ?>"><strong><?php echo $anuncio['otsikko']; ?></strong></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <h6><strong>Yritys:</strong> <?php echo $anuncio['yrityksennimi']; ?></h6>
                                <p><strong>Kuvaus:</strong> <?php echo $anuncio['kuvaus']; ?></p>
                                <p><strong>Tarkka kuvaus:</strong> <?php echo $anuncio['tarkkakuvaus']; ?></p>
                                <p><strong>Sijainti:</strong> <?php echo $anuncio['sijainti']; ?></p>
                                <p><strong>Kunta:</strong> <?php echo $anuncio['kunta']; ?></p>
                                <p><strong>Ala:</strong> <?php echo $anuncio['ala']; ?></p>
                                <p><strong>Julkaistu:</strong> <?php echo $anuncio['julkaistu']; ?></p>
                                <p><strong>Palvelusuhde:</strong> <?php echo $anuncio['palvelusuhde']; ?></p>
                                <p><strong>Työkieli:</strong> <?php echo $anuncio['tyokieli']; ?></p>
                                <p><strong>Voimassa:</strong> <?php echo $anuncio['voimassaolopaiva']; ?></p>
                                <p><strong>Yrityksen linkki:</strong> <?php echo $anuncio['yrityksenlinkki']; ?></p>
                                <!-- Añade más campos si es necesario -->
                            </div>
                            <!-- Botón de guardar cambios -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary custom-save">
                            <i class="fa-regular fa-floppy-disk"></i> Tallenna
                        </button>
                    </div>
                </form>

                <!-- Mensaje de actualización exitosa (se muestra solo cuando se actualiza) -->
                <?php if ($actualizacionExitosa) : ?>
                    <div class="alert alert-success mt-4" role="alert">
                        Profiili päivitetty onnistuneesti.
                    </div>
                <?php endif; ?>

                <!-- Mensaje de error (se muestra solo en caso de error) -->
                <?php if (isset($_SESSION['error_message'])) : ?>
                    <div class="alert alert-danger mt-4" role="alert">
                        <?php echo $_SESSION['error_message']; ?>
                    </div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <p>Ei voimassa olevia työtarjouksia.</p>
    <?php endif; ?>
</div>
        </div>
    </div>
    </main>
    <script>
    // Función para ocultar el mensaje después de un tiempo específico
    function hideMessage() {
        var message = document.querySelector('.alert'); // Selecciona el mensaje
        if (message) {
            setTimeout(function () {
                message.style.display = 'none'; // Oculta el mensaje
            }, 3000); // 3000 milisegundos (3 segundos)
        }
    }

    // Llama a la función para ocultar el mensaje cuando se carga la página
    window.addEventListener('load', hideMessage);
</script>
    <?php include 'footer.html'; ?>
</body>
</html>