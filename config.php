<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$server = getenv('DB_SERVER');
$port = getenv('DB_PORT');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');
$database = getenv('DB_DATABASE');


if (empty($server) || empty($port) || empty($username) || empty($password) || empty($database)) {
    die("Database connection is not working.");
}

//Omnian Azuren yhteys
try {
    $dsn = "mysql:host=$server;port=$port;dbname=$database;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);

    // Errors
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $pdo->exec("set names utf8mb4");
} catch (PDOException $e) {
    echo "Error de conexión a Azure: " . $e->getMessage();
}
?>
