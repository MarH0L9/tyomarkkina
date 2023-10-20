<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
/* require 'vendor/autoload.php';
$config = require 'C:\xampp\htdocs\Projects\mail_config.php';
$config = require 'mail_config.php'; */

require 'config.php';

$pdo = new PDO($dsn, $username, $password);

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verificar si el correo electrónico existe en la base de datos
    $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
    $stmt = $pdo->prepare($checkEmailQuery);
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        // Generar un token de restablecimiento de contraseña único
        $resetToken = bin2hex(random_bytes(50));

        // Actualizar el token en la base de datos
        $updateTokenQuery = "UPDATE users SET reset_token = ? WHERE email = ?";
        $stmt = $pdo->prepare($updateTokenQuery);
        if ($stmt->execute([$resetToken, $email])) {
            // Enviar un correo electrónico al usuario con un enlace para restablecer la contraseña
            $resetLink = "http://127.0.0.1/projects/tyomarkkina_test2/reset.php?token=" . $resetToken;
               // Configuración de PHPMailer
       
       
       /*
       $mail = new PHPMailer(true); 

        try {
            // Configuración del servidor
            $mail->isSMTP(); 
            $mail->Host = 'smtp.tu-servidor.com'; 
            $mail->SMTPAuth = true; 
            $mail->Username = 'tu-correo@dominio.com'; 
            $mail->Password = 'tu-contraseña'; 
            $mail->SMTPSecure = 'tls'; 
            $mail->Port = 587;

            // Destinatarios
            $mail->setFrom('tu-correo@dominio.com', 'Nombre del remitente');
            $mail->addAddress($email); 

            // Contenido del correo
            $mail->isHTML(true);
            $mail->Subject = 'Restablecimiento de contraseña';
            $mail->Body = "Haz clic en el siguiente enlace para restablecer tu contraseña: <a href='{$resetLink}'>Restablecer contraseña</a>";

            $mail->send();
        } catch (Exception $e) {
            // 
            error_log("El mensaje no pudo ser enviado. Error de Mailer: {$mail->ErrorInfo}");
                }*/
            }
            
        }
        // 
        $message = '<div class="alert alert-success" role="alert">Jos tähän sähköpostiosoitteeseen liittyy tili, lähetetään viesti, jossa kerrotaan, miten kirjautumissalasana palautetaan.</div>';
        
    } 
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>forgot</title>
    <link rel="stylesheet" href="css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include 'header.php'; ?>
</head>
<body>
    <main>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
        <h2>Unohditko salasanan?</h2>
        <?php echo $message; ?>
        <form action="forgot.php" method="POST">            
            <div class="mb-5">
                <label for="email" class="form-label">Sähköpostiosotie</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="text-center">
            <button type="submit" class="btn btn-primary"> Läheta</button>
            </div>
        </form>
    </div>
    </div>
    </div>
    </div>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>
