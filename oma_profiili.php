<?php
 if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirige al usuario al formulario de inicio de sesión si no ha iniciado sesión
    exit();
}
require 'config.php';
include 'functions/functions.php'; 
$pdo = new PDO($dsn, $username, $password);
$userId = $_SESSION['user_id'];

$query = "SELECT * FROM user_profile WHERE user_id = :user_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':user_id', $userId);
$stmt->execute();

$profile = $stmt->fetch(PDO::FETCH_ASSOC);
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
    <?php displaySessionMessage(); ?>
    <body>
        <main>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
        <h2><i class="fa-regular fa-user fa-lm"></i> Profiili</h2><hr>
            <form action="oma_profiili_update.php" method="POST" class="mt-4">
                <h3>Perustiedot</h3><br>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="etunimi" class="form-label">Etunimi</label>
                        <input type="text" class="form-control" id="etunimi" name="etunimi" value="<?php echo $profile ? $profile['etunimi'] : ''; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sukunimi" class="form-label">Sukunimi</label>
                        <input type="text" class="form-control" id="sukunimi" name="sukunimi" value="<?php echo $profile ? $profile['sukunimi'] : ''; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="Email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="Email" name="Email" value="<?php echo $profile ? $profile['Email'] : ''; ?>" readonly>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="puhelin" class="form-label">Puhelin</label>
                        <input type="text" class="form-control" id="puhelin" name="puhelin" value="<?php echo $profile ? $profile['puhelin'] : ''; ?>">
                    </div>

                    <div class="text-end">
                        <a href="reset_password.php" class="btn btn-primary btn-sm active " role="button" aria-pressed="true" ><i class="fa-regular fa-pen-to-square fa-lg"></i></i> Vaihda Salasana</a>
                    </div>
                </div>
                <br>
                <hr>
                <h2>Työnhakijan tiedot</h2><br>

                <div class="row">
                    <div class="col-md-6 mb-3">
                    <label for="viimeisin_tyonantaja" class="form-label">Viimeisin Tyonantaja</label>
                    <input type="text" class="form-control" id="viimeisin_tyonantaja" name="viimeisin_tyonantaja" value="<?php echo $profile ? $profile['viimeisin_tyonantaja'] : ''; ?>">
                    </div>

                    <div class="col-md-6 mb-3">
                    <label for="viimeisin_tyotehtava" class="form-label">Viimeisin Tyotehtava</label>
                    <input type="text" class="form-control" id="viimeisin_tyotehtava" name="viimeisin_tyotehtava" value="<?php echo $profile ? $profile['viimeisin_tyotehtava'] : ''; ?>">
                    </div>
                </div>   
                <div class="mb-3">
                    <label for="aikaisempi_kokemus" class="form-label">Aikaisempi Kokemus</label>
                    <select class="form-select" id="aikaisempi_kokemus" name="aikaisempi_kokemus">
                        <option value="-----" <?php echo ($profile && $profile['aikaisempi_kokemus'] == "-----") ? "selected" : ""; ?>>-----</option>
                        <option value="0-1 vuotta" <?php echo ($profile && $profile['aikaisempi_kokemus'] == "0-1 vuotta") ? "selected" : ""; ?>>0-1 vuotta</option>
                        <option value="1-3 vuotta" <?php echo ($profile && $profile['aikaisempi_kokemus'] == "1-3 vuotta") ? "selected" : ""; ?>>1-3 vuotta</option>
                        <option value="3-6 vuotta" <?php echo ($profile && $profile['aikaisempi_kokemus'] == "3-6 vuotta") ? "selected" : ""; ?>>3-6 vuotta</option>
                        <option value="+6 vuotta" <?php echo ($profile && $profile['aikaisempi_kokemus'] == "+6 vuotta") ? "selected" : ""; ?>>+6 vuotta</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="koulutustaso" class="form-label">Koulutustaso</label>
                    <input type="text" class="form-control" id="koulutustaso" name="koulutustaso" value="<?php echo $profile ? $profile['koulutustaso'] : ''; ?>"> 
                </div>

                <div class="mb-3">
                    <label for="linkedin_url" class="form-label">Linkedin URL</label>
                    <input type="text" class="form-control" id="linkedin_url" name="linkedin_url" value="<?php echo $profile ? $profile['linkedin_url'] : ''; ?>"> 
                </div>                          
                <div class="mb-3">
                <div class="text-center">
                <button type="submit" class="btn btn-primary custom-save">
                    <i class="fa-regular fa-floppy-disk"></i> Tallenna
                </button>
            </div>
            </div>
            </form>
        </div>
    </div>
</div>
</main>
<?php include 'footer.html'; ?>
     </body>
     
</html>
