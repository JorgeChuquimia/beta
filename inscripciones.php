<?php
include 'include/sesion.php';
include 'include/conexion.php'; 

// Establecer la zona horaria de La Paz, Bolivia
date_default_timezone_set('America/La_Paz');
$fecha_actual = date('Y-m-d');
$anio_actual = date('Y');
$mensaje = "";
$mensajeClase = ""; // Corregido: Agregado punto y coma al final

// Consulta para obtener grados
$sql_grados = "SELECT id_gra, nom_gra FROM grado";
$result_grados = $conn->query($sql_grados);

// Consulta para obtener paralelos
$sql_paralelos = "SELECT id_par, nom_par FROM paralelo";
$result_paralelos = $conn->query($sql_paralelos);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos enviados desde el formulario
    $id_grado = $_POST['id_grado'];
    $id_paralelo = $_POST['id_paralelo'];
    $fecha_reg = $_POST['fecha_reg'];
    $id_usuario = $_SESSION['id_usuario'];

    // Extraer solo el año de la fecha de registro
    $anio_registro = date('Y', strtotime($fecha_reg));

    // Verificar si el usuario ya está inscrito en el mismo año
    $sql_verificacion = "SELECT * FROM inscripciones WHERE id_usuario = ? AND YEAR(fecha) = ?";
    $stmt_verificacion = $conn->prepare($sql_verificacion);
    $stmt_verificacion->bind_param("ii", $id_usuario, $anio_registro);
    $stmt_verificacion->execute();
    $resultado_verificacion = $stmt_verificacion->get_result();

if ($resultado_verificacion->num_rows > 0) {
    // Si ya existe una inscripción en el mismo año, mostrar mensaje de error
    $mensaje = "Usted ya está inscrito.";
    $mensajeClase = "alert alert-warning";
} else {
    // Insertar la inscripción en la tabla inscripciones
    $sql_insertar = "INSERT INTO inscripciones (id_gra, id_par, fecha, id_usuario) VALUES (?, ?, ?, ?)";
    $stmt_insertar = $conn->prepare($sql_insertar);
    $stmt_insertar->bind_param("iisi", $id_grado, $id_paralelo, $fecha_reg, $id_usuario);

    if ($stmt_insertar->execute()) {
        $mensaje = "Inscripción realizada con éxito.";
        $mensajeClase = "alert alert-success";
        
        // Solo insertar en la tabla calificaciones para una nueva inscripción
        if ($id_grado == 1) {
            // Insertar solo la materia con id_mat = 1
            $sql_calificaciones = "INSERT INTO calificaciones (id_usuario, id_mat, anio) VALUES (?, 1, ?)";
            $stmt_calificaciones = $conn->prepare($sql_calificaciones);
            $stmt_calificaciones->bind_param("ii", $id_usuario, $anio_actual);
            $stmt_calificaciones->execute();
            $stmt_calificaciones->close();
        } else {
            // Insertar en la tabla calificaciones para todas las materias, excepto id_mat = 1
            $sql_materias = "SELECT id_mat FROM materia WHERE id_mat != 1";
            $result_materias = $conn->query($sql_materias);

            if ($result_materias->num_rows > 0) {
                while ($row_materia = $result_materias->fetch_assoc()) {
                    $id_materia = $row_materia['id_mat'];
                    $sql_calificaciones = "INSERT INTO calificaciones (id_usuario, id_mat, anio) VALUES (?, ?, ?)";
                    $stmt_calificaciones = $conn->prepare($sql_calificaciones);
                    $stmt_calificaciones->bind_param("iii", $id_usuario, $id_materia, $anio_actual);
                    $stmt_calificaciones->execute();
                    $stmt_calificaciones->close();
                }
            }
        }
    } else {
        $mensaje = "Error al registrar la inscripción. Inténtelo de nuevo.";
        $mensajeClase = "alert alert-danger";
    }
    $stmt_insertar->close();
}

    $stmt_verificacion->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Inscripción</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>

    <div class="contenido main-content">
        <div class="container">
            <div align="center">
            <h3>Inscripciones</h3></div>

    <?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];
    if ($rol === 'Administrador') { ?>
         <a href="adm_ins.php" class="btn btn-success">Administrar</a>
<?php }} ?>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php if (!empty($mensaje)) { ?>
                        <div class="<?php echo $mensajeClase; ?>">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="id_grado">Grado:</label>
                            <select class="form-control" id="id_grado" name="id_grado" required>
                                <option value="">Seleccionar Grado</option>
                                <?php if ($result_grados->num_rows > 0): ?>
                                    <?php while ($row = $result_grados->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_gra']; ?>">
                                            <?php echo htmlspecialchars($row['nom_gra']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="id_paralelo">Paralelo:</label>
                            <select class="form-control" id="id_paralelo" name="id_paralelo" required>
                                <option value="">Seleccionar Paralelo</option>
                                <?php if ($result_paralelos->num_rows > 0): ?>
                                    <?php while ($row = $result_paralelos->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_par']; ?>">
                                            <?php echo htmlspecialchars($row['nom_par']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha_reg">Fecha de Registro:</label>
                            <input type="date" class="form-control" id="fecha_reg" name="fecha_reg" value="<?php echo $fecha_actual; ?>" required readonly>
                        </div>
                        <div align="center">
                        <button type="submit" class="btn btn-primary">Inscribirse</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
