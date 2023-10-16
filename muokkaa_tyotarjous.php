<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'yritys') {
    // Si el usuario no es una empresa, redirige al inicio de sesión
    header('Location: login.php');
    exit();
}

// Incluye el archivo de configuración de la base de datos
require 'config.php';

// Verifica si se proporciona un ID de oferta de trabajo válido en la URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $jobId = $_GET['id'];

    try {
        // Establece una conexión a la base de datos
        $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Consulta para obtener los detalles de la oferta de trabajo
        $query = "SELECT * FROM jobs WHERE id = :job_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':job_id', $jobId, PDO::PARAM_INT);
        $stmt->execute();

        // Verifica si se encontró la oferta de trabajo
        if ($job = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // A continuación, puedes utilizar los datos de $job para mostrar el formulario de modificación
        } else {
            // Si no se encontró la oferta de trabajo, puedes mostrar un mensaje de error o redirigir a otra página
            echo "Oferta de trabajo no encontrada.";
        }
    } catch (PDOException $e) {
        // Manejo de errores de la base de datos
        echo "Error de base de datos: " . $e->getMessage();
    }
} else {
    // Si no se proporciona un ID de oferta de trabajo válido, puedes mostrar un mensaje de error o redirigir a otra página
    echo "ID de oferta de trabajo no válido.";
}
?>


<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script src="script/haku_filter.js"></script>
    <?php include 'maakunnat.php'; ?>
</head>
<?php include 'header.php'; ?>
<body>
<main>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2><i class="fa-regular fa-edit fa-lm"></i> Muokkaa työtarjousta</h2><hr>
            <form action="paivita_tyotarjous.php" method="POST" class="mt-4">
            <div class="mb-3">
    <label for="otsikko" class="form-label">Otsikko</label>
    <input type="text" class="form-control" id="otsikko" name="otsikko" value="<?php echo $job['otsikko']; ?>">
</div>

<div class="mb-3">
    <label for="kuvaus" class="form-label">Kuvaus</label>
    <textarea class="form-control" id="kuvaus" name="kuvaus"><?php echo $job['kuvaus']; ?></textarea>
</div>

<div class="mb-3">
    <label for="tarkkakuvaus" class="form-label">Tarkka kuvaus</label>
    <textarea class="form-control" id="tarkkakuvaus" name="tarkkakuvaus"><?php echo $job['tarkkakuvaus']; ?></textarea>
</div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="sijainti" class="form-label" style="font-weight:bold;">Sijainti:</label>
                    <input class="form-control" id="sijainti" name="sijainti" value="<?php echo $job['sijainti']; ?>" readonly>            
                </div>

                <div class="col-md-6 mb-3">
                    <label for="kunta" class="form-label">Kunta</label>
                    <input type="text" class="form-control" id="kunta" name="kunta" value="<?php echo $job['kunta']; ?>">
                </div>
            </div>
    <div class="row">
        <div class="col-md-6 mb-3">
                    <label for="tehtava" class="form-label">Tehtävä</label>
                    <select class="form-select" id="tehtava" name="tehtava" value="<?php echo $job['tehtava'];?>">:
                        <option value="Asennus, huolto ja kunnossapito">Asennus, huolto ja kunnossapito</option>
                        <option value="Asiakaspalvelu">Asiakaspalvelu</option>
                        <option value="Asiantuntijatyö ja konsultointi">Asiantuntijatyö ja konsultointi</option>
                        <option value="Hallinto ja yleiset toimistotyöt">Hallinto ja yleiset toimistotyöt</option>
                        <option value="Henkilöstöala">Henkilöstöala</option>
                        <option value="Hyvinvointi- ja henkilöpalvelut">Hyvinvointi- ja henkilöpalvelut</option>
                        <option value="Johtotehtävät">Johtotehtävät</option>
                        <option value="Julkinen sektori ja järjestöt">Julkinen sektori ja järjestöt</option>
                        <option value="Kiinteistöala">Kiinteistöala</option>
                        <option value="Kuljetus, logistiikka ja liikenne">Kuljetus, logistiikka ja liikenne</option>
                        <option value="Kulttuuri-, viihde- ja taidealat">Kulttuuri-, viihde- ja taidealat</option>
                        <option value="Lakiala">Lakiala</option>
                        <option value="Markkinointi">Markkinointi</option>
                        <option value="Markkinointi, mainonta, media ja viestintä">Markkinointi, mainonta, media ja viestintä</option>
                        <option value="Myynti- ja kaupan ala">Myynti- ja kaupan ala</option>
                        <option value="Opetusala">Opetusala</option>
                        <option value="Opiskelijoiden työpaikat">Opiskelijoiden työpaikat</option>
                        <option value="Rakennusala">Rakennusala</option>
                        <option value="Ravintola- ja matkailuala">Ravintola- ja matkailuala</option>
                        <option value="Siivous, puhtaanapito ja jätehuolto">Siivous, puhtaanapito ja jätehuolto</option>
                        <option value="Sosiaali- ja hoiva-ala">Sosiaali- ja hoiva-ala</option>
                        <option value="Taloushallinto ja pankkiala">Taloushallinto ja pankkiala</option>
                        <option value="Teollisuus ja teknologia">Teollisuus ja teknologia</option>
                        <option value="Terveys- ja sosiaalipalvelut">Terveys- ja sosiaalipalvelut</option>
                    </select>
                </div>

        <div class="col-md-6 mb-3">
            <label for="tyoaika" class="form-label">Työaika</label>
            <input type="text" class="form-control" id="tyoaika" name="tyoaika" value="<?php echo $job['tyoaika']; ?>">
        </div>
    </div>
<div class="row">
    <div class="col-md-6 mb-3">
        <label for="palkka" class="form-label">Palkka</label>
        <input type="text" class="form-control" id="palkka" name="palkka" value="<?php echo $job['palkka']; ?>">
    </div>

    <div class="col-md-6 mb-3">
        <label for="voimassaolopaiva" class="form-label">Viimeinen hakupäivä</label>
        <input type="date" class="form-control" id="voimassaolopaiva" name="voimassaolopaiva" value="<?php echo $job['voimassaolopaiva']; ?>">
    </div>
</div>
<div class="row">
    <div class="col mb-3">
        <label for="yrityksenlinkki" class="form-label">Linkki</label>
        <input type="text" class="form-control" id="yrityksenlinkki" name="yrityksenlinkki" value="<?php echo $job['yrityksenlinkki'];?>">
    </div>   
    </div>
    <input type="hidden" name="jobId" value="<?php echo $jobId; ?>">             
<div class="mb-3 text-center" >
<button type="submit" class="btn btn-warning"><i class="fa-solid fa-wrench fa-lg"></i></i> Päivitä</button>
</div>
            </form>
        </div>
    </div>
</div>
</main>
<?php include 'footer.html'; ?>
</body>
</html>