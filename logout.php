<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);  // elimina la variable user_id de la sesión
}
session_destroy();  // destruye la sesión
header('Location: index.php?session_closed=true');  // redirige con un parámetro en la URL
exit();
?>
