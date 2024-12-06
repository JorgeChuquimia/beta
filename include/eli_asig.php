<?php include 'sesion.php'; ?>
<?php include 'conexion.php'; ?>
<?php

$id_asi = $_GET['id_asi'];

$sql = "DELETE FROM asignar WHERE id_asi=$id_asi";

if ($conn->query($sql) === TRUE) {
    header("Location: ../asignar.php"); 
} else {
    echo "Error al eliminar el registro: " . $conn->error;
}
?>
