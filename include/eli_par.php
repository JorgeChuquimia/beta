<?php include 'sesion.php'; ?>
<?php include 'conexion.php'; ?>
<?php

$id_par = $_GET['id_par'];

$sql = "DELETE FROM paralelo WHERE id_par=$id_par";

if ($conn->query($sql) === TRUE) {
    header("Location: ../paralelos.php"); 
} else {
    echo "Error al eliminar el paralelo: " . $conn->error;
}
?>
