<?php
include 'include/sesion.php'; 
include 'include/conexion.php';

// Encapsular obtención de nombre del estudiante
function obtenerNombreEstudiante($conn, $id_usuario) {
    $sql_estudiante = "SELECT nombre FROM usuario WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql_estudiante);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $stmt->bind_result($nombre);
    $stmt->fetch();
    $stmt->close();
    return $nombre;
}

// Encapsular obtención de materias
function obtenerMaterias($conn) {
    $sql_materias = "SELECT id_mat, nom_mat FROM materia ORDER BY nom_mat ASC";
    return $conn->query($sql_materias);
}

// Obtener el id_cal de GET
$id_cal = $_GET['id_cal'] ?? null;

if (!$id_cal) {
    die("ID de calificación no proporcionado.");
}

// Variables para almacenar los datos de la calificación
$id_usuario_cal = $id_materia = $calificacion_b1 = $calificacion_b2 = $calificacion_b3 = $promedio = "";

// Cargar datos de la calificación si se recibe el id_cal
$sql_calificacion = "SELECT id_usuario, id_mat, b1, b2, b3 FROM calificaciones WHERE id_cal = ?";
$stmt = $conn->prepare($sql_calificacion);
$stmt->bind_param("i", $id_cal);
$stmt->execute();
$stmt->bind_result($id_usuario_cal, $id_materia, $calificacion_b1, $calificacion_b2, $calificacion_b3);
if ($stmt->fetch()) {
    $promedio = round(($calificacion_b1 + $calificacion_b2 + $calificacion_b3) / 3);
} else {
    die("No se encontró la calificación con el ID proporcionado.");
}
$stmt->close();

// Obtener nombre del estudiante
$nombre_estudiante = obtenerNombreEstudiante($conn, $id_usuario_cal);
$materias = obtenerMaterias($conn);

$mensaje = "";
$mensajeClase = "";

// Procesar el envío del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_materia = $_POST['id_materia'];
    $calificacion_b1 = $_POST['calificacion_b1'];
    $calificacion_b2 = $_POST['calificacion_b2'];
    $calificacion_b3 = $_POST['calificacion_b3'];
    $promedio = $_POST['promedio'];
    $anio = date("Y");

    // Realizar UPDATE ya que tenemos id_cal
    $sql_update = "UPDATE calificaciones SET id_mat = ?, b1 = ?, b2 = ?, b3 = ?, promedio = ?, anio = ? WHERE id_cal = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("iiiiiii",$id_materia, $calificacion_b1, $calificacion_b2, $calificacion_b3, $promedio, $anio, $id_cal);

    if ($stmt->execute()) {
        $mensaje = "Calificación actualizada exitosamente.";
        $mensajeClase = "alert alert-success";
    } else {
        $mensaje = "Error al guardar las calificaciones: " . $stmt->error;
        $mensajeClase = "alert alert-danger";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar Calificación</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" type="image/png" href="img/logo.png">
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>

    <div class="contenido main-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <?php if (!empty($mensaje)) { ?>
                    <div class="<?php echo $mensajeClase; ?>">
                        <?php echo $mensaje; ?>
                    </div>
                    <?php } ?>
                    <form method="post" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" value="<?php echo $nombre_estudiante; ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label for="id_materia">Materia:</label>
                            <select id="id_materia" name="id_materia" class="form-control" required>
                                <option value="">Seleccionar materia...</option>
                                <?php while ($fila = $materias->fetch_assoc()): ?>
                                    <option value="<?php echo $fila['id_mat']; ?>" <?php if ($id_materia == $fila['id_mat']) echo 'selected'; ?>>
                                        <?php echo $fila['nom_mat']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label for="calificacion_b1">Calificación Bimestre 1:</label>
            <input type="number" id="calificacion_b1" name="calificacion_b1" 
                   class="form-control" placeholder="Ingrese la calificación" 
                   required oninput="calcularPromedio()" 
                   min="0" max="100" value="<?php echo $calificacion_b1; ?>">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="calificacion_b2">Calificación Bimestre 2:</label>
            <input type="number" id="calificacion_b2" name="calificacion_b2" 
                   class="form-control" placeholder="Ingrese la calificación" 
                   required oninput="calcularPromedio()" 
                   min="0" max="100" value="<?php echo $calificacion_b2; ?>">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label for="calificacion_b3">Calificación Bimestre 3:</label>
            <input type="number" id="calificacion_b3" name="calificacion_b3" 
                   class="form-control" placeholder="Ingrese la calificación" 
                   required oninput="calcularPromedio()"  
                   min="0" max="100" value="<?php echo $calificacion_b3; ?>">
        </div>
    </div>
</div>


                        <div class="form-group">
                            <label for="promedio">Promedio:</label>
                            <input type="text" id="promedio" name="promedio" class="form-control" readonly value="<?php echo $promedio; ?>">
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Actualizar Calificación</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calcularPromedio() {
            const b1 = parseInt(document.getElementById('calificacion_b1').value) || 0;
            const b2 = parseInt(document.getElementById('calificacion_b2').value) || 0;
            const b3 = parseInt(document.getElementById('calificacion_b3').value) || 0;
            const promedio = Math.round((b1 + b2 + b3) / 3);
            document.getElementById('promedio').value = promedio;
        }
    </script>
</body>
</html>
