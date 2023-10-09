<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = getenv('DB_SERVER');
$port = getenv('DB_PORT');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');

// Verifica si alguna de las variables de entorno está vacía
if (empty($server) || empty($port) || empty($username) || empty($password) || empty($database)) {
    die("La configuración de la base de datos no está completa.");
}

// Intenta conectar a la base de datos de Azure
try {
    $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Configurar el manejo de errores de PDO si es necesario
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 // Configurar la codificación de caracteres a utf8mb4
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    // Manejo de errores
    echo "Error de conexión a Azure: " . $e->getMessage();
}
?>
