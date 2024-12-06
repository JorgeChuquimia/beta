<?php include 'include/sesion.php'; ?>
<?php include 'include/conexion.php'; ?>
<?php
$mensaje = "";
$mensajeClase = "";

// Comprueba si se ha enviado el formulario.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los datos del formulario.
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    $nombre = $_POST['nombre'];
    $contrasena_encriptada = password_hash($password, PASSWORD_DEFAULT);

    // Verifica si el usuario ya existe.
    $sql_check = "SELECT COUNT(*) FROM usuario WHERE usuario = :usuario";
    $stmt_check = $conexion->prepare($sql_check);
    $stmt_check->bindParam(':usuario', $usuario);
    $stmt_check->execute();
    $usuarioExistente = $stmt_check->fetchColumn();

    if ($usuarioExistente > 0) {
        $mensaje = "Este nombre de usuario ya existe.";
        $mensajeClase = "alert alert-danger";
    } else {
        // Inserta los datos en la tabla "usuario".
        $sql_insert = "INSERT INTO usuario (usuario, password, nombre, rol) VALUES (:usuario, :password, :nombre, :rol)";
        $stmt_insert = $conexion->prepare($sql_insert);

        $stmt_insert->bindParam(':usuario', $usuario);
        $stmt_insert->bindParam(':password', $contrasena_encriptada);
        $stmt_insert->bindParam(':nombre', $nombre);
        $stmt_insert->bindParam(':rol', $rol);

        if ($stmt_insert->execute()) {
            $mensaje = "Usuario registrado exitosamente.";
            $mensajeClase = "alert alert-success";

            // Validación de la foto
            if ($_FILES["foto"]["error"] == UPLOAD_ERR_OK) {
                // Comprueba que la foto tenga un tamaño máximo de 2 MB.
                if ($_FILES["foto"]["size"] > 2 * 1024 * 1024) {
                    $mensaje = "Error: La foto debe tener un tamaño máximo de 2 MB.";
                    $mensajeClase = "alert alert-danger";
                } else {
                    $directorioDestino = "img/fotos/";
                    $nombreFoto = uniqid() . "_" . $_FILES["foto"]["name"];
                    $rutaFoto = $directorioDestino . $nombreFoto;

                    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $rutaFoto)) {
                        $sql_update = "UPDATE usuario SET foto = :foto WHERE usuario = :usuario";
                        $stmt_update = $conexion->prepare($sql_update);
                        $stmt_update->bindParam(':foto', $rutaFoto);
                        $stmt_update->bindParam(':usuario', $usuario);
                        $stmt_update->execute();
                    } else {
                        $mensaje = "Error al cargar la foto.";
                        $mensajeClase = "alert alert-danger";
                    }
                }
            }
        } else {
            $mensaje = "Error al registrar: " . $stmt_insert->errorInfo()[2];
            $mensajeClase = "alert alert-danger";
        }
    }

    // Cierra la conexión a la base de datos.
    $conexion = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de usuarios</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
     <link rel="stylesheet" href="css/bootstrap.min.css">
     <script src="js/bootstrap.min.js"></script>
  <link rel="icon" type="image/png" href="img/logo.png">

    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">


</head>
<body>
    
    <?php include 'include/cabecera.php'; ?>
  <?php include 'include/menu.php'; ?>
<div class="contenido main-content" align="center">
     <h3 class="display-8 fw-semibold text-uppercase text-center fw-bold fst-italic text-end">Nuevo usuario</h3>

 
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="pt-3" enctype="multipart/form-data">
    <div class="container">
        <?php if (!empty($mensaje)) { ?>
            <div class="alert <?php echo $mensajeClase; ?>">
                <?php echo $mensaje; ?>
            </div>
        <?php } ?>
        
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Registrar Usuario</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="usuario" class="form-label">Usuario:</label>
                                    <input type="text" name="usuario" pattern="^[a-zA-Z0-9]+$" oninput="this.value = this.value.toUpperCase()" title="No se permiten espacios vacíos" required class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Contraseña:</label>
                                    <input type="password" name="password" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{7,}$" title="La contraseña debe tener al menos 7 caracteres, incluyendo al menos una letra, un número y un carácter especial" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rol" class="form-label">Rol de usuario:</label>
                                    <select name="rol" id="rol" required class="form-control">
                                        <option value="">Seleccionar rol</option>
                                        <option value="Administrador">Administrador</option>
                                        <option value="Profesor">Profesor</option>
                                        <option value="Estudiante">Estudiante</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre" class="form-label">Nombre Completo:</label>
                                    <input type="text" name="nombre" id="nombre" pattern="[A-Z ]+" title="Ingresa solo letras mayúsculas" oninput="this.value = this.value.toUpperCase()" required class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="foto" class="form-label">Foto del usuario:</label>
                            <input type="file" name="foto" id="foto" class="form-control-file">
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Registrar Usuario</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


</div>
</div>
</body>
</html>


