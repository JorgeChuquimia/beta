<?php
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    $sql = "SELECT foto FROM usuario WHERE usuario = ?";
    
    // Inicializa la conexión solo cuando es necesario.
    $conn = new mysqli('localhost', 'root', '', 'db_irenenava');
    
    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }
    
    // Prepara y ejecuta la consulta.
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $fotoUsuario = 'img/user.png';  // Establece una imagen predeterminada por defecto.
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fotoUsuario = $row['foto'];
    }
    
    // Cierra la conexión de la base de datos.
    $stmt->close();
    $conn->close();
    
    // Guarda el resultado en una variable de sesión.
    $_SESSION['foto_usuario'] = $fotoUsuario;
}
?>


