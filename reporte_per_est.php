<?php
// Incluir librerías y conexión
require 'tcpdf/tcpdf.php';
include 'include/conexion.php';

// Verificar si se ha enviado el id_usuario por sesión
if (isset($_POST['id_usuario']) && $_POST['id_usuario'] !== '') {
    $id_usuario = $_POST['id_usuario'];
    $anio_actual = date('Y'); // Año actual

    // Consulta para obtener los datos del estudiante
    $sql_estudiante = "SELECT nombre, ci, exp, zona, calle, casa, celular, genero, fecha_nac, nom_ppff, cel_ppff, ci_ppff, ocupacion_ppff 
                       FROM estudiante 
                       WHERE id_usuario = :id_usuario";
    $stmt_estudiante = $conexion->prepare($sql_estudiante);
    $stmt_estudiante->execute([':id_usuario' => $id_usuario]);
    $estudiante = $stmt_estudiante->fetch(PDO::FETCH_ASSOC);

    if ($estudiante) {
        // Obtener la fecha actual
        date_default_timezone_set('America/La_Paz');
        $fecha_actual = date('d-m-Y');

        // Crear el PDF
        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Colegio');
        $pdf->SetTitle('Datos del Estudiante');
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

        // Datos del estudiante
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Datos del Estudiante', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(140, 8, 'Nombre: ' . $estudiante['nombre'], 0, 0, 'L');
        $pdf->Cell(0, 8, 'CI: ' . $estudiante['ci'] . ' ' . $estudiante['exp'], 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);

        // Crear tabla para el resto de la información del estudiante
        $pdf->SetY($pdf->GetY() + 5); // Espacio entre el encabezado y la tabla
        $html = '
            <table border="1" cellpadding="4">
                <tr>
                    <td width="50%"><b>Zona:</b> ' . $estudiante['zona'] . '</td>
                    <td width="50%"><b>Calle:</b> ' . $estudiante['calle'] . '</td>
                </tr>
                <tr>
                    <td><b>Número de Casa:</b> ' . $estudiante['casa'] . '</td>
                    <td><b>Celular:</b> ' . $estudiante['celular'] . '</td>
                </tr>
                <tr>
                    <td><b>Género:</b> ' . $estudiante['genero'] . '</td>
                    <td><b>Fecha de Nacimiento:</b> ' . $estudiante['fecha_nac'] . '</td>
                </tr>
            </table>';

        // Agregar la tabla al PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Datos del Tutor (PPFF)
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Perfil del PPFF/Tutor', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);

        // Crear tabla para la información del tutor
        $html_tutor = '
            <table border="1" cellpadding="4">
                <tr>
                    <td width="50%"><b>Nombre del Tutor:</b> ' . $estudiante['nom_ppff'] . '</td>
                    <td width="50%"><b>CI del Tutor:</b> ' . $estudiante['ci_ppff'] . '</td>
                </tr>
                <tr>
                    <td><b>Celular del Tutor:</b> ' . $estudiante['cel_ppff'] . '</td>
                    <td><b>Ocupación del Tutor:</b> ' . $estudiante['ocupacion_ppff'] . '</td>
                </tr>
            </table>';

        // Agregar la tabla del tutor al PDF
        $pdf->writeHTML($html_tutor, true, false, true, false, '');

        // Salida del PDF
        $pdf->Output('perfil_estudiante.pdf', 'D');
    } else {
        echo "No se encontraron datos para el estudiante con ID de usuario: " . $id_usuario;
    }
}
?>
