<?php include 'sesion.php'; ?>
<?php include 'conexion.php'; ?>
<?php

$id_ins = $_GET['id_ins'];

$sql = "DELETE FROM inscripciones WHERE id_ins=$id_ins";

if ($conn->query($sql) === TRUE) {
    header("Location: ../adm_ins.php"); 
} else {
    echo "Error al eliminar el registro: " . $conn->error;
}
?>
