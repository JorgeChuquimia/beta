<style>
    .menu-desplegable {
    position: relative;
    display: inline-block;
}

.menu-desplegable .boton {
    background-color: transparent;
    border: none;
    cursor: pointer;
    color: white;
}

.menu-desplegable .user-image {
    border-radius: 50%;
}

.menu-contenido {
    display: none;
    position: absolute;
    background-color: #dd443d;
    min-width: 160px;
    max-width: 100vw; /* Limita el ancho del menú al 90% de la vista */
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
    right: 0; /* Alinea el menú a la derecha */
}

.menu-desplegable:hover .menu-contenido {
    display: block;
}


.menu-contenido a {
    color: white;
    text-decoration: none;
    display: block;
    padding: 8px 16px;
}

.menu-contenido a:hover {
    background-color: grey;
}

.menu-desplegable:hover .menu-contenido {
    display: block;
}

</style>
<div class="cabecera main-content">
    <div class="info-usuario">
        <?php
        if (isset($_SESSION['usuario'])) {

            echo "<h3>Bienvenido - U.E. Irene Nava de Castillo</h3>";
        }
        ?>
    </div>
<?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Administrador') { ?>
<div class="menu-desplegable">
    <?php
include 'include/foto_usuario.php';
if (isset($_SESSION['usuario'])) {
    echo '<button class="boton">';
    
    if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
        echo '<img src="' . $_SESSION['foto_usuario'] . '" alt="Foto de Usuario" class="user-image">';
    } else {
        echo '<img src="img/user.png" alt="Usuario" class="user-image">';
    }

    echo $_SESSION['usuario'] . '</button>';
    echo '<div class="menu-contenido">';
    echo '<a class="fa fa-school" href="inicio_ad.php"> Inicio</a>';
    echo '<a class="fa fa-pen" href="per_adm.php"> Mi perfil</a>';
    echo '<a class="fa fa-users" href="usuarios.php"> Usuarios</a>';
    echo '<a class="fa fa-file-signature" href="inscripciones.php"> Inscripciones</a>';
    echo '<a class="fa fa-book" href="asignar.php"> Materias y cursos</a>';
    echo '<a class="fa fa-graduation-cap" href="calificaciones_menu.php"> Calificaciones</a>';
    echo '<a class="fa fa-door-open" href="include/cerrar.php"> Cerrar Sesión</a>';
    echo '</div>';
}
?>

</div>

<?php }} ?>


<?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Estudiante') { ?>
<div class="menu-desplegable">
    <?php
include 'include/foto_usuario.php';
if (isset($_SESSION['usuario'])) {
    echo '<button class="boton">';
    
    if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
        echo '<img src="' . $_SESSION['foto_usuario'] . '" alt="Foto de Usuario" class="user-image">';
    } else {
        echo '<img src="img/user.png" alt="Usuario" class="user-image">';
    }

    echo $_SESSION['usuario'] . '</button>';
    echo '<div class="menu-contenido">';
    echo '<a class="fa fa-school" href="inicio_ad.php"> Inicio</a>';
    echo '<a class="fa fa-pen" href="per_est.php"> Mi perfil</a>';
    echo '<a class="fa fa-file-signature" href="inscripciones.php"> Inscripciones</a>';
    echo '<a class="fa fa-graduation-cap" href="calificaciones_menu.php"> Calificaciones</a>';
    echo '<a class="fa fa-door-open" href="include/cerrar.php"> Cerrar Sesión</a>';
    echo '</div>';
}
?>

</div>

<?php }} ?>



<?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Profesor') { ?>
<div class="menu-desplegable">
    <?php
include 'include/foto_usuario.php';
if (isset($_SESSION['usuario'])) {
    echo '<button class="boton">';
    
    if (isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario'])) {
        echo '<img src="' . $_SESSION['foto_usuario'] . '" alt="Foto de Usuario" class="user-image">';
    } else {
        echo '<img src="img/user.png" alt="Usuario" class="user-image">';
    }

    echo $_SESSION['usuario'] . '</button>';
    echo '<div class="menu-contenido">';
    echo '<a class="fa fa-school" href="inicio_ad.php"> Inicio</a>';
    echo '<a class="fa fa-pen" href="per_prof.php"> Mi perfil</a>';
    echo '<a class="fa fa-graduation-cap" href="calificaciones_menu.php"> Calificaciones</a>';
    echo '<a class="fa fa-door-open" href="include/cerrar.php"> Cerrar Sesión</a>';
    echo '</div>';
}
?>

</div>

<?php }} ?>
</div>