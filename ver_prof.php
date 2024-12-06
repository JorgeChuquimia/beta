<?php
include 'include/sesion.php';
include 'include/conexion.php';

$id_user = $_GET['id_usuario'];
$titulos = obtenerTitulos($id_user);
$mensaje = "";
$mensajeClase = "";

$stmt_check = null;
$stmt_update = null;
$stmt_insert = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_completo = $_POST['nombre_completo'];
    $ci = $_POST['ci'];
    $lugar_exp = $_POST['lugar_exp'];
    $zona = $_POST['zona'];
    $calle = $_POST['calle'];
    $nro_casa = $_POST['nro_casa'];
    $cel = $_POST['cel'];
    $genero = $_POST['genero'];
    $fecha_nac = $_POST['fecha_nac'];
    $esp_egreso = $_POST['esp_egreso'];
    $cat_gestion = $_POST['cat_gestion'];
    $categoria = $_POST['categoria'];
    $anio_ser = $_POST['anio_ser'];
    $rda_prof = $_POST['rda_prof'];


    $sql_check = "SELECT id_prof FROM profesor WHERE id_usuario = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("i", $id_user);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $sql_update = "UPDATE profesor SET nombre = ?, ci = ?, exp = ?, zona = ?, calle = ?, casa = ?, celular = ?, genero = ?, fecha_nac = ?, esp_egreso = ?, cat_gestion = ?, categoria = ?, anio_ser = ?, rda_prof = ? WHERE id_usuario = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssssssssssssssi", $nombre_completo, $ci, $lugar_exp, $zona, $calle, $nro_casa, $cel, $genero, $fecha_nac, $esp_egreso, $cat_gestion, $categoria, $anio_ser, $rda_prof, $id_user);

        if ($stmt_update->execute()) {
            $mensaje = "Datos actualizados exitosamente.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al actualizar los datos: " . htmlspecialchars($stmt_update->error);
            $mensajeClase = "alert alert-danger";
        }
    } else {
    $sql_insert = "INSERT INTO profesor (id_usuario, nombre, ci, exp, zona, calle, casa, celular, genero, fecha_nac, esp_egreso, cat_gestion, categoria, anio_ser, rda_prof) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("issssssssssssss", $id_user, $nombre_completo, $ci, $lugar_exp, $zona, $calle, $nro_casa, $cel, $genero, $fecha_nac, $esp_egreso, $cat_gestion, $categoria, $anio_ser, $rda_prof);


    if ($stmt_insert->execute()) {
        $mensaje = "Datos insertados exitosamente.";
        $mensajeClase = "alert alert-success";
    } else {
        $mensaje = "Error al insertar los datos: " . htmlspecialchars($stmt_insert->error);
        $mensajeClase = "alert alert-danger";
    }
}


    if ($stmt_check) $stmt_check->close();
    if ($stmt_update) $stmt_update->close();
    if ($stmt_insert) $stmt_insert->close();
}

function obtenerDatosProfesor($conn, $id_user) {
    $sql_select = "SELECT * FROM profesor WHERE id_usuario = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $id_user);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return array_fill_keys(['nombre', 'ci', 'exp', 'zona', 'calle', 'casa', 'celular', 'genero', 'fecha_nac', 'esp_egreso', 'cat_gestion', 'categoria', 'anio_ser', 'rda_prof'], '');
    }
}

$usuario = obtenerDatosProfesor($conn, $id_user);
$nombre_completo = htmlspecialchars($usuario['nombre']);
$ci = htmlspecialchars($usuario['ci']);
$lugar_exp = htmlspecialchars($usuario['exp']);
$zona = htmlspecialchars($usuario['zona']);
$calle = htmlspecialchars($usuario['calle']);
$nro_casa = htmlspecialchars($usuario['casa']);
$celular = htmlspecialchars($usuario['celular']);
$genero = htmlspecialchars($usuario['genero']);
$fecha_nac = htmlspecialchars($usuario['fecha_nac']);
$esp_egreso = htmlspecialchars($usuario['esp_egreso']);
$cat_gestion = htmlspecialchars($usuario['cat_gestion']);
$categoria = htmlspecialchars($usuario['categoria']);
$anio_ser = htmlspecialchars($usuario['anio_ser']);
$rda_prof = htmlspecialchars($usuario['rda_prof']);

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

