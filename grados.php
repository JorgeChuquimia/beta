<?php 
include 'include/conexion.php'; // Incluye la conexión a la base de datos
include 'include/sesion.php';

$mensaje = "";
$mensajeClase = "";

// Registro de nuevo grado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['grado']) && isset($_POST['abreviatura'])) {
    $nom_gra = strtoupper($_POST['grado']);
    $abr_gra = $_POST['abreviatura'];
    
    // Verifica si el grado ya existe
    $sql = $conn->prepare("SELECT * FROM grado WHERE nom_gra = ? OR abr_gra = ?");
    $sql->bind_param("si", $nom_gra, $abr_gra);
    $sql->execute();
    $result = $sql->get_result();
    
    if ($result->num_rows == 0) {
        // Inserta el nuevo grado
        $stmt = $conn->prepare("INSERT INTO grado (nom_gra, abr_gra) VALUES (?, ?)");
        $stmt->bind_param("si", $nom_gra, $abr_gra);
        if ($stmt->execute()) {
            $mensaje = "Grado registrado con éxito.";
            $mensajeClase = "alert alert-success";
        } else {
            $mensaje = "Error al registrar el grado.";
            $mensajeClase = "alert alert-danger";
        }
        $stmt->close();
    } else {
        $mensaje = "El grado o abreviatura ya está registrado.";
        $mensajeClase = "alert alert-warning";
    }
    $sql->close();
}

function obtenerGrados() {
    include 'include/conexion.php'; 
    $query = "SELECT id_gra, nom_gra, abr_gra FROM grado ORDER BY abr_gra"; 
    $result = $conn->query($query);
    $grados = array();
    while ($row = $result->fetch_assoc()) {
        $grados[] = $row;
    }
    $conn->close();
    return $grados;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grados y Paralelos</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>
    
    <div class="contenido main-content">
        <h3 class="display-8 fw-semibold text-uppercase text-center fw-bold fst-italic text-end">Grados</h3>
        <a href="asignar.php"><button class="boton">Asignar cursos</button></a>
        <a href="grados.php"><button class="boton">Grados</button></a>
        <a href="paralelos.php"><button class="boton">Paralelos</button></a>
        <a href="mat_cur.php"><button class="boton">Materias</button></a>
        <br>

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">Registro de nuevo grado</div>
                    <div class="card-body">
                        <?php if (!empty($mensaje)) { ?>
                            <div class="<?php echo $mensajeClase; ?>">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>
                        
                        <form action="" method="POST">
                            <div class="mb-3" align="left">
                                <label for="grado" class="form-label">Grado</label>
                                <input type="text" class="form-control" id="grado" name="grado" oninput="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="mb-3" align="left">
                                <label for="abreviatura" class="form-label">Abreviatura</label>
                                <input type="text" class="form-control" id="abreviatura" name="abreviatura" maxlength="1" oninput="this.value = this.value.replace(/[^0-9]/g, '');" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="table-responsive overflow-auto" style="max-height: 60vh;">
                    <table id="gradosTable" class="table table-bordered table-hover">
                        <thead class="table-striped">
                            <tr>
                                <th>#</th>
                                <th>Grado</th>
                                <th>Abreviatura</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php 
    $grados = obtenerGrados();
    $contador = 1;
    foreach ($grados as $grado) { ?>
        <tr>
            <td><?php echo $contador++; ?></td>
            <td><?php echo htmlspecialchars($grado['nom_gra']); ?></td>
            <td><?php echo htmlspecialchars($grado['abr_gra']); ?></td>
            <td>
              <a href="include/eli_gra.php?id_gra=<?php echo $grado['id_gra']; ?>" onclick="return confirm('¿Estás seguro de eliminar este grado?');" class="btn btn-danger btn-sm">Eliminar</a>
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
            $('#gradosTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json"
                }
            });
        });
    </script>
</body>
</html>
