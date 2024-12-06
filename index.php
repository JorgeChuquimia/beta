<?php include 'include/conexion.php'; ?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $mensaje = "";
    $mensajeClase = "";
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];
    
    $sql = $conn->prepare("SELECT * FROM usuario WHERE usuario = ?");
    $sql->bind_param("s", $usuario);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($contrasena, $row['password'])) {
            session_start();
            $_SESSION['usuario'] = $usuario;
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['rol'] = $row['rol'];
            $_SESSION['nombre'] = $row['nombre'];
            header("Location: inicio_ad.php");
            exit();
        } else {
            $mensaje = "Contraseña incorrecta.";
            $mensajeClase = "alert alert-danger";
        }
    } else {
        $mensaje = "Datos incorrectos.";
        $mensajeClase = "alert alert-danger";
    }
    $sql->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>U.E. Irene Nava de Castillo - Página de inicio de sesión</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <link rel="icon" type="image/png" href="img/logo.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .content-wrapper {
            background-image: url('img/background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            width: 100%;
        }

        .auth-form-light {
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            padding: 2rem;
            background: #fff;
        }

        .btn {
            border-radius: 5px;
            font-size: 1.1rem;
        }

        .auth-form-light {
            max-width: 100%;
        }

        .brand-logo img {
            max-width: 150px;
            height: auto;
        }

        .brand-logo {
            margin-bottom: 5px;
        }

        .intro-text {
            font-size: 1rem;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        @media (max-width: 767px) {
            .form-group label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth">
                <div class="row flex-grow">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-center p-5">
                            <div class="brand-logo">
                                <img src="img/logo.png" alt="Logo">
                            </div>
                            <h5 class="intro-text">"SISTEMA WEB DE INSCRIPCIONES, CALIFICACIONES E INFORMACIÓN DOCENTE"</h5>
                            <form class="pt-3" id="login" method="post" action="" name="login">
                                <?php if (!empty($mensaje)) { ?>
                                    <div class="<?php echo $mensajeClase; ?>">
                                        <?php echo $mensaje; ?>
                                    </div>
                                <?php } ?>

                                <!-- Grupo de Usuario -->
                                <div class="form-group row">
                                    <label for="usuario" class="col-sm-4 col-form-label text-left">Usuario:</label>
                                    <div class="col-sm-8">
                                        <input type="text" id="usuario" name="usuario" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Grupo de Contraseña -->
                                <div class="form-group row">
                                    <label for="contrasena" class="col-sm-4 col-form-label text-left">Contraseña:</label>
                                    <div class="col-sm-8">
                                        <input type="password" id="contrasena" name="contrasena" class="form-control" required>
                                    </div>
                                </div>

                                <!-- Botón centrado -->
                                <div class="d-flex justify-content-center mt-3">
                                    <input class="btn btn-primary" type="submit" value="Iniciar Sesión">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