function obtenerTitulos($id_user) {
    include 'include/conexion.php'; // Incluye la conexión a la base de datos

    // Obtiene el id_prof del usuario conectado
    $query_prof = "SELECT id_prof FROM profesor WHERE id_usuario = ?";
    $stmt_prof = $conn->prepare($query_prof);
    $stmt_prof->bind_param("i", $id_user);
    $stmt_prof->execute();
    $result_prof = $stmt_prof->get_result();
    
    // Asegúrate de que el usuario tiene al menos un id_prof asociado
    if ($row_prof = $result_prof->fetch_assoc()) {
        $id_prof = $row_prof['id_prof'];
        
        // Consulta para obtener los títulos basados en el id_prof
        $query_tit = "SELECT id_tit, nom_tit, institucion, anio_obtencion 
              FROM titulos 
              WHERE id_prof = ? 
              ORDER BY anio_obtencion DESC";

        $stmt_tit = $conn->prepare($query_tit);
        $stmt_tit->bind_param("i", $id_prof);
        $stmt_tit->execute();
        $result_tit = $stmt_tit->get_result();
        
        $titulos = array();
        while ($row_tit = $result_tit->fetch_assoc()) {
            $titulos[] = $row_tit; // Almacena los resultados en el array
        }
        
        $stmt_tit->close();
    } else {
        $titulos = array(); // No hay id_prof asociado al usuario
    }

    $stmt_prof->close();
    $conn->close(); // Cierra la conexión
    return $titulos; // Devuelve el array de títulos
}

function obtenerIdProf($id_user) {
    include 'include/conexion.php'; // Incluye la conexión a la base de datos

    $query_prof = "SELECT id_prof FROM profesor WHERE id_usuario = ?";
    $stmt_prof = $conn->prepare($query_prof);
    $stmt_prof->bind_param("i", $id_user);
    $stmt_prof->execute();
    $result_prof = $stmt_prof->get_result();
    
    if ($row_prof = $result_prof->fetch_assoc()) {
        $id_prof = $row_prof['id_prof'];
    } else {
        $id_prof = null; // Retorna null si no se encuentra id_prof
    }

    $stmt_prof->close();
    $conn->close(); // Cierra la conexión
    return $id_prof; // Devuelve el id_prof
}
// Obtener id_prof
$id_prof = obtenerIdProf($id_user);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Profesor</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>
    
    <div class="contenido main-content" align="center">
        <h5 class="text-center">Perfil del Profesor</h5>
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

                <!-- Lugar de Expedición -->
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
                    <input type="text" class="form-control" id="cel" name="cel" value="<?php echo $celular; ?>" pattern="\d{1,8}" title="Solo se permiten números" 
           maxlength="8"  required>
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
                
                <!-- Fecha de Nacimiento -->
                <div class="col-md-4">
                    <label for="fecha_nac" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fecha_nac" name="fecha_nac" value="<?php echo $fecha_nac; ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <!-- Especialidad de Egreso -->
                <div class="col-md-6">
                    <label for="esp_egreso" class="form-label">Especialidad de Egreso:</label>
                    <input type="text" class="form-control" id="esp_egreso" name="esp_egreso" value="<?php echo $esp_egreso; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                
                <!-- Categoría de Gestión -->
                <div class="col-md-6">
                    <label for="cat_gestion" class="form-label">Categoría de Gestión:</label>
                    <input type="text" class="form-control" id="cat_gestion" name="cat_gestion" value="<?php echo $cat_gestion; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <!-- Categoría -->
                <div class="col-md-6">
                    <label for="categoria" class="form-label">Categoría:</label>
                    <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo $categoria; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                
                <!-- Años de Servicio -->
                <div class="col-md-2">
                    <label for="anio_ser" class="form-label">Años de Servicio:</label>
                    <input type="text" class="form-control" id="anio_ser" name="anio_ser" value="<?php echo $anio_ser; ?>" required>
                </div>
                <!-- rda -->
                <div class="col-md-4">
                    <label for="rda_prof" class="form-label">RDA del Profesor:</label>
                    <input type="text" class="form-control" id="rda_prof" name="rda_prof" value="<?php echo $rda_prof; ?>" oninput="this.value = this.value.toUpperCase()" required>
                </div>
                </div>
            
            <!-- Botón de envío -->
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
       <div class="container mt-4">
        <h5 class="text-center">Titulos y certificaciones</h5>




        <!-- Tabla para mostrar los títulos -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre del Título</th>
                        <th>Institución</th>
                        <th>Año de Obtención</th>
                    </tr>
                </thead>
<tbody>
    <?php 
    $titulos = obtenerTitulos($id_user); // Llama a la función para obtener los títulos
    if (count($titulos) > 0) {
        $contador = 1;
        foreach ($titulos as $titulo) { ?>
            <tr>
                <td><?php echo $contador++; ?></td>
                <td><?php echo htmlspecialchars($titulo['nom_tit']); ?></td>
                <td><?php echo htmlspecialchars($titulo['institucion']); ?></td>
                <td><?php echo htmlspecialchars($titulo['anio_obtencion']); ?></td>
            </tr>
        <?php } 
    } else { ?>
        <tr>
            <td colspan="5" class="text-center">No hay títulos ni certificaiones registrados.</td>
        </tr>
    <?php } ?>
</tbody>


            </table>
        </div>
    </div>
    </div>
</body>
</html>
