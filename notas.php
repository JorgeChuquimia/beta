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
        <h2 class="text-center my-4">Calificaciones del Estudiante</h2>
        <table class="table table-bordered table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Bimestre</th>
                    <th>Calificación</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Bimestre 1</td>
                    <td>85</td>
                </tr>
                <tr>
                    <td>Bimestre 2</td>
                    <td>90</td>
                </tr>
                <tr>
                    <td>Bimestre 3</td>
                    <td>88</td>
                </tr>
                <tr>
                    <td>Bimestre 4</td>
                    <td>92</td>
                </tr>
                <tr>
                    <th>Promedio Final</th>
                    <th>88.75</th>
                </tr>
            </tbody>
        </table>
    </div>
</

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
