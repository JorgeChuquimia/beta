<?php include 'sesion.php'; ?>
<?php include 'conexion.php'; ?>
<?php

$id_mat = $_GET['id_mat'];

$sql = "DELETE FROM materia WHERE id_mat=$id_mat";

if ($conn->query($sql) === TRUE) {
    header("Location: ../mat_cur.php"); 
} else {
    echo "Error al eliminar la materia: " . $conn->error;
}
?>
