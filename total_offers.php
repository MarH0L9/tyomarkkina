<?php
// Requerir tu archivo de configuración de base de datos (config.php)
require 'config.php';

// Establecer la conexión a la base de datos
$pdo = new PDO($dsn, $username, $password);

// Consulta SQL para obtener el número total de ofertas disponibles (debe coincidir con tu estructura de base de datos)
$sql = "SELECT COUNT(*) AS total FROM jobs WHERE hyvaksytty = 1"; // Ajusta la consulta según tu estructura

// Preparar y ejecutar la consulta
$stmt = $pdo->prepare($sql);
$stmt->execute();

// Obtener el resultado de la consulta
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Devolver el número total de ofertas como JSON
header('Content-Type: application/json');
echo json_encode($result['total']);
?>