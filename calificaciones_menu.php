<?php
include 'include/sesion.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calificaciones</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <?php include 'include/cabecera.php'; ?>
    <?php include 'include/menu.php'; ?>

    <div class="contenido main-content">
        <div class="container">

    <?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Administrador' || $rol === 'Profesor') { ?>

            <h4 class="mt-4">Reporte de notas</h4>
    
         <a href="reporte_cal.php" class="btn btn-danger"><i class='fas fa-print'></i> Reporte</a><br><br>


            <table id="tabla-inscripciones" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Grado</th>
                        <th>Paralelo</th>
                        <th>Nombres y apellidos</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'include/conexion.php';
$sql = "
    SELECT 
        i.id_usuario, 
        g.nom_gra AS grado, 
        p.nom_par AS paralelo, 
        u.nombre AS usuario, 
        i.fecha
    FROM inscripciones i
    JOIN grado g ON i.id_gra = g.id_gra
    JOIN paralelo p ON i.id_par = p.id_par
    JOIN usuario u ON i.id_usuario = u.id_usuario
    WHERE YEAR(i.fecha) = YEAR(CURDATE()) -- Filtrar por año actual
    ORDER BY g.abr_gra ASC
";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['grado'] . "</td>";
                            echo "<td>" . $row['paralelo'] . "</td>";
                            echo "<td>" . $row['usuario'] . "</td>";
                            echo "<td>
                                    <a href='calificaciones_est.php?id_usuario=" . $row['id_usuario'] . "' 
                                       class='btn btn-success btn-sm'>
                                        <i class='fas fa-eye'></i>
                                    </a>
                                    <a href='reporte_cal_est.php?id_usuario=" . $row['id_usuario'] . "' 
                                       class='btn btn-danger btn-sm'>
                                        <i class='fas fa-print'></i>
                                    </a>
                                  </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No se encontraron registros.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php }} ?>

                <?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Estudiante') { ?>
        <h4 class="mt-4">Mis notas</h4>
         <div class="col-md-12">
         <table id="tabla_calificaciones" class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>Materia</th>
                    <th>Calificación B1</th>
                    <th>Calificación B2</th>
                    <th>Calificación B3</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'include/conexion.php'; // Ajusta este archivo según tu conexión a la base de datos
                // Asignar la variable de sesión a otra variable
                $id_user = $_SESSION['id_usuario'];

                // Obtener el año actual
                $anio_actual = date("Y");

                // Preparar la consulta para obtener las calificaciones del año actual
                $sql_select = "
                    SELECT c.id_cal, m.nom_mat, c.b1, c.b2, c.b3, c.promedio 
                    FROM calificaciones c 
                    JOIN materia m ON c.id_mat = m.id_mat 
                    WHERE c.id_usuario = ? AND c.anio = ?";
                
                $stmt = $conn->prepare($sql_select);
                $stmt->bind_param("ii", $id_user, $anio_actual);
                $stmt->execute();
                $result = $stmt->get_result();

                // Mostrar las calificaciones en la tabla
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row['nom_mat']) . "</td>
                        <td>" . htmlspecialchars($row['b1']) . "</td>
                        <td>" . htmlspecialchars($row['b2']) . "</td>
                        <td>" . htmlspecialchars($row['b3']) . "</td>
                        <td>" . htmlspecialchars($row['promedio']) . "</td>
                    </tr>";
                }

                // Cerrar la declaración
                $stmt->close();
                ?>
            </tbody>
        </table>
</div>
<?php }} ?>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#tabla-inscripciones').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });
        });

    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
