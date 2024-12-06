<?php 
include 'include/conexion.php'; // Incluye la conexión a la base de datos
include 'include/sesion.php';

$mensaje = "";
$mensajeClase = "";

// Registro de asignación
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['profesor']) && isset($_POST['grado']) && isset($_POST['paralelo'])) {
    $id_usuario = $_POST['profesor'];
    $id_gra = $_POST['grado'];
    $id_par = $_POST['paralelo'];
    
    // Verifica si la asignación ya existe
    // $sql = $conn->prepare("SELECT * FROM asignar WHERE id_usuario = ? AND id_gra = ? AND id_par = ?");
    // $sql->bind_param("iii", $id_usuario, $id_gra, $id_par);
    // $sql->execute();
    // $result = $sql->get_result();
    
    // if ($result->num_rows == 0) {
        // Inserta la nueva asignación
        $stmt = $conn->prepare("INSERT INTO asignar (id_usuario, id_gra, id_par) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $id_usuario, $id_gra, $id_par);
        if ($stmt->execute()) {
            $mensaje = "Asignación registrada con éxito.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al registrar la asignación.";
            $mensajeClase = "alert alert-danger";
        }
        $stmt->close();
   //  } else {
     //    $mensaje = "La asignación ya existe.";
     //    $mensajeClase = "alert alert-warning";
   //  }
 //    $sql->close();
}

function obtenerProfesores() {
    include 'include/conexion.php'; 
    $query = "SELECT id_usuario, nombre, rol FROM usuario WHERE rol = 'profesor' ORDER BY nombre"; 
    $result = $conn->query($query);
    $profesores = array();
    while ($row = $result->fetch_assoc()) {
        $profesores[] = $row;
    }
    $conn->close();
    return $profesores;
}

function obtenerGrados() {
    include 'include/conexion.php'; 
    $query = "SELECT id_gra, nom_gra FROM grado ORDER BY nom_gra"; 
    $result = $conn->query($query);
    $grados = array();
    while ($row = $result->fetch_assoc()) {
        $grados[] = $row;
    }
    $conn->close();
    return $grados;
}

function obtenerParalelos() {
    include 'include/conexion.php'; 
    $query = "SELECT id_par, nom_par FROM paralelo ORDER BY nom_par"; 
    $result = $conn->query($query);
    $paralelos = array();
    while ($row = $result->fetch_assoc()) {
        $paralelos[] = $row;
    }
    $conn->close();
    return $paralelos;
}

function obtenerAsignaciones() {
    include 'include/conexion.php';
    $query = "SELECT a.id_asi, u.nombre AS profesor, g.nom_gra AS grado, p.nom_par AS paralelo 
              FROM asignar a 
              JOIN usuario u ON a.id_usuario = u.id_usuario
              JOIN grado g ON a.id_gra = g.id_gra
              JOIN paralelo p ON a.id_par = p.id_par
              ORDER BY g.nom_gra, p.nom_par"; 
    $result = $conn->query($query);
    $asignaciones = array();
    while ($row = $result->fetch_assoc()) {
        $asignaciones[] = $row;
    }
    $conn->close();
    return $asignaciones;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignaciones</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>
    
    <div class="contenido main-content">
        <h3 class="display-8 fw-semibold text-uppercase text-center fw-bold fst-italic text-end">Asignar Grado y Paralelo</h3>
                <a href="asignar.php"><button class="boton">Asignar cursos</button></a>
        <a href="grados.php"><button class="boton">Grados</button></a>
        <a href="paralelos.php"><button class="boton">Paralelos</button></a>
        <a href="mat_cur.php"><button class="boton">Materias</button></a>
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($mensaje)) { ?>
                            <div class="<?php echo $mensajeClase; ?>">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>
                        
                        <form action="" method="POST">
                            <div class="mb-3" align="left">
                                <label for="profesor" class="form-label">Seleccionar Profesor</label>
                                <select class="form-control" id="profesor" name="profesor" required>
                                    <option value="">Seleccionar</option>
                                    <?php 
                                    $profesores = obtenerProfesores();
                                    foreach ($profesores as $profesor) {
                                    ?>
                                        <option value="<?php echo $profesor['id_usuario']; ?>"><?php echo $profesor['nombre']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3" align="left">
                                <label for="grado" class="form-label">Seleccionar Grado</label>
                                <select class="form-control" id="grado" name="grado" required>
                                    <option value="">Seleccionar</option>
                                    <?php 
                                    $grados = obtenerGrados();
                                    foreach ($grados as $grado) {
                                    ?>
                                        <option value="<?php echo $grado['id_gra']; ?>"><?php echo $grado['nom_gra']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="mb-3" align="left">
                                <label for="paralelo" class="form-label">Seleccionar Paralelo</label>
                                <select class="form-control" id="paralelo" name="paralelo" required>
                                    <option value="">Seleccionar</option>
                                    <?php 
                                    $paralelos = obtenerParalelos();
                                    foreach ($paralelos as $paralelo) {
                                    ?>
                                        <option value="<?php echo $paralelo['id_par']; ?>"><?php echo $paralelo['nom_par']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="table-responsive overflow-auto" style="max-height: 60vh;">
                    <table id="asignacionesTable" class="table table-bordered table-hover">
                        <thead class="table-striped">
                            <tr>
                                <th>#</th>
                                <th>Profesor</th>
                                <th>Grado</th>
                                <th>Paralelo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php 
    $asignaciones = obtenerAsignaciones();
    $contador = 1;
    foreach ($asignaciones as $asignacion) { ?>
        <tr>
            <td><?php echo $contador++; ?></td>
            <td><?php echo htmlspecialchars($asignacion['profesor']); ?></td>
            <td><?php echo htmlspecialchars($asignacion['grado']); ?></td>
            <td><?php echo htmlspecialchars($asignacion['paralelo']); ?></td>
            <td>
              <a href="include/eli_asig.php?id_asi=<?php echo $asignacion['id_asi']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta asignación?');" class="btn btn-danger btn-sm">Eliminar</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
  </table>
</div>
</div>

</div>

</div>

</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#asignacionesTable').DataTable();
    });
</script>
