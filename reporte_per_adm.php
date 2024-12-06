<?php
// Incluir librerías y conexión
require 'tcpdf/tcpdf.php';
include 'include/conexion.php';

// Verificar si se ha enviado el id_usuario por sesión
if (isset($_POST['id_usuario']) && $_POST['id_usuario'] !== '') {
$id_usuario = $_POST['id_usuario'];
$anio_actual = date('Y'); // Año actual

// Consulta para obtener los datos del administrador
$sql_administrador = "SELECT nombre, ci, exp, zona, calle, casa, celular, genero, profesion, categoria, antiguedad, fecha_nac 
                      FROM administrador 
                      WHERE id_usuario = :id_usuario";
$stmt_administrador = $conexion->prepare($sql_administrador);
$stmt_administrador->execute([':id_usuario' => $id_usuario]);
$administrador = $stmt_administrador->fetch(PDO::FETCH_ASSOC);

if ($administrador) {
    // Obtener la fecha actual
    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date('d-m-Y');

    // Crear el PDF
    $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false); // Orientación vertical (P)
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Colegio');
    $pdf->SetTitle('Datos del Administrador');
    $pdf->SetMargins(20, 20, 20);
    $pdf->SetPrintHeader(false);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Agregar imágenes al PDF
    @$pdf->Image('img/logo.png', 170, 10, 26, '', 'PNG');
    @$pdf->Image('img/logo.png', 20, 10, 26, '', 'PNG');

    // Encabezado del PDF
    $pdf->SetFont('helvetica', 'B', 17);
    $pdf->Cell(0, 10, 'Unidad Educativa Irene Nava de Castillo', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Fecha del Reporte: ' . $fecha_actual, 0, 1, 'C');
    $pdf->Ln(10);

    // Datos del administrador
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Datos del Administrador', 0, 1, 'L');
    $pdf->SetFont('helvetica', '', 12);
$pdf->Cell(140, 8, 'Nombre: ' . $administrador['nombre'], 0, 0, 'L');
$pdf->Cell(0, 8, 'CI: ' . $administrador['ci'] . ' ' . $administrador['exp'], 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
// Crear tabla para el resto de la información
$pdf->SetY($pdf->GetY() + 5); // Espacio entre el encabezado y la tabla
$html = '
    <table border="1" cellpadding="4">
        <tr>
            <td width="50%"><b>Zona:</b> ' . $administrador['zona'] . '</td>
            <td width="50%"><b>Calle:</b> ' . $administrador['calle'] . '</td>
        </tr>
        <tr>
            <td><b>Número de Casa:</b> ' . $administrador['casa'] . '</td>
            <td><b>Celular:</b> ' . $administrador['celular'] . '</td>
        </tr>
        <tr>
            <td><b>Género:</b> ' . $administrador['genero'] . '</td>
            <td><b>Profesión:</b> ' . $administrador['profesion'] . '</td>
        </tr>
        <tr>
            <td><b>Categoría:</b> ' . $administrador['categoria'] . '</td>
            <td><b>Antigüedad:</b> ' . $administrador['antiguedad'] . '</td>
        </tr>
        <tr>
            <td colspan="2"><b>Fecha de Nacimiento:</b> ' . $administrador['fecha_nac'] . '</td>
        </tr>
    </table>';

// Agregar la tabla al PDF
$pdf->writeHTML($html, true, false, true, false, '');
    // Salida del PDF
    $pdf->Output('perfil_administrador.pdf', 'D');
} else {
    echo "No se encontraron datos para el administrador con ID de usuario: " . $id_usuario;
}
}
?>
