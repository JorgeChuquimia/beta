<?php
// Conexión utilizando mysqli
$conn = new mysqli('localhost', 'root', '', 'db_irenenava');

if ($conn->connect_error) {
    die("Conexión fallida (mysqli): " . $conn->connect_error);
}

// Conexión utilizando PDO
try {
    $conexion = new PDO("mysql:host=localhost;dbname=db_irenenava", "root", "");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexión fallida (PDO): " . $e->getMessage());
}

?>
