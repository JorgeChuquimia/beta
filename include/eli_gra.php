<?php 
include 'sesion.php'; 
include 'conexion.php'; 

$id_gra = $_GET['id_gra'];

// Verifica si el grado tiene materias asociadas
$sql_check = $conn->prepare("SELECT COUNT(*) FROM materia WHERE id_gra = ?");
$sql_check->bind_param("i", $id_gra);
$sql_check->execute();
$result_check = $sql_check->get_result();
$count = $result_check->fetch_row()[0];

if ($count > 0) {
    // Muestra una alerta con un botón de aceptar que redirige
    echo '<script>
        alert("Este grado tiene una materia asociada. No se puede eliminar.");
        window.location.href = "../grados.php";
    </script>';
    exit();
}

// Elimina el grado si no tiene materias asociadas
$sql_delete = $conn->prepare("DELETE FROM grado WHERE id_gra = ?");
$sql_delete->bind_param("i", $id_gra);

if ($sql_delete->execute()) {
    // Redirige a la página de grados con un mensaje de éxito
    header("Location: ../grados.php");
} else {
    // Muestra un mensaje de error en la misma página
    echo "Error al eliminar el grado: " . $conn->error;
}

$sql_check->close();
$sql_delete->close();
$conn->close();
?>
