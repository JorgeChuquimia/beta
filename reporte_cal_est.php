<?php
// Desactivar salida de errores para evitar interferencias con el PDF
error_reporting(E_ALL & ~E_WARNING);

// Incluir librerías y conexión
require 'tcpdf/tcpdf.php';
include 'include/conexion.php';

// Verificar si se ha enviado el id_usuario por GET
if (isset($_GET['id_usuario']) && $_GET['id_usuario'] !== '') {
    $id_usuario = $_GET['id_usuario'];
    $anio_actual = date('Y'); // Año actual

    // Consultar las calificaciones del estudiante
    $sql_calificaciones = "
        SELECT c.id_mat, m.nom_mat, c.b1, c.b2, c.b3, c.promedio 
        FROM calificaciones c
        JOIN materia m ON c.id_mat = m.id_mat
        WHERE c.id_usuario = :id_usuario AND c.anio = :anio_actual
        ORDER BY m.nom_mat
    ";

    // Consultar los datos del estudiante
    $sql_estudiante = "
        SELECT u.nombre, u.foto
        FROM usuario u
        WHERE u.id_usuario = :id_usuario
    ";

    // Preparar y ejecutar consultas
    $stmt_estudiante = $conexion->prepare($sql_estudiante);
    $stmt_estudiante->execute([':id_usuario' => $id_usuario]);
    $estudiante = $stmt_estudiante->fetch(PDO::FETCH_ASSOC);

    $stmt_calificaciones = $conexion->prepare($sql_calificaciones);
    $stmt_calificaciones->execute([':id_usuario' => $id_usuario, ':anio_actual' => $anio_actual]);

    if ($estudiante) {
        $nombre = $estudiante['nombre'];
        $foto = $estudiante['foto'];
        date_default_timezone_set('America/La_Paz');
        $fecha_actual = date('d-m-Y');

        // Crear el PDF
        $pdf = new TCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Colegio Solidaridad');
        $pdf->SetTitle('Calificaciones del Estudiante');
        $pdf->SetMargins(10, 10, 10);
        $pdf->SetPrintHeader(false);
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();

        // Agregar imágenes al PDF (evitar errores con @ para suprimir advertencias)
        @$pdf->Image('img/logo.png', 235, 18, 25, '', 'PNG');
        @$pdf->Image('img/logo.png', 20, 18, 25, '', 'PNG');

        if ($foto) {
            @$pdf->Image($foto, 15, 50, 30, 30, '', '', '', true);
        }

        // Encabezado del PDF
        $pdf->SetFont('helvetica', 'B', 20);
        $pdf->SetY(40);
        $pdf->Cell(260, 10, 'Unidad Educativa Irene Nava de Castillo', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(260, 10, 'Fecha del Reporte: ' . $fecha_actual, 0, 1, 'C');

        // Datos del estudiante
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->SetY(80);
        $pdf->Cell(70, 10, 'Estudiante:', 0, 0, 'L');
        $pdf->SetFont('helvetica', '', 14);
        $pdf->Cell(200, 10, $nombre, 0, 1, 'L');

        // Tabla de calificaciones
        $pdf->SetY(100);
        $html = '<h5>Boletín de Calificaciones</h5>';
        $html .= '
            <table border="1" align="center">
                <tr>
                    <th colspan="2" align="left"><b>Materia</b></th>
                    <th><b>Bimestre 1</b></th>
                    <th><b>Bimestre 2</b></th>
                    <th><b>Bimestre 3</b></th>
                    <th><b>Promedio Final</b></th>
                </tr>';

        while ($fila = $stmt_calificaciones->fetch(PDO::FETCH_ASSOC)) {
            $html .= '
                <tr>
                    <td colspan="2" align="left">' . $fila['nom_mat'] . '</td>
                    <td>' . $fila['b1'] . '</td>
                    <td>' . $fila['b2'] . '</td>
                    <td>' . $fila['b3'] . '</td>
                    <td>' . $fila['promedio'] . '</td>
                </tr>';
        }

        $html .= '</table>';
        $pdf->writeHTML($html, true, false, true, false, '');

        // Salida del PDF
        $pdf->Output('calificaciones.pdf', 'I');
    } else {
        echo '<h4 class="alert-warning">No se encontraron datos para este estudiante.</h4>';
    }
} else {
    echo '<h4 class="alert-warning">No se encontró el estudiante o hay un error en la solicitud.</h4>';
}

?>
