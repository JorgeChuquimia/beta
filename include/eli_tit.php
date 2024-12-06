<?php 
include 'sesion.php'; 
include 'conexion.php'; 

$id_usuario = $_GET['id_usuario'];

// Verifica el rol del usuario
$sql_role = $conn->prepare("SELECT rol FROM usuario WHERE id_usuario = ?");
$sql_role->bind_param("i", $id_usuario);
$sql_role->execute();
$result_role = $sql_role->get_result();
$user_role = $result_role->fetch_assoc()['rol'];

// Elimina el usuario
$sql_delete = $conn->prepare("DELETE FROM usuario WHERE id_usuario = ?");
$sql_delete->bind_param("i", $id_usuario);

if ($sql_delete->execute()) {
    // Redirige según el rol del usuario
    if ($user_role === 'Administrador') {
        header("Location: ../usuarios.php");
    } elseif ($user_role === 'Estudiante') {
        header("Location: ../usuarios_estudiantes.php");
    } elseif ($user_role === 'Profesor') {
        header("Location: ../usuarios_prof.php");
    } else {
        echo "Rol de usuario desconocido.";
    }
} else {
    // Muestra un mensaje de error en la misma página
    echo "Error al eliminar el usuario: " . $conn->error;
}

$sql_role->close();
$sql_delete->close();
$conn->close();
?>
<?php include 'sesion.php'; ?>
<?php include 'conexion.php'; ?>
<?php

$id_tit = $_GET['id_tit'];

$sql = "DELETE FROM titulos WHERE id_tit=$id_tit";

if ($conn->query($sql) === TRUE) {
    header("Location: ../per_prof.php"); 
} else {
    echo "Error al eliminar el titulo: " . $conn->error;
}
?>
