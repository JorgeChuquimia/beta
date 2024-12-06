<?php 
include 'sesion.php'; 
include 'conexion.php'; 

$id_cal = $_GET['id_cal'];

// Primero, obtenemos el id_usuario de la calificación que se va a eliminar
$sql = "SELECT id_usuario FROM calificaciones WHERE id_cal = $id_cal";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Si la calificación existe, obtenemos el id_usuario
    $row = $result->fetch_assoc();
    $id_usuario = $row['id_usuario'];

    // Ahora eliminamos la calificación
    $delete_sql = "DELETE FROM calificaciones WHERE id_cal = $id_cal";
    if ($conn->query($delete_sql) === TRUE) {
        // Redirigimos a calificaciones_est.php con el id_usuario como parámetro GET
        header("Location: ../calificaciones_est.php?id_usuario=$id_usuario");
        exit; // Aseguramos que no se ejecute ningún otro código después de la redirección
    } else {
        echo "Error al eliminar: " . $conn->error;
    }
} else {
    echo "No se encontró la calificación.";
}
?>
