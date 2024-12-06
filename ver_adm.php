<?php include 'include/sesion.php'; ?>
<?php
// Incluir el archivo de configuración de la base de datos
include 'include/conexion.php';

// Asignar la variable de sesión a otra variable
 $id_user = $_GET['id_usuario'];

// Inicializar variables
$mensaje = "";
$mensajeClase = "";

// Inicializar variables para las declaraciones preparadas
$stmt_check = null;
$stmt_update = null;
$stmt_insert = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $nombre_completo = $_POST['nombre_completo'];
    $ci = $_POST['ci'];
    $lugar_exp = $_POST['lugar_exp'];
    $zona = $_POST['zona'];
    $calle = $_POST['calle'];
    $nro_casa = $_POST['nro_casa'];
    $cel = $_POST['cel'];
    $genero = $_POST['genero'];
    $profesion = $_POST['profesion'];
    $categoria = $_POST['categoria'];
    $antiguedad = $_POST['antiguedad'];
    $fecha_nac = $_POST['fecha_nac'];

    // Verificar si el usuario ya existe en la base de datos
    $sql_check = "SELECT id_usuario FROM administrador WHERE id_usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_usuario);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Si el usuario existe, realizar un UPDATE
        $sql_update = "UPDATE administrador SET nombre = ?, ci = ?, exp = ?, zona = ?, calle = ?, casa = ?, celular = ?, genero = ?, profesion = ?, categoria = ?, antiguedad = ?, fecha_nac = ? WHERE id_usuario = ?";
$stmt_update = $conn->prepare($sql_update);
$stmt_update->bind_param("ssssssssssssi", $nombre_completo, $ci, $lugar_exp, $zona, $calle, $nro_casa, $cel, $genero, $profesion, $categoria, $antiguedad, $fecha_nac, $id_usuario);


        if ($stmt_update->execute()) {
            $mensaje = "Datos actualizados exitosamente.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al actualizar los datos: " . htmlspecialchars($stmt_update->error);
            $mensajeClase = "alert alert-danger";
        }
    } else {
        // Si el usuario no existe, realizar un INSERT
        $sql_insert = "INSERT INTO administrador (id_usuario, nombre, ci, exp, zona, calle, casa, celular, genero, profesion, categoria, antiguedad, fecha_nac) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("issssssssssis", $id_usuario, $nombre_completo, $ci, $lugar_exp, $zona, $calle, $nro_casa, $cel, $genero, $profesion, $categoria, $antiguedad, $fecha_nac);


        if ($stmt_insert->execute()) {
            $mensaje = "Datos insertados exitosamente.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al insertar los datos: " . htmlspecialchars($stmt_insert->error);
            $mensajeClase = "alert alert-danger";
        }
    }

    // Cerrar las consultas preparadas si están definidas
    if ($stmt_check) $stmt_check->close();
    if ($stmt_update) $stmt_update->close();
    if ($stmt_insert) $stmt_insert->close();
}

function obtenerDatosAdministrador($conn, $id_user) {
    $sql_select = "SELECT * FROM administrador WHERE id_usuario = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_user);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    
    if ($result->num_rows > 0) {
        $datos = $result->fetch_assoc();
        
        // Extraer valores a variables separadas
        $nombre = $datos['nombre'];
        $ci = $datos['ci'];
        $exp = $datos['exp'];
        $zona = $datos['zona'];
        $calle = $datos['calle'];
        $casa = $datos['casa'];
        $celular = $datos['celular'];
        $genero = $datos['genero'];
        $profesion = $datos['profesion'];
        $categoria = $datos['categoria'];
        $antiguedad = $datos['antiguedad'];
        $fecha_nac = $datos['fecha_nac'];
        
        // Devolver las variables en un array asociativo
        return array(
            'nombre' => $nombre,
            'ci' => $ci,
            'exp' => $exp,
            'zona' => $zona,
            'calle' => $calle,
            'casa' => $casa,
            'celular' => $celular,
            'genero' => $genero,
            'profesion' => $profesion,
            'categoria' => $categoria,
            'antiguedad' => $antiguedad,
            'fecha_nac' => $fecha_nac
        );
    } else {
        // Devolver valores vacíos si no hay datos
        return array(
            'nombre' => '',
            'ci' => '',
            'exp' => '',
            'zona' => '',
            'calle' => '',
            'casa' => '',
            'celular' => '',
            'genero' => '',
            'profesion' => '',
            'categoria' => '',
            'antiguedad' => '',
            'fecha_nac' => ''
        );
    }
}

// Obtener los datos del administrador
$usuario = obtenerDatosAdministrador($conn, $id_user);
$nombre_completo = htmlspecialchars($usuario['nombre']);
$ci = htmlspecialchars($usuario['ci']);
$lugar_exp = htmlspecialchars($usuario['exp']);
$zona = htmlspecialchars($usuario['zona']);
$calle = htmlspecialchars($usuario['calle']);
$nro_casa = htmlspecialchars($usuario['casa']);
$celular = htmlspecialchars($usuario['celular']);
$genero = htmlspecialchars($usuario['genero']);
$profesion = htmlspecialchars($usuario['profesion']);
$categoria = htmlspecialchars($usuario['categoria']);
$antiguedad = htmlspecialchars($usuario['antiguedad']);
$fecha_nac = htmlspecialchars($usuario['fecha_nac']);

// Función para obtener el nombre completo del usuario según el id_user
function obtenerNombreCompleto($id_user, $conn) {
    // Consulta para obtener el nombre completo del usuario
    $sql = "SELECT nombre FROM usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        return $row['nombre'];
    } else {
        return ''; // Retorna una cadena vacía si no se encuentra el usuario
    }
}
// Llamar a la función para obtener el nombre completo
$nombre = obtenerNombreCompleto($id_user, $conn);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
 
