<?php

// Define una variable para determinar si la conexión debe ser local o a Azure
$conexionLocal = true;

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
    try {
        $server = getenv('DB_SERVER');
        $port = getenv('DB_PORT');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_DATABASE'); 

        $dsn = "mysql:host=$server;port=$port;dbname=$database";
        $pdo = new PDO($dsn, $username, $password);
        // Configurar el manejo de errores de PDO si es necesario
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Registrar el error en un archivo
        file_put_contents("errors.txt", "Error de conexión a Azure: " . $e->getMessage() . "\n", FILE_APPEND);
        
        // Si la conexión a Azure falla, muestra un mensaje personalizado de error
        die("Ha ocurrido un error en la conexión a la base de datos. Por favor, intenta de nuevo más tarde.");
    }
}

?>