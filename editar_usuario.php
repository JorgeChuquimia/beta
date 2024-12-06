<?php include 'include/sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" type="image/png" href="img/ico.ico">
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
</head>
<body>
<?php include 'include/cabecera.php'; ?>
<?php include 'include/menu.php'; ?>
<?php include 'include/conexion.php'; ?>
<?php
$mensaje = "";
$mensajeClase = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];
    $nombre = $_POST['nombre'];
$nueva_foto = $_FILES["foto"]["name"]; // Nombre del nuevo archivo

// Verifica si se cargó un nuevo archivo de imagen.
if (!empty($nueva_foto)) {
    // Obtiene la imagen anterior.
    $sqlFotoAnterior = "SELECT foto FROM usuario WHERE id_usuario = ?";
    $stmtFotoAnterior = $conn->prepare($sqlFotoAnterior);
    $stmtFotoAnterior->bind_param("i", $id_usuario);
    $stmtFotoAnterior->execute();
    $stmtFotoAnterior->bind_result($fotoAnterior);
    $stmtFotoAnterior->fetch();
    $stmtFotoAnterior->close();
    // Elimina la imagen anterior si existe.
if (!empty($fotoAnterior) && file_exists($fotoAnterior)) {
    unlink($fotoAnterior);
}

    // Sube el nuevo archivo de imagen a una ubicación en el servidor.
    $directorioDestino = "img/fotos/";
    $nombreFoto = uniqid() . "_" . $nueva_foto;
    $rutaFoto = $directorioDestino . $nombreFoto;

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaFoto)) {
        // Actualiza la ruta de la imagen en la base de datos.
        $sqlActualizarFoto = "UPDATE usuario SET foto = '$rutaFoto' WHERE id_usuario = $id_usuario";
        if ($conn->query($sqlActualizarFoto) === TRUE) {
            // La actualización se realizó con éxito.
        } else {
            echo "Error al actualizar la foto: " . $conn->error;
        }
    }
}

    // Actualiza los datos del usuario en la base de datos.
    $sql = "UPDATE usuario SET usuario = ?, rol = ?, nombre = ? WHERE id_usuario = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        $mensaje = "Error en la consulta preparada: " . $conn->error;
        $mensajeClase = "alert alert-danger";
    } else {
        $stmt->bind_param("sssi", $usuario, $rol, $nombre, $id_usuario);
        if ($stmt->execute()) {
            $mensaje = "Usuario actualizado exitosamente.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al actualizar el usuario: " . $stmt->error;
            $mensajeClase = "alert alert-danger";
        }

        $stmt->close();
    }
}

// Obtiene el ID del usuario a editar desde la URL.
$id_usuario = $_GET['id_usuario'];
$sql = "SELECT * FROM usuario WHERE id_usuario = $id_usuario";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<div class="contenido main-content" align="center">
    <form method="post" class="formulario" enctype="multipart/form-data">
    <div class="container mt-6">
        <?php if (!empty($mensaje)) { ?>
            <div class="alert <?php echo $mensajeClase; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>
         <div class="row justify-content-center">
            <div class="col-md-6">
        <div class="card">
            <div class="card-header text-center">
                <h4>Editar Usuario</h4>
            </div>
            <div class="card-body">
                <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
                
                <div class="form-group">
                    <label for="usuario">Nombre de usuario:</label>
                    <input type="text" name="usuario" pattern="^[a-zA-Z0-9]+$" title="No se permiten espacios vacíos" value="<?php echo $row['usuario']; ?>" class="form-control" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                
                <div class="form-group">
                    <label for="rol">Rol de usuario:</label>
                    <select name="rol" id="rol" required class="form-control">
                        <option value="">Seleccionar rol</option>
                        <option value="Administrador" <?php if ($row['rol'] == 'Administrador') echo 'selected'; ?>>Administrador</option>
                        <option value="Profesor" <?php if ($row['rol'] == 'Profesor') echo 'selected'; ?>>Profesor</option>
                        <option value="Estudiante" <?php if ($row['rol'] == 'Estudiante') echo 'selected'; ?>>Estudiante</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" name="nombre" pattern="[A-Z ]+" title="Ingresa solo letras mayúsculas" value="<?php echo $row['nombre']; ?>" class="form-control" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                
                <div class="form-group">
                    <label for="foto">Cambiar Foto de Perfil:</label>
                    <input type="file" name="foto" id="foto" class="form-control-file">
                </div>
                
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
         </div>
    </div>
</form>

</div>
</body>
</html>