<?php include 'include/menu.php'; ?>
    <div class="contenido main-content" align="center">
         
        <h4 class="text-center">Perfil del administrador</h4>
            <?php if ($mensaje): ?>
                <div class="<?php echo $mensajeClase; ?>" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
   <form action="" method="POST" enctype="multipart/form-data">
    <!-- ID del Usuario -->
    <input type="text" class="form-control" id="id_usuario" name="id_usuario" value="<?php echo htmlspecialchars($id_user); ?>" hidden>

        <div class="row mb-3">
                <!-- Nombre completo (ocupa más espacio) -->
                <div class="col-md-6">
                    <label for="nombre_completo" class="form-label">Nombre Completo:</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo"
                        value="<?php echo htmlspecialchars($nombre); ?>" oninput="this.value = this.value.toUpperCase()" readonly required>
                </div>

        <!-- CI (más pequeño) -->
        <div class="col-md-3">
            <label for="ci" class="form-label">CI:</label>
            <input type="text" class="form-control" id="ci" name="ci" 
                value="<?php echo $ci; ?>" pattern="\d{1,8}" title="Solo se permiten números"  required>
        </div>

        <!-- Lugar de expedición (más pequeño) -->
        <div class="col-md-3">
            <label for="lugar_exp" class="form-label">Expedido:</label>
            <select class="form-select w-100" id="lugar_exp" name="lugar_exp" required>
                <option value="" selected disabled>Selecciona</option>
                <option value="LPZ" <?php echo ($lugar_exp == 'LPZ') ? 'selected' : ''; ?>>LPZ</option>
                <option value="CBB" <?php echo ($lugar_exp == 'CBB') ? 'selected' : ''; ?>>CBB</option>
                <option value="SCZ" <?php echo ($lugar_exp == 'SCZ') ? 'selected' : ''; ?>>SCZ</option>
                <option value="ORU" <?php echo ($lugar_exp == 'ORU') ? 'selected' : ''; ?>>ORU</option>
                <option value="PTS" <?php echo ($lugar_exp == 'PTS') ? 'selected' : ''; ?>>PTS</option>
                <option value="TJA" <?php echo ($lugar_exp == 'TJA') ? 'selected' : ''; ?>>TJA</option>
                <option value="CHQ" <?php echo ($lugar_exp == 'CHQ') ? 'selected' : ''; ?>>CHQ</option>
                <option value="BNI" <?php echo ($lugar_exp == 'BNI') ? 'selected' : ''; ?>>BNI</option>
                <option value="PND" <?php echo ($lugar_exp == 'PND') ? 'selected' : ''; ?>>PND</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Zona -->
        <div class="col-md-4">
            <label for="zona" class="form-label">Zona:</label>
            <input type="text" class="form-control" id="zona" name="zona" 
                value="<?php echo $zona; ?>" oninput="this.value = this.value.toUpperCase()" required>
        </div>

        <!-- Calle -->
        <div class="col-md-4">
            <label for="calle" class="form-label">Calle:</label>
            <input type="text" class="form-control" id="calle" name="calle" 
                value="<?php echo $calle; ?>" oninput="this.value = this.value.toUpperCase()" required>
        </div>

        <!-- Número de casa -->
        <div class="col-md-4">
            <label for="nro_casa" class="form-label">Número de Casa:</label>
            <input type="number" class="form-control" id="nro_casa" name="nro_casa" 
                value="<?php echo $nro_casa; ?>" required>
        </div>
    </div>

    <div class="row mb-3">
        <!-- Celular -->
        <div class="col-md-4">
            <label for="cel" class="form-label">Celular:</label>
            <input type="text" class="form-control" id="cel" name="cel" 
                value="<?php echo $celular; ?>" pattern="\d{1,8}" title="Solo se permiten números" 
           maxlength="8" required>
        </div>

        <!-- Género -->
        <div class="col-md-4">
            <label for="genero" class="form-label">Género:</label>
            <select class="form-select w-100" id="genero" name="genero" required>
                <option value="" selected disabled>Selecciona</option>
                <option value="M" <?php echo ($genero == 'M') ? 'selected' : ''; ?>>Masculino</option>
                <option value="F" <?php echo ($genero == 'F') ? 'selected' : ''; ?>>Femenino</option>
            </select>
        </div>

        <!-- Profesión -->
        <div class="col-md-4">
            <label for="profesion" class="form-label">Profesión:</label>
            <input type="text" class="form-control" id="profesion" name="profesion" 
                value="<?php echo htmlspecialchars($profesion); ?>" oninput="this.value = this.value.toUpperCase()">
        </div>

        <!-- Categoría -->
        <div class="col-md-4">
            <label for="categoria" class="form-label">Categoría:</label>
            <input type="text" class="form-control" id="categoria" name="categoria" 
                value="<?php echo htmlspecialchars($categoria); ?>" oninput="this.value = this.value.toUpperCase()">
        </div>

        <!-- Antigüedad -->
        <div class="col-md-4">
            <label for="antiguedad" class="form-label">Antigüedad:</label>
            <input type="text" class="form-control" id="antiguedad" name="antiguedad" 
                value="<?php echo htmlspecialchars($antiguedad); ?>">
        </div>

        <!-- Fecha de nacimiento -->
        <div class="col-md-4">
            <label for="fecha_nac" class="form-label">Fecha de Nacimiento:</label>
            <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" 
                value="<?php echo htmlspecialchars($fecha_nac); ?>">
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
</form>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
