<?php 
include 'include/conexion.php';
include 'include/sesion.php';

// Función para obtener las inscripciones ordenadas por grado y paralelo
function obtenerInscripciones() {
    include 'include/conexion.php';
     $anio_actual = date('Y');
    $query = "
        SELECT i.id_ins, g.nom_gra, p.nom_par, i.fecha, u.nombre 
        FROM inscripciones i
        JOIN grado g ON i.id_gra = g.id_gra
        JOIN paralelo p ON i.id_par = p.id_par
        JOIN usuario u ON i.id_usuario = u.id_usuario
        WHERE YEAR(i.fecha) = $anio_actual
        ORDER BY g.nom_gra, p.nom_par, i.fecha DESC"; 
    $result = $conn->query($query);
    $inscripciones = array();
    while ($row = $result->fetch_assoc()) {
        $inscripciones[$row['nom_gra']][$row['nom_par']][] = $row;
    }
    $conn->close();
    return $inscripciones;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>
    
    <div class="contenido main-content">
        <?php 
        $inscripciones = obtenerInscripciones();
        foreach ($inscripciones as $grado => $paralelos) { 
            foreach ($paralelos as $paralelo => $estudiantes) { 
        ?>
            <div class="mt-4">
                <h5 class="text-center"><?php echo "$grado - $paralelo"; ?></h5>
                <a href="reporte_lis_est.php?grado=<?php echo urlencode($grado); ?>&paralelo=<?php echo urlencode($paralelo); ?>" class="btn btn-danger">
                <i class="fas fa-print"> Imprimir</i></a>

                <div class="table-responsive overflow-auto" style="max-height: 60vh;">
                    <table class="table table-bordered table-hover">
                        <thead class="table-striped">
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>Fecha</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $contador = 1;
                            foreach ($estudiantes as $inscripcion) { ?>
                                <tr>
                                    <td><?php echo $contador++; ?></td>
                                    <td><?php echo htmlspecialchars($inscripcion['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($inscripcion['fecha']); ?></td>
                                    <td>
                                        <a href="include/eli_ins.php?id_ins=<?php echo $inscripcion['id_ins']; ?>" onclick="return confirm('¿Estás seguro de eliminar esta inscripción?');" class="btn btn-danger btn-sm">Eliminar</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php 
            }
        }
        ?>
    </div>

    <!-- Script para DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('table').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/Spanish.json"
                },
                "paging": false, // Desactiva la paginación para mostrar todas las filas de cada tabla
                "ordering": false // Desactiva el ordenamiento de columnas por defecto
            });
        });
    </script>
</body>
</html>
