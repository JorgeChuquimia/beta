<?php
include 'include/sesion.php'; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Calificaciones</title>
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
                <div class="col-md-12">
                    <div>
                        <table id="tabla_calificaciones" class="table-striped table-responsive">
                            <thead>
                                <tr>
                                    <th>Materia</th>
                                    <th>Calificación B1</th>
                                    <th>Calificación B2</th>
                                    <th>Calificación B3</th>
                                    <th>Promedio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                             <tbody>
                                <?php
                                include 'include/conexion.php';
                                // Usar el id_usuario de la sesión
                                $id_usuario = $_GET['id_usuario'];

                                // Obtener el año actual
                                $anio_actual = date("Y");

                                // Preparar la consulta para obtener las calificaciones del año actual
                                $sql_select = "
                                    SELECT c.id_cal, m.nom_mat, c.b1, c.b2, c.b3, c.promedio 
                                    FROM calificaciones c 
                                    JOIN materia m ON c.id_mat = m.id_mat 
                                    WHERE c.id_usuario = ? AND c.anio = ?";

                                // Preparar la consulta
                                if ($stmt = $conn->prepare($sql_select)) {
                                    $stmt->bind_param("ii", $id_usuario, $anio_actual);
                                    $stmt->execute();
                                    $result = $stmt->get_result();

                                    // Verifica si hay resultados
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                <td>" . htmlspecialchars($row['nom_mat']) . "</td>
                                                <td>" . htmlspecialchars($row['b1']) . "</td>
                                                <td>" . htmlspecialchars($row['b2']) . "</td>
                                                <td>" . htmlspecialchars($row['b3']) . "</td>
                                                <td>" . htmlspecialchars($row['promedio']) . "</td>
                                                <td>
                                                    <a href='calificaciones.php?id_cal=" . $row['id_cal'] . "' 
                                                       class='btn btn-primary btn-sm'>
                                                        <i class='fas fa-edit'></i>
                                                    </a>";

                                                    // Verificar si el usuario es Administrador
                                                    if (isset($_SESSION['usuario']) && $_SESSION['rol'] === 'Administrador') {
                                                        echo "<a href='include/eli_cal.php?id_cal=" . $row['id_cal'] . "' 
                                                            class='btn btn-danger btn-sm' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
                                                            <i class='fas fa-trash'></i>
                                                        </a>";
                                                    }

                                            echo "</td>
                                            </tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='5'>No hay calificaciones disponibles para este año.</td></tr>";
                                    }
                                    $stmt->close();
                                } else {
                                    echo "<tr><td colspan='5'>Error en la consulta: " . $conn->error . "</td></tr>";
                                }

                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

