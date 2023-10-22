<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include 'config.php';

$userId = $_SESSION['user_id'];
$dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
$pdo = new PDO($dsn, $username, $password);

// Consulta SQL para obtener los datos del usuario
$stmt = $pdo->prepare("SELECT * FROM user_profile WHERE user_id = :user_id");
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$userData = $stmt->fetch(PDO::FETCH_ASSOC);

$offer_id = $_GET['job_id'];
$stmt = $pdo->prepare("SELECT otsikko, sijainti,kuva FROM jobs WHERE id = :id");
$stmt->bindParam(':id', $offer_id, PDO::PARAM_INT);
$stmt->execute();

$offerData = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE job_id = :job_id AND applicant_id = :user_id");
$stmt->bindParam(':job_id', $offer_id, PDO::PARAM_INT);
$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
$stmt->execute();

$hasApplied = $stmt->fetchColumn() > 0;

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
    <?php include 'header.php'; ?>
</head>
<body>
<main>
<div class="container mt-5">
        <div class="row justify-content-center">
            <fieldset class="col-md-8">
                <h2 style="color:#0B3B0B;"><?php echo $offerData['otsikko']; ?> - <?php echo $offerData['sijainti']; ?></h2>
                <hr>
                <?php if ($hasApplied): ?>
                    <div class="alert alert-warning" role="alert">
                        <h3 style="color:#0B3B0B;">Olet jo lähettänyt hakemuksen tähän työpaikkaan.</h3>
                        <p>Voit tarkistaa hakemuksesi Oma profiilissä <a href="oma_profiili.php">tästä</a>.</p>
                        </div>
                <?php else: ?>
                <form action="process_application.php" class="needs-validation" id="aplicationForm" method="POST" enctype="multipart/form-data" novalidate>
                    <div class="mb-3">
                        <label for="etunimi" class="form-label">Etunimi:</label>
                        <input type="text" class="form-control" name="etunimi" value="<?php echo $userData['etunimi']; ?>" required>
                        <div class="invalid-feedback">
                            Täytä etunimi kentä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="sukunimi" class="form-label">Sukunimi:</label>
                        <input type="text" class="form-control" name="sukunimi" value="<?php echo $userData['sukunimi']; ?>" required>
                        <div class="invalid-feedback">
                           Täytä sukunimi kentä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $userData['Email']; ?>" required>
                        <div class="invalid-feedback">
                            Täytä email kentä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="puhelin" class="form-label">Puhelin:</label>
                        <input type="tel" class="form-control" name="puhelin" value="<?php echo $userData['puhelin']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="linkedin" class="form-label">LinkedIn:</label>
                        <input type="text" class="form-control" name="linkedin" value="<?php echo $userData['linkedin_url']; ?>">
                    </div>

                    <div class="mb-3">
                        <label for="opinnot" class="form-label">Opinnot:</label>
                        <input class="form-control" name="opinnot" value="<?php echo $userData['koulutustaso']; ?>" required></textarea>
                        <div class="invalid-feedback">
                            täytä opinnot kentä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="viimeinen_tyo" class="form-label">Viimeinen työpaikka:</label>
                        <input type="text" class="form-control" name="viimeinen_tyo" value="<?php echo $userData['viimeisin_tyotehtava']; ?>" required>
                        <div class="invalid-feedback">
                            Täytä viimeinen työpaikka kentä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kokemus" class="form-label">Työkokemus:</label>
                        <select class="form-control" name="kokemus"  required>
                            <option value="">Valitse...</option>
                            <option value="Ei ole työkomemusta">Ei ole työkomemusta</option>
                            <option value="0-1 vuotta">0-1 vuotta</option>
                            <option value="2 vuotta">1-3 vuotta</option>
                            <option value="3-5 vuotta">3-6 vuotta</option>
                            <option value="+6 vuotta">+6 vuotta</option>
                        </select>
                        <div class="invalid-feedback">
                            Täytä työkokemus kentä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="aloitus" class="form-label">Milloin voisit aloittaa:</label>
                        <input type="date" class="form-control" name="aloitus" required>
                        <div class="invalid-feedback">
                            Valitse aloitus päivä.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="miksi" class="form-label">Miksi haluat tähän työpaikaan?</label>
                        <textarea class="form-control" name="miksi"></textarea>
                        <div class="invalid-feedback">
                            Täytä miksi haluat tähän työpaikaan.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="cv" class="form-label">Lataa CV:</label>
                        <input type="file" class="form-control" name="cv" accept=".pdf, .doc, .docx" required>
                        <div class="invalid-feedback">
                            Liitä CV.
                        </div>
                    </div>

                    <input type="hidden" name="job_id" value="<?php echo $offer_id; ?>">
                    
                    <div class="mb-3 text-center">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-square-arrow-up-right fa-lg"></i> Lähetä hakemus</button>
                    </div>
                </form>
            <script src="scripts/invalide_aplication_form.js"></script>
            <?php endif; ?>
            </fieldset>
        </div>
    </div>
</main>


<?php include 'footer.html'; ?>
</body>
</html>