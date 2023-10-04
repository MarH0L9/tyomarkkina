<?php
    // Intenta conectar a la base de datos de Azure
// Intenta conectar a la base de datos de Azure
        $server = getenv('DB_SERVER');
        $port = getenv('DB_PORT');
        $username = getenv('DB_USERNAME');
        $password = getenv('DB_PASSWORD');
        $database = getenv('DB_DATABASE'); 
// Intenta la conexión a la base de datos
$conn = new mysqli($servername, $username, $password, database);
// Verifica la conexión
if ($conn->connect_error) {
    die("La conexión a la base de datos ha fallado: " . $conn->connect_error);
}

echo "Conexión a la base de datos exitosa.";
$conn->close();
?>
