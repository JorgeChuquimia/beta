<?php include 'include/conexion.php'; ?>
<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: index.php"); // Redirige al formulario de inicio de sesión
    exit();
}
?>
<?php
$mensaje = "";
$mensajeClase = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nueva_contrasena = $_POST['nueva_contrasena'];

    // Verificar si se proporcionó una nueva contraseña
    if (!empty($nueva_contrasena)) {
        $nueva_contrasena_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET password=? WHERE id_usuario=?";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            echo "Error en la consulta preparada: " . $conn->error;
        } else {
            $stmt->bind_param("si", $nueva_contrasena_hash, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
    $mensajeClase = "alert alert-success";
     $mensaje = "Contraseña actualizada exitosamente";
   
            } else {
    $mensajeClase = "alert alert-danger";
   $mensaje = "Error al actualizar.";
 
            }

            $stmt->close();
        }
    }
}

$id = $_GET['id_usuario'];
$sql = "SELECT * FROM usuario WHERE id_usuario=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Contraseña</title>
     <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
         <link rel="stylesheet" href="css/bootstrap.min.css">
     <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="assets/vendors/simple-line-icons/css/simple-line-icons.css">
    <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
    <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
<?php include 'include/menu.php'; ?>
<div class="contenido main-content">
    <h3>Actualizar Contraseña</h3>
            <tr>
            <th>
            <?php if (!empty($mensaje)) { ?>
            <div class="<?php echo $mensajeClase; ?>">
            <?php echo $mensaje; ?>
            </div>
            <?php } ?>
            </th>
            </tr>
    <form method="post" class="formulario">

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <label for="nueva_contrasena">Nueva Contraseña:</label>
        <input type="text" name="nueva_contrasena" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{7,}$" title="La contraseña debe tener al menos 7 caracteres, incluyendo al menos una letra, un número y un carácter especial"><br>
        <button type="submit" class="boton">Guardar</button>
    </form>
</div>
</body>
</html>
