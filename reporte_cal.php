<?php
include 'include/sesion.php';
include 'include/conexion.php'; 

// Consulta para obtener grados
$sql_grados = "SELECT id_gra, nom_gra FROM grado";
$result_grados = $conn->query($sql_grados);

// Consulta para obtener paralelos
$sql_paralelos = "SELECT id_par, nom_par FROM paralelo";
$result_paralelos = $conn->query($sql_paralelos);

// Consulta para obtener materias
$sql_materias = "SELECT id_mat, nom_mat FROM materia";
$result_materias = $conn->query($sql_materias);
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
                <h3>Generar Reporte</h3>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-6">
                    <?php if (!empty($mensaje)) { ?>
                        <div class="<?php echo $mensajeClase; ?>">
                            <?php echo $mensaje; ?>
                        </div>
                    <?php } ?>
                    <form action="reporte_cal_pdf.php" method="POST">
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
                            <label for="id_materia">Materia:</label>
                            <select class="form-control" id="id_materia" name="id_materia" required>
                                <option value="">Seleccionar Materia</option>
                                <?php if ($result_materias->num_rows > 0): ?>
                                    <?php while ($row = $result_materias->fetch_assoc()): ?>
                                        <option value="<?php echo $row['id_mat']; ?>">
                                            <?php echo htmlspecialchars($row['nom_mat']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </select>
                        </div>

                        <div align="center">
                            <button type="submit" class="btn btn-danger"><i class='fas fa-print'></i> Generar PDF</button>
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
