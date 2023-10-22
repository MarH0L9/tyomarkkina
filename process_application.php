<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include 'config.php';
    
    $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Carga del archivo CV
    $cvPath = "";
    if (isset($_FILES['cv']) && $_FILES['cv']['error'] == 0) {
        $extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
        $allowedExtensions = ['pdf', 'doc', 'docx'];

        if (in_array($extension, $allowedExtensions)) {
            // Uusi nimi CV:lle - esim: etunimi.sukunimi-20210914120000.pdf
            $filename = $_POST['etunimi'] . "." . $_POST['sukunimi'] . "-" . date('YmdHis') . "." . $extension;
            $destination = "resources/uploads/cv/" . $filename;

            if (move_uploaded_file($_FILES['cv']['tmp_name'], $destination)) {
                $cvPath = $destination;
            } else {
                die("Virhe CV:n lataamisessa.");
            }
        } else {
            die("CV:n sallitut tiedostotyypit ovat pdf, doc ja docx.");
        }
    } else {
        die("Virhe CV:n lataamisessa.");
    }

    $stmt = $pdo->prepare("INSERT INTO applications (job_id, etunimi, sukunimi, email, puhelin, linkedin, opinnot, viimeinen_tyo, kokemus, aloitus, miksi, cv_path, application_date, applicant_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");

    $stmt->execute([
        $_POST['job_id'], 
        $_POST['etunimi'], 
        $_POST['sukunimi'], 
        $_POST['email'], 
        $_POST['puhelin'], 
        $_POST['linkedin'], 
        $_POST['opinnot'], 
        $_POST['viimeinen_tyo'], 
        $_POST['kokemus'], 
        $_POST['aloitus'], 
        $_POST['miksi'],
        $cvPath,
        $_SESSION['user_id']  // Suponiendo que el ID del usuario está en $_SESSION['user_id']
    ]);

    // Mostramos el mensaje y el botón
    echo '<div id="successMessage" style="border: 2px solid #4CAF50; padding: 20px; margin: 20px 0; background-color: #f4f4f4; text-align: center;">
            <h3>Hakemus on lähetetty!</h3>
            <p>Sinut viedään etusivulle...</p>
            <button onclick="redirectToHomepage()">Ok</button>
          </div>';

    echo '<script>
        function redirectToHomepage() {
            location.href = "index.php";
        }
        setTimeout(redirectToHomepage, 3000);
    </script>';

} else {
    die("Acceso no permitido.");
}
?>