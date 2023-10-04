<?php

// Define una variable para determinar si la conexión debe ser local o a Azure
$conexionLocal = false;

// Verifica si la conexión debe ser local (por ejemplo, si estás en un entorno de desarrollo)
if ($conexionLocal) {
    // Incluye el archivo de configuración local
    include 'C:\xampp\htdocs\Projects\tunnukset.php';

    // Utiliza las credenciales locales para la conexión a la base de datos
    $server = $local_credentials["server"];
    $username = $local_credentials["username"];
    $password = $local_credentials["password"];
    $database = $local_credentials["database"];
} else {
    // Intenta conectar a la base de datos de Azure
// Intenta conectar a la base de datos de Azure
        $server = getenv('DB_SERVER');
        $port = getenv('DB_PORT');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_DATABASE'); 
// Verifica la conexión
if ($conn->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
}

echo "Conexión a la base de datos exitosa.";
$conn->close();
?>
