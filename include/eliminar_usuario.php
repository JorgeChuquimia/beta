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

// Verificar si el usuario tiene registros relacionados en otras tablas
$sql_check_related = $conn->prepare("
    SELECT 1 FROM profesor WHERE id_usuario = ? 
    UNION 
    SELECT 1 FROM estudiante WHERE id_usuario = ?
");
$sql_check_related->bind_param("ii", $id_usuario, $id_usuario);
$sql_check_related->execute();
$result_check = $sql_check_related->get_result();

if ($result_check->num_rows > 0) {
    // Mostrar alerta y redirigir si el usuario tiene datos relacionados
    echo '<script>
        alert("Este usuario tiene datos dentro del sistema. No se puede eliminar."); 
        window.location.href = "../usuarios_prof.php";
    </script>';
} else {
    // Intentar eliminar el usuario si no hay datos relacionados
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
        // Muestra un mensaje de error en la misma página si falla la eliminación
        echo "Error al eliminar el usuario: " . $conn->error;
    }
}

// Cerrar conexiones
$sql_role->close();
$sql_check_related->close();
$conn->close();
?>
