<?php
// Incluir librerías y conexión
require 'tcpdf/tcpdf.php';
include 'include/conexion.php';

// Verificar si se ha enviado el grado y paralelo por GET
if (isset($_GET['grado']) && isset($_GET['paralelo'])) {
    $grado = urldecode($_GET['grado']);
    $paralelo = urldecode($_GET['paralelo']);
    date_default_timezone_set('America/La_Paz');
    $fecha_actual = date('d-m-Y');
    $anio_actual = date('Y');

    // Crear el PDF
    $pdf = new TCPDF('L', 'mm', 'LETTER', true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Colegio');
    $pdf->SetTitle('Lista de Estudiantes Inscritos');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetPrintHeader(false);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    // Agregar imágenes al PDF
    @$pdf->Image('img/logo.png', 235, 18, 25, '', 'PNG');
    @$pdf->Image('img/logo.png', 20, 18, 25, '', 'PNG');

    // Encabezado del PDF
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetY(40);
    $pdf->Cell(260, 10, 'Unidad Educativa Irene Nava de Castillo', 0, 1, 'C');
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(260, 10, "Fecha del Reporte: $fecha_actual", 0, 1, 'C');
    $pdf->Cell(260, 10, "$grado - Paralelo: $paralelo", 0, 1, 'C');

    // Espacio entre encabezado y tabla
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(10, 10, '#', 1, 0, 'C');
    $pdf->Cell(150, 10, 'Nombre del Estudiante', 1, 0, 'C');
    $pdf->Cell(70, 10, 'Fecha de Inscripción', 1, 1, 'C');

    // Consultar la lista de estudiantes inscritos en el grado y paralelo especificado
    $sql = "SELECT u.nombre, i.fecha 
            FROM inscripciones i
            JOIN usuario u ON i.id_usuario = u.id_usuario
            JOIN grado g ON i.id_gra = g.id_gra
            JOIN paralelo p ON i.id_par = p.id_par
            WHERE YEAR(i.fecha) = ? AND g.nom_gra = ? AND p.nom_par = ?
            ORDER BY u.nombre DESC";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $anio_actual, $grado, $paralelo); // "i" para el año, "s" para grado y paralelo
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si hay estudiantes inscritos
    if ($result->num_rows > 0) {
        $contador = 1;
        $pdf->SetFont('helvetica', '', 10);
        
        // Llenar la tabla con los datos de los estudiantes
        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(10, 10, $contador++, 1, 0, 'C');
            $pdf->Cell(150, 10, $row['nombre'], 1, 0, 'L');
            $pdf->Cell(70, 10, date('d-m-Y', strtotime($row['fecha'])), 1, 1, 'C');
        }
    } else {
        $pdf->Cell(260, 10, 'No hay estudiantes inscritos en este grado y paralelo.', 1, 1, 'C');
    }

    // Cerrar conexión y salida del PDF
    $conn->close();
    $pdf->Output('lista_estudiantes_inscritos.pdf', 'I');
} else {
    echo "Grado o paralelo no especificado.";
}
?>
