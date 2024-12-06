<?php include 'include/sesion.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
    <link rel="stylesheet" type="text/css" href="style/estilos.css">
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#usuariosTable').DataTable();
        });
    </script>
</head>
<body>
<?php include 'include/cabecera.php'; ?>
<?php include 'include/menu.php'; ?>

<div class="contenido main-content">
    <h3 class="display-8 fw-semibold text-uppercase text-center fw-bold fst-italic text-end">Profesores</h3>
    <a href="agregar_usuario.php"><button class="boton">Nuevo usuario</button></a>
    <a href="usuarios.php"><button class="boton">Administrador</button></a>
    <a href="usuarios_prof.php"><button class="boton">Profesores</button></a>
    <a href="usuarios_estudiantes.php"><button class="boton">Estudiantes</button></a>

    <br>
    <br>

    <div class="table-responsive">
        <table id="usuariosTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>N°</th>
                    <th>Foto</th>
                    <th>Usuario</th>
                    <th>Rol</th>
                    <th>Nombre</th>
                    <th>Gestionar</th>
                </tr>
            </thead>
            <tbody>
                <?php include 'include/conexion.php'; ?>
                <?php
                $sql = "SELECT * FROM usuario WHERE rol IN ('Profesor')";
                $result = $conn->query($sql); 
                $numeroFila = 1; // Variable para contar las filas

                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$numeroFila}</td>"; // Mostrar el número de fila
                    echo "<td>";
                    if (!empty($row['foto'])) {
                        echo "<img src='{$row['foto']}' class='img-fluid rounded-circle' style='width: 50px; height: 50px;'></a>";
                    } else {
                        echo "<img src='img/user.png' alt='Sin foto' style='width: 50px; height: 50px;'>";
                    }
                    echo "</td>";
                    echo "<td>{$row['usuario']}</td>";
                    echo "<td>{$row['rol']}</td>";
                    echo "<td>{$row['nombre']}</td>";
                    echo "<td>
                        <a href='ver_prof.php?id_usuario={$row['id_usuario']}' class='btn btn-secondary'>
                            <i class='fa fa-eye'></i>
                        </a>
                        <a href='editar_usuario.php?id_usuario={$row['id_usuario']}' class='btn btn-success'>Editar</a>
                        <a href='editar_contrasena.php?id_usuario={$row['id_usuario']}' class='btn btn-warning'><li class='fa fa-key'></li></a>
                        <a href='include/eliminar_usuario.php?id_usuario={$row['id_usuario']}' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\")' class='btn btn-danger'>Eliminar</a>
                    </td>";
                    echo "</tr>";
                    
                    $numeroFila++; // Incrementar el número de fila para la siguiente iteración
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
