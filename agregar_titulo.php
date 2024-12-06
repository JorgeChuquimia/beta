<?php
include 'include/sesion.php'; // Asegúrate de que la sesión está incluida
include 'include/conexion.php'; // Asegúrate de que la conexión a la base de datos está incluida
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Obtener el id_prof enviado desde el formulario
$id_prof = isset($_POST['id_prof']) ? intval($_POST['id_prof']) : null;
$nom_tit = isset($_POST['nom_tit']) ? $_POST['nom_tit'] : '';
$institucion = isset($_POST['institucion']) ? $_POST['institucion'] : '';
$anio_obtencion = isset($_POST['anio_obtencion']) ? $_POST['anio_obtencion'] : '';

// Verificar que el id_prof sea válido
if ($id_prof === null) {
    echo "Error: No se ha proporcionado id_prof.";
    exit();
}

// Consulta para insertar el nuevo título
$sql = "INSERT INTO titulos (nom_tit, institucion, anio_obtencion, id_prof) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nom_tit, $institucion, $anio_obtencion, $id_prof);

if ($stmt->execute()) {
    header("Location: per_prof.php"); // Redirige a la página de perfiles de profesor (ajusta según corresponda)
} else {
    echo "Error al guardar el título: " . $conn->error;
}

$stmt->close();
$conn->close();
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Nuevo Título</title>
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
            <h2>Agregar Nuevo Título</h2>
            <form action="" method="post">
                <input type="hidden" name="id_prof" value="<?php echo htmlspecialchars($_GET['id_prof']); ?>">

                <div class="form-group">
                    <label for="nom_tit">Nombre del Título:</label>
                    <input type="text" id="nom_tit" name="nom_tit" class="form-control" oninput="this.value = this.value.toUpperCase()" required>
                </div>

                <div class="form-group">
                    <label for="institucion">Institución:</label>
                    <input type="text" id="institucion" name="institucion" class="form-control" oninput="this.value = this.value.toUpperCase()" required>
                </div>

                <div class="form-group">
                    <label for="anio_obtencion">Año de Obtención:</label>
                    <input type="date" id="anio_obtencion" name="anio_obtencion" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
