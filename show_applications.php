<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    } 

require 'config.php';

  if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'yritys') {
        echo '<div class="alert alert-danger" role="alert">Pääsy kielletty. Vain "yritys"-käyttäjät voivat katsoa tätä sivua.</div>';
        exit;
    }
$jobId = $_GET['job_id'];

$pdo = new PDO($dsn, $username, $password);
$query = "SELECT * FROM applications WHERE job_id = :job_id"; // Suponiendo que tu tabla se llama 'applications'
$stmt = $pdo->prepare($query);
$stmt->bindParam(':job_id', $jobId);
$stmt->execute();

$applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html lang="fi">
<head>
    <title>Työtarjouksen tiedot</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="resources/images/logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css"> <!-- Agrega tus estilos CSS personalizados aquí si es necesario -->
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <h2>Hakemukset lähetetty Job ID: <?php echo $jobId; ?></h2>
          
        
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">Etunimi</th>
                    <th scope="col">Sukunimi</th>
                    <th scope="col">Toiminta</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $application) : ?>
                    <tr>
                        <td><?php echo $application['etunimi']; ?></td>
                        <td><?php echo $application['sukunimi']; ?></td>
                        <td>
                            <a href="view_application.php?application_id=<?php echo $application['id']; ?>" target="_blank" class="btn btn-info btn-md">
                                Tarkista
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>    

    </div>
</div>
    </div>
    </div>
    <?php include 'footer.html'; ?>
</body>
</html>
