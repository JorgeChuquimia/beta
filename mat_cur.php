<?php 
include 'include/conexion.php'; // Incluye la conexión a la base de datos
include 'include/sesion.php';

$mensaje = "";
$mensajeClase = "";

// Registro de nueva materia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['materia'])) {
    $nom_mat = strtoupper($_POST['materia']);

        // Verifica si la materia ya existe para el mismo grado
    $sql = $conn->prepare("SELECT * FROM materia WHERE nom_mat = ?");
    $sql->bind_param("s", $nom_mat);
    $sql->execute();
    $result = $sql->get_result();
    
    if ($result->num_rows == 0) {
        // Inserta la nueva materia
        $stmt = $conn->prepare("INSERT INTO materia (nom_mat) VALUES (?)");
        $stmt->bind_param("s", $nom_mat);
        if ($stmt->execute()) {
            $mensaje = "Materia registrada con éxito.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al registrar la materia.";
            $mensajeClase = "alert alert-danger";
        }
        $stmt->close();
    } else {
        $mensaje = "La materia ya está registrada.";
        $mensajeClase = "alert alert-warning";
    }
    $sql->close();
}

function obtenerMaterias() {
    include 'include/conexion.php'; 
    $query = "SELECT id_mat, nom_mat FROM materia"; // Consulta solo de materias
    $result = $conn->query($query);

    $materias = array();
    while ($row = $result->fetch_assoc()) {
        $materias[] = $row; // Guarda las materias en el array
    }

    $conn->close();
    return $materias; // Devuelve las materias
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias y Cursos</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>
    
    <div class="contenido main-content">
        <h3 class="display-8 fw-semibold text-uppercase text-center fw-bold fst-italic text-end">Materias</h3>
        <a href="asignar.php"><button class="boton">Asignar cursos</button></a>
        <a href="grados.php"><button class="boton">Grados</button></a>
        <a href="paralelos.php"><button class="boton">Paralelos</button></a>
        <a href="mat_cur.php"><button class="boton">Materias</button></a>
        
        <br>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Registro de nueva materia</div>
                    <div class="card-body">
                        <?php if (!empty($mensaje)) { ?>
                            <div class="<?php echo $mensajeClase; ?>">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>
                        
                        <form action="" method="POST">
                            <div class="mb-3" align="left">
                                <label for="materia" class="form-label">Materia</label>
                                <input type="text" class="form-control" id="materia" name="materia" oninput="this.value = this.value.toUpperCase()" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="table-responsive overflow-auto" style="max-height: 60vh;">
                    <table id="materiasTable" class="table table-bordered table-hover">
                        <thead class="table-striped">
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php 
    $materias = obtenerMaterias();
    $contador = 1;
    foreach ($materias as $materia) { ?>
        <tr>
            <td><?php echo $contador++; ?></td>
            <td><?php echo htmlspecialchars($materia['nom_mat']); ?></td>
            <td>
              <a href="include/eli_mat.php?id_mat=<?php echo $materia['id_mat']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta materia?');" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
    <?php } ?>
</tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#materiasTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>
</html>
