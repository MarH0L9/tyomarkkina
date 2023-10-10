<?php
session_start();
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);  // elimina la variable user_id de la sesión
}
session_destroy();  // destruye la sesión
header('Location: index.php');  // redirige al usuario de vuelta a la página principal
exit();
?>
