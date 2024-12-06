<style>
/* General styles for the sidebar */
.sidebar {
    width: 250px;
    height: 100vh;
    background-color: #214a8b;
    /*background-color: #9B45EB;*/
    position: fixed;
    top: 0;
    left: 0;
    padding-top: 20px;
    z-index: 1000;
}

.sidebar ul {
    list-style-type: none;
    padding: 0;
}

.sidebar ul img {
    margin-bottom: 20px;
}

.sidebar ul li {
    width: 100%;
}

.sidebar ul li a {
    display: block;
    color: white;
    padding: 8px; /* Reducción del padding */
    font-size: 0.9rem; /* Tamaño de fuente reducido */
    text-decoration: none;
}

.sidebar ul li a:hover {
    background-color: #575757;
}

/* Ensure main content is visible */
.main-content {
    margin-left: 250px;
    padding: 20px;
}

/* Hide sidebar on small screens and adjust main content */
@media (max-width: 767.98px) {
    .sidebar {
        display: none;
    }

    .main-content {
        margin-left: 0;
        padding: 20px;
    }
}

.logo {
    width: 120px;
    height: 120px;
    border-radius: 80%;
    background-color: #ffffff;
    border: 5px solid #dd443d;
    display: inline-block;
    overflow: hidden;
    box-sizing: border-box; /* Asegura que el tamaño total sea de 120x120 píxeles incluyendo el borde */
}

.logo img {
    width: 70%;
    height: 95%;
    
}

</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Administrador') { ?>
<div class="sidebar" align="center">
    <ul>
        <div class="logo">
    <img src="img/logo.png" alt="Usuario">
</div>
<li><a class="fa fa-home" href="inicio_ad.php"> Inicio</a></li>
<li><a class="fa fa-user-cog" href="usuarios.php"> Administrar usuarios</a></li>
<li><a class="fa fa-pen" href="per_adm.php"> Mi perfil</a></li>
<li><a class="fa fa-file-signature" href="inscripciones.php"> Inscripciones</a></li>
<li><a class="fa fa-book" href="asignar.php"> Materias y cursos</a></li>
<li><a class="fa fa-graduation-cap" href="calificaciones_menu.php"> Calificaciones</a></li>
<li><a class="fa fa-database" href="backup.php"> Copia de seguridad</a></li>
<li><a class="fa fa-sign-out-alt" href="include/cerrar.php"> Cerrar Sesión</a></li>
</ul>
</div>

<?php }} ?>

<?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Estudiante') { ?>
<div class="sidebar" align="center">
    <ul>
        <div class="logo">
    <img src="img/logo.png" alt="Usuario">
</div>
<li><a class="fa fa-home" href="inicio_ad.php"> Inicio</a></li>
<li><a class="fa fa-pen" href="per_est.php"> Mi perfil</a></li>
<li><a class="fa fa-file-signature" href="inscripciones.php"> Inscripciones</a></li>
<li><a class="fa fa-graduation-cap" href="calificaciones_menu.php"> Calificaciones</a></li>
<li><a class="fa fa-sign-out-alt" href="include/cerrar.php"> Cerrar Sesión</a></li>
    </ul>
</div>

<?php }} ?>


<?php if (isset($_SESSION['usuario'])) {
    $rol = $_SESSION['rol'];

    if ($rol === 'Profesor') { ?>
<div class="sidebar" align="center">
    <ul>
        <div class="logo">
    <img src="img/logo.png" alt="Usuario">
</div>
<li><a class="fa fa-home" href="inicio_ad.php"> Inicio</a></li>
<li><a class="fa fa-pen" href="per_prof.php"> Mi perfil</a></li>
<li><a class="fa fa-graduation-cap" href="calificaciones_menu_prof.php"> Calificaciones</a></li>
<li><a class="fa fa-sign-out-alt" href="include/cerrar.php"> Cerrar Sesión</a></li>
    </ul>
</div>

<?php }} ?>
