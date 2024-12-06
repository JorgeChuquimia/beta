<?php
// Desactivar salida de errores para evitar interferencias con el PDF
error_reporting(E_ALL & ~E_WARNING);

// Incluir librerías y conexión
require 'tcpdf/tcpdf.php';
include 'include/conexion.php';

// Verificar si se han recibido los datos por POST
if (isset($_POST['id_grado'], $_POST['id_paralelo'], $_POST['id_materia'])) {
    $id_grado = $_POST['id_grado'];
    $id_paralelo = $_POST['id_paralelo'];
    $id_materia = $_POST['id_materia'];
    $anio_actual = date('Y'); // Año actual

    // Consultar las calificaciones de los estudiantes en ese grado y paralelo para la materia seleccionada
    $sql_calificaciones = "
    SELECT u.nombre AS estudiante, c.b1, c.b2, c.b3, c.promedio, 
           g.nom_gra, p.nom_par, m.nom_mat
    FROM calificaciones c
    JOIN inscripciones i ON c.id_usuario = i.id_usuario
    JOIN materia m ON c.id_mat = m.id_mat
    JOIN usuario u ON i.id_usuario = u.id_usuario  
    JOIN grado g ON i.id_gra = g.id_gra
    JOIN paralelo p ON i.id_par = p.id_par
    WHERE m.id_mat = :id_materia 
    AND i.id_par = :id_paralelo 
    AND i.id_gra = :id_grado 
    AND YEAR(i.fecha) = :anio_actual
    ORDER BY u.nombre
";

    // Preparar y ejecutar la consulta
    $stmt_calificaciones = $conexion->prepare($sql_calificaciones);
    $stmt_calificaciones->execute([
        ':id_materia' => $id_materia,
        ':id_grado' => $id_grado,
        ':id_paralelo' => $id_paralelo,
        ':anio_actual' => $anio_actual
    ]);

    // Obtener el nombre del grado y paralelo
    $fila = $stmt_calificaciones->fetch(PDO::FETCH_ASSOC);
    $nombre_grado = $fila['nom_gra'];
    $nombre_paralelo = $fila['nom_par'];
    $nombre_materia = $fila['nom_mat'];

    // Reiniciar el cursor de la consulta
    $stmt_calificaciones->execute([
        ':id_materia' => $id_materia,
        ':id_grado' => $id_grado,
        ':id_paralelo' => $id_paralelo,
        ':anio_actual' => $anio_actual
    ]);

    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date('d-m-Y');

    // Crear el PDF
    $pdf = new TCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Colegio Solidaridad');
    $pdf->SetTitle('Calificaciones por Materia');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetPrintHeader(false);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Agregar imágenes al PDF (evitar errores con @ para suprimir advertencias)
    @$pdf->Image('img/logo.png', 235, 18, 25, '', 'PNG');
    @$pdf->Image('img/logo.png', 20, 18, 25, '', 'PNG');

    // Encabezado del PDF
    $pdf->SetY(20);
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->Cell(260, 10, 'Unidad Educativa Irene Nava de Castillo', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 15);
    $pdf->Cell(260, 10, 'Reporte de Calificaciones', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(260, 10, 'Fecha del Reporte: ' . $fecha_actual, 0, 1, 'C');
    $pdf->SetY(50);

    // Mostrar grado, paralelo y materia
    $pdf->SetFont('helvetica', 'B', 14);
    
$pdf->SetX(20); // Ajustar la posición X aquí
$pdf->Cell(70, 10, 'Curso:', 0, 0, 'L');
$pdf->SetX(45);
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(200, 10, $nombre_grado . ' ' . $nombre_paralelo, 0, 1, 'L');

$pdf->SetFont('helvetica', 'B', 14);
$pdf->SetX(20); // Ajustar la posición X aquí
$pdf->Cell(70, 10, 'Materia:', 0, 0, 'L');
$pdf->SetX(45);
$pdf->SetFont('helvetica', '', 14);
$pdf->Cell(200, 10, $nombre_materia, 0, 1, 'L');

    $pdf->SetFont('helvetica', '', 10);
    // Tabla de calificaciones
    $pdf->SetY(75); // Ajustar Y si es necesario
    $html .= '
        <table border="1" align="center" cellpadding="4">
            <tr>
                <th><b>Estudiante</b></th>
                <th><b>Bimestre 1</b></th>
                <th><b>Bimestre 2</b></th>
                <th><b>Bimestre 3</b></th>
                <th><b>Promedio Final</b></th>
            </tr>';

    while ($fila = $stmt_calificaciones->fetch(PDO::FETCH_ASSOC)) {
        $html .= '
            <tr>
                <td>' . htmlspecialchars($fila['estudiante']) . '</td>
                <td>' . htmlspecialchars($fila['b1']) . '</td>
                <td>' . htmlspecialchars($fila['b2']) . '</td>
                <td>' . htmlspecialchars($fila['b3']) . '</td>
                <td>' . htmlspecialchars($fila['promedio']) . '</td>
            </tr>';
    }

    $html .= '</table>';
    $pdf->writeHTML($html, true, false, true, false, '');

    // Salida del PDF
    $pdf->Output('calificaciones.pdf', 'I');
} else {
    echo '<h4 class="alert-warning">Error: No se recibieron los parámetros necesarios.</h4>';
}
?>
