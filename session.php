<?php

ini_set('session.gc_maxlifetime', 1800); // Duración de la sesión en el servidor
session_set_cookie_params(1800); // Duración de la cookie de sesión en el cliente

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 1800) {
    // Última actividad fue hace más de 30 minutos
    session_unset();     // elimina las variables de sesión
    session_destroy();   // destruye los datos de la sesión
}
$_SESSION['last_activity'] = time(); // actualiza la última actividad

?>