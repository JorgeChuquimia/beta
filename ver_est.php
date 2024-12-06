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
    $fecha_nac = $_POST['fecha_nac'];
    $nom_ppff = $_POST['nom_ppff'];
    $cel_ppff = $_POST['cel_ppff'];
    $ci_ppff = $_POST['ci_ppff'];
    $ocupacion_ppff = $_POST['ocupacion_ppff'];

    // Verificar si el estudiante ya existe en la base de datos
    $sql_check = "SELECT id_usuario FROM estudiante WHERE id_usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_usuario);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // Si el estudiante existe, realizar un UPDATE
        $sql_update = "UPDATE estudiante SET nombre = ?, ci = ?, exp = ?, zona = ?, calle = ?, casa = ?, celular = ?, genero = ?, fecha_nac = ?, nom_ppff = ?, cel_ppff = ?, ci_ppff = ?, ocupacion_ppff = ? WHERE id_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssssssssssi", $nombre_completo, $ci, $lugar_exp, $zona, $calle, $nro_casa, $cel, $genero, $fecha_nac, $nom_ppff, $cel_ppff, $ci_ppff, $ocupacion_ppff, $id_usuario);

        if ($stmt_update->execute()) {
            $mensaje = "Datos actualizados exitosamente.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al actualizar los datos: " . htmlspecialchars($stmt_update->error);
            $mensajeClase = "alert alert-danger";
        }
    } else {
        // Si el estudiante no existe, realizar un INSERT
        $sql_insert = "INSERT INTO estudiante (id_usuario, nombre, ci, exp, zona, calle, casa, celular, genero, fecha_nac, nom_ppff, cel_ppff, ci_ppff, ocupacion_ppff) 
               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);

// Corregir el número de parámetros en bind_param
$stmt_insert->bind_param("isssssssssssss", $id_usuario, $nombre_completo, $ci, $lugar_exp, $zona, $calle, $nro_casa, $cel, $genero, $fecha_nac, $nom_ppff, $cel_ppff, $ci_ppff, $ocupacion_ppff);

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

function obtenerDatosEstudiante($conn, $id_user) {
    $sql_select = "SELECT * FROM estudiante WHERE id_usuario = ?";
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
        $fecha_nac = $datos['fecha_nac'];
        $nom_ppff = $datos['nom_ppff'];
        $cel_ppff = $datos['cel_ppff'];
        $ci_ppff = $datos['ci_ppff'];
        $ocupacion_ppff = $datos['ocupacion_ppff'];
        
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
            'fecha_nac' => $fecha_nac,
            'nom_ppff' => $nom_ppff,
            'cel_ppff' => $cel_ppff,
            'ci_ppff' => $ci_ppff,
            'ocupacion_ppff' => $ocupacion_ppff
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
            'fecha_nac' => '',
            'nom_ppff' => '',
            'cel_ppff' => '',
            'ci_ppff' => '',
            'ocupacion_ppff' => ''
        );
    }
}

// Obtener los datos del estudiante
$usuario = obtenerDatosEstudiante($conn, $id_user);
$nombre_completo = htmlspecialchars($usuario['nombre']);
$ci = htmlspecialchars($usuario['ci']);
$lugar_exp = htmlspecialchars($usuario['exp']);
$zona = htmlspecialchars($usuario['zona']);
$calle = htmlspecialchars($usuario['calle']);
$nro_casa = htmlspecialchars($usuario['casa']);
$celular = htmlspecialchars($usuario['celular']);
$genero = htmlspecialchars($usuario['genero']);
$fecha_nac = htmlspecialchars($usuario['fecha_nac']);
$nom_ppff = htmlspecialchars($usuario['nom_ppff']);
$cel_ppff = htmlspecialchars($usuario['cel_ppff']);
$ci_ppff = htmlspecialchars($usuario['ci_ppff']);
$ocupacion_ppff = htmlspecialchars($usuario['ocupacion_ppff']);

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
    <title>Perfil del Estudiante</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>
    
    <div class="contenido main-content" align="center">
        <h4 class="text-center">Perfil del Estudiante</h4>
        <?php if ($mensaje): ?>
            <div class="<?php echo $mensajeClase; ?>" role="alert">
                <?php echo $mensaje; ?>
            </div>
        <?php endif; ?>
        
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" class="form-control" id="id_usuario" name="id_usuario" value="<?php echo htmlspecialchars($id_user); ?>" hidden>
            
            <div class="row mb-3">
                <!-- Nombre completo -->
                <div class="col-md-6">
                    <label for="nombre_completo" class="form-label">Nombre Completo:</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($nombre); ?>" oninput="this.value = this.value.toUpperCase()" readonly>
                </div>
                
                <!-- CI -->
                <div class="col-md-3">
                    <label for="ci" class="form-label">CI:</label>
                    <input type="text" class="form-control" id="ci" name="ci" value="<?php echo $ci; ?>" pattern="\d{1,8}" title="Solo se permiten números" required>
                </div>
                
                <!-- Lugar de expedición -->
                <div class="col-md-3">
                    <label for="lugar_exp" class="form-label">Expedido:</label>
                    <select class="form-select w-100" id="lugar_exp" name="lugar_exp" required>
                        <option value="" selected disabled>Selecciona</option>
                        <option value="LPZ" <?php echo ($lugar_exp == 'LPZ') ? 'selected' : ''; ?>>LPZ</option>
                        <option value="CBB" <?php echo ($lugar_exp == 'CBB') ? 'selected' : ''; ?>>CBB</option>
                        <option value="SCZ" <?php echo ($lugar_exp == 'SCZ') ? 'selected' : ''; ?>>SCZ</option>
                        <option value="OR" <?php echo ($lugar_exp == 'OR') ? 'selected' : ''; ?>>OR</option>
                        <option value="PT" <?php echo ($lugar_exp == 'PT') ? 'selected' : ''; ?>>PT</option>
                        <option value="CH" <?php echo ($lugar_exp == 'CH') ? 'selected' : ''; ?>>CH</option>
                        <option value="TJA" <?php echo ($lugar_exp == 'TJA') ? 'selected' : ''; ?>>TJA</option>
                        <option value="BNI" <?php echo ($lugar_exp == 'BNI') ? 'selected' : ''; ?>>BNI</option>
                        <option value="PND" <?php echo ($lugar_exp == 'PND') ? 'selected' : ''; ?>>PND</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <!-- Zona -->
                <div class="col-md-4">
                    <label for="zona" class="form-label">Zona:</label>
                    <input type="text" class="form-control" id="zona" name="zona" value="<?php echo $zona; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                
                <!-- Calle -->
                <div class="col-md-4">
                    <label for="calle" class="form-label">Calle:</label>
                    <input type="text" class="form-control" id="calle" name="calle" value="<?php echo $calle; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                
                <!-- Nro de Casa -->
                <div class="col-md-4">
                    <label for="nro_casa" class="form-label">Nro. de Casa:</label>
                    <input type="text" class="form-control" id="nro_casa" name="nro_casa" value="<?php echo $nro_casa; ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <!-- Celular -->
<div class="col-md-4">
    <label for="cel" class="form-label">Celular:</label>
    <input type="text" class="form-control" id="cel" name="cel" value="<?php echo $celular; ?>" 
           pattern="\d{1,8}" title="Solo se permiten números" 
           maxlength="8" required>
</div>

                
                <!-- Género -->
                <div class="col-md-4">
                    <label for="genero" class="form-label">Género:</label>
                    <select class="form-select w-100" id="genero" name="genero" required>
                        <option value="" selected disabled>Selecciona</option>
                        <option value="MASCULINO" <?php echo ($genero == 'MASCULINO') ? 'selected' : ''; ?>>Masculino</option>
                        <option value="FEMENINO" <?php echo ($genero == 'FEMENINO') ? 'selected' : ''; ?>>Femenino</option>
                    </select>
                </div>
                
                <!-- Fecha de Nacimiento -->
                <div class="col-md-4">
                    <label for="fecha_nac" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" value="<?php echo $fecha_nac; ?>" required>
                </div>
            </div>
 <h5 class="text-center">Perfil del PPFF/Tutor</h5>
<div class="row mb-3">
    <div class="col-md-6">
        <label for="nom_ppff" class="form-label">Nombre Completo:</label>
        <input type="text" class="form-control" id="nom_ppff" name="nom_ppff" value="<?php echo $nom_ppff; ?>" oninput="this.value = this.value.toUpperCase()">
    </div> 
    <div class="col-md-6">
        <label for="cel_ppff" class="form-label">Celular:</label>
        <input type="text" class="form-control" id="cel_ppff" name="cel_ppff" value="<?php echo $cel_ppff; ?>" 
               pattern="\d{1,8}" title="Solo se permiten números" 
               maxlength="8" required>
    </div>
</div>

            <div class="row mb-3">
                <!-- CI de PPFF -->
                <div class="col-md-6">
                    <label for="ci_ppff" class="form-label">CI del padre de familia:</label>
                    <input type="text" class="form-control" id="ci_ppff" name="ci_ppff" value="<?php echo $ci_ppff; ?>" required>
                </div>

                <!-- Ocupación de PPFF -->
                <div class="col-md-6">
                    <label for="ocupacion_ppff" class="form-label">Ocupación de padre de familia:</label>
                    <input type="text" class="form-control" id="ocupacion_ppff" name="ocupacion_ppff" value="<?php echo $ocupacion_ppff; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
            </div>

            <!-- Botón de envío -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</body>
</html>
