<?php
include 'sesion.php';
include 'conexion.php';

// Definir credenciales manualmente
$username = 'root';
$password = ''; // Cambia esta línea si tienes una contraseña configurada
$host = 'localhost';

// Nombre de la base de datos
$db_name = 'db_irenenava';
date_default_timezone_set('America/La_Paz');
$backup_file = sys_get_temp_dir() . '/' . $db_name . '_' . date("d-m-Y_H-i-s") . '.sql';

//$command = "mysqldump --user={$username} --password={$password} --host={$host} --databases {$db_name} --single-transaction --quick --lock-tables=false > {$backup_file}";--- para servidor

$command = "C:\\xampp\\mysql\\bin\\mysqldump --user={$username} --password={$password} --host={$host} --databases {$db_name} --single-transaction --quick --lock-tables=false > {$backup_file} 2>&1";
exec($command, $output, $return_var);

if ($return_var !== 0) {
    echo "Error al generar la copia de seguridad. Código de error: $return_var";
    print_r($output); // Muestra los errores generados por mysqldump
    exit;
}


// Forzar la descarga del archivo
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . basename($backup_file) . '"');
header('Content-Length: ' . filesize($backup_file));

// Leer el archivo y enviarlo al navegador
readfile($backup_file);

// Eliminar el archivo después de la descarga
unlink($backup_file);

exit;
?>


