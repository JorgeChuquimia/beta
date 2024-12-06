<?php include 'include/sesion.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
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
            <div class="row align-items-center">
                <!-- Columna de la izquierda con la imagen -->
                <div class="col-md-6">
                    <img src="img/portada.jpg" class="img-fluid" alt="Portada">
                </div>
                <!-- Columna de la derecha con el mensaje de bienvenida -->
                <div class="col-md-6 text-center">
                    <p class="display-4 fw-bold">¡Bienvenid@!</p>
                    <h4><?php echo $_SESSION['nombre']; ?></h4>
                    <p class="lead">Estamos encantados de tenerte de vuelta.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
