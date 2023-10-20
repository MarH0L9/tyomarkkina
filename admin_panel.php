<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Tarkista onko user_type asetettu ja onko se admin. Jos ei, ohjaa kirjautumissivulle
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
   header("Location: /login.php");
   exit();
}

// Tarkista onko käyttäjä ollut aktiivinen viimeisen 30 minuutin aikana
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    // Viimeinen toiminta oli yli 30 minuuttia sitten
    session_unset();     // Poista kaikki sessiomuuttujat
    session_destroy();   // Poista sessio
    header("Location: /login.php");
    exit();
}
// Päivitä viimeinen aktiviteetti
$_SESSION['last_activity'] = time();

require 'config.php';
$pdo = new PDO($dsn, $username, $password);

// Hae kaikki työtarjoukset, jotka eivät ole vielä hyväksytty
$query = "SELECT * FROM jobs WHERE hyvaksytty = 0";
$stmt = $pdo->prepare($query);
$stmt->execute();
$unapprovedJobs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$query_users = "SELECT users.id, users.email, tyonantajat.yrityksen_nimi, tyonantajat.Y_tunnus 
                FROM users 
                JOIN tyonantajat ON users.ID = tyonantajat.user_id 
                WHERE users.user_type = 'yritys' AND tyonantajat.hyvaksytty = 0";
$stmt_users = $pdo->prepare($query_users);
$stmt_users->execute();
$unapprovedUsers = $stmt_users->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Paneli</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include "header.php"?>

<main>

<div class="container mt-5 ">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1>Admin Paneli</h1>
        </div><hr>
    <div class="row">
    <div class="col-md-6">
    <h3>Uusia yrityksiä jotka pitää hyväksyä:</h3><hr>
    <?php if($unapprovedUsers): ?>
        <div class="offer-list">
        <?php foreach($unapprovedUsers as $user): ?>
            
            <div class="offer-item mb-3">
                <strong>Yrityksen nimi:</strong> <?php echo $user['yrityksen_nimi']; ?><br>
                <strong>Email:</strong> <?php echo $user['email']; ?><br>
                <strong>Y-tunnus:</strong> <?php echo $user['Y_tunnus']; ?><br>
                <!-- -->
                <a href="approves/approve_yritys.php?user_id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Hyväksy</a>
                <a href="approves/reject_yritys.php?user_id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">Ei hyväksyä</a>
            </div>   
        <?php endforeach; ?>
    <?php else: ?>
        <p>Ei ole yrityksiä.</p>
    <?php endif; ?>
</div>
</div>

        
            
        <div class="col-md-6">
            <h3>Työtarjoukset jotka pitää hyvaksyä:</h3><hr>
            
            <?php if($unapprovedJobs): ?>
                <div class="offer-list">
                
                    <?php foreach($unapprovedJobs as $job): ?>
                    <div class="offer-item mb-3">
                           <h5> <?php echo $job['otsikko']; ?></h5>
                            <p> <?php echo $job['yrityksennimi']; ?></p>
                            <p> <?php echo $job['kuvaus']; ?></p>
                            
                            <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#jobModal<?php echo $job['id']; ?>">
                                Tarkista työtarjous
                            </button>
                            
                            <a href="approves/approve_jobs.php?job_id=<?php echo $job['id']; ?>" class="btn btn-success btn-sm">Hyväksy</a>
                            <a href="approves/reject_jobs.php?job_id=<?php echo $job['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">Ei hyväksyä</a>

                        </div>
                        <!-- Modal -->
                        <div class="modal fade" id="jobModal<?php echo $job['id']; ?>" tabindex="-1" aria-labelledby="jobModalLabel<?php echo $job['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="jobModalLabel<?php echo $job['id']; ?>"><strong><?php echo $job['otsikko']; ?></strong></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h6><strong>Yritys:</strong> <?php echo $job['yrityksennimi']; ?></h6>
                                        <p><strong>Kuvaus:</strong> <?php echo $job['kuvaus']; ?></p>
                                        <p><strong>Tarkka kuvaus:</strong> <?php echo $job['tarkkakuvaus']; ?></p>
                                        <p><strong>Sijainti:</strong> <?php echo $job['sijainti']; ?></p>
                                        <p><strong>Kunta:</strong> <?php echo $job['kunta']; ?></p>
                                        <p><strong>Ala:</strong> <?php echo $job['ala']; ?></p>
                                        <p><strong>Julkaistu:</strong> <?php echo $job['julkaistu']; ?></p>
                                        <p><strong>Palvelusuhde:</strong> <?php echo $job['palvelusuhde']; ?></p>
                                        <p><strong>Työkieli:</strong> <?php echo $job['tyokieli']; ?></p>
                                        <p><strong>Voimassa:</strong> <?php echo $job['voimassaolopaiva']; ?></p>
                                        <p><strong>Yrityksen linkki:</strong> <?php echo $job['yrityksenlinkki']; ?></p>
                                        <p><strong>Työtarjouksen yhteys tiedot:</strong> <?php echo $job['contact_details']; ?></p>
                                        
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sulje</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>   
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Ei ole työtarjouksia hyvaksyttäväksi.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>
</main>
<script type="text/javascript">
        function confirmDelete() {
            return confirm("Oletko varma että et halua hyväksyä käyttäjää/työtarjousta ja haluat poistaa tiedot tietokannasta?");
        }
     
    </script>
<?php include 'footer.html'; ?>

</body>
</html>
