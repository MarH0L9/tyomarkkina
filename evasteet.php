<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
} ?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">    
    <script src="https://kit.fontawesome.com/07bb6b2702.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
    <title>Sinun Työpaikkaportaali</title>
    <?php include 'config.php'; ?>
</head>
<body>
<main>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php"><img src="resources/images/logo/logo4.png" style="width:70%;"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item d-block "> <!-- Muestra el icono de "briefcase" en dispositivos pequeños -->
                    <a class="nav-link" href="avoinmet_tyopaikat.php"><i class="fa-solid fa-briefcase d-lg-none"></i> Työpaikat</a> <!-- Oculta el icono de "briefcase" en dispositivos grandes -->
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="register.php">Rekisteröidy</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Työhakuvinkit</a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <?php if ($_SESSION['user_type'] == 'normal'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="oma_profiili.php">Oma profiili</a>
                    </li>
                <?php elseif ($_SESSION['user_type'] == 'yritys'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="yrityksen_profiili.php">Yrityksen profiili</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="luo_ilmotus.php">Luo ilmotus</a>
                    </li>
                <?php endif; ?>
            <?php endif; ?>
                        </ul>
        </div>
        <div class="ml-2">
    <?php if (!isset($_SESSION['user_id'])): ?>
        <!-- Si el usuario NO ha iniciado sesión -->
        <a href="login.php" class="btn btn-primary rounded-pill custom-primary-button-log"><i class="fa-solid fa-right-to-bracket fa-lg"></i> Kirjaudu sisään</a>
    <?php else: ?>
        <!-- Si el usuario ya ha iniciado sesión -->
        <a href="logout.php" class="btn btn-danger rounded-pill custom-primary-button-out">Kirjaudu Ulos</a>
    <?php endif; ?>
</div>
</div>   
</nav>
</main>
</body>
</html>
