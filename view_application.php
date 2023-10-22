<?php
require 'config.php';

$applicationId = $_GET['application_id'];

$pdo = new PDO($dsn, $username, $password);
$query = "SELECT * FROM applications WHERE id = :application_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':application_id', $applicationId);
$stmt->execute();

$application = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Aplicación</title>
    <!-- Aquí puedes agregar tus hojas de estilo y scripts adicionales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
    <div class="container mt-5">
        <div class="mb-6">
        <h2>Hakemuksen tiedot</h2>
        <table class="table table-bordered">
            <tr>
                <th>Nimi</th>
                <td><?php echo $application['etunimi'] . ' ' . $application['sukunimi']; ?></td>
            </tr>
            <tr>
                <th>Sähköposti</th>
                <td><?php echo $application['email']; ?></td>
            </tr>
            <tr>
                <th>Puhelin</th>
                <td><?php echo $application['puhelin']; ?></td>
            </tr>
            <tr>
                <th>LinkedIn</th>
                <td><a href="<?php echo $application['linkedin']; ?>" target="_blank"><?php echo $application['linkedin']; ?></a></td>
            </tr>
            <tr>
                <th>Opinnot</th>
                <td><?php echo $application['opinnot']; ?></td>
            </tr>
            <tr>
                <th>Viimeinen työpaikka</th>
                <td><?php echo $application['viimeinen_tyo']; ?></td>
            </tr>
            <tr>
                <th>Työkokemus</th>
                <td><?php echo $application['kokemus']; ?></td>
            </tr>
            <tr>
                <th>Voin aloitta työt</th>
                <td><?php echo $application['aloitus']; ?></td>
            </tr>
            <tr>
                <th>Miksi haluat työpaikan</th>
                <td><?php echo $application['miksi']; ?></td>
            </tr>
            <tr>
                <th>CV</th>
                <td><a href="<?php echo $application['cv_path']; ?>" target="_blank" class="btn btn-info">Katso CV</a></td>
            </tr>
        </table>
    </div>
    </div>
    <?php include 'footer.html'; ?>
</body>
</html>
