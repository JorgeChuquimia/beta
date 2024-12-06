<?php
// Incluir librerías y conexión
require 'tcpdf/tcpdf.php';
include 'include/conexion.php';

// Verificar si se ha enviado el id_usuario por sesión
if (isset($_POST['id_usuario']) && $_POST['id_usuario'] !== '') {
    $id_usuario = $_POST['id_usuario'];
    $anio_actual = date('Y'); // Año actual

    // Consulta para obtener los datos del profesor
    $sql_profesor = "SELECT id_prof, nombre, ci, exp, zona, calle, casa, celular, genero, fecha_nac, esp_egreso, cat_gestion, categoria, anio_ser, rda_prof 
                     FROM profesor 
                     WHERE id_usuario = :id_usuario";
    $stmt_profesor = $conexion->prepare($sql_profesor);
    $stmt_profesor->execute([':id_usuario' => $id_usuario]);
    $profesor = $stmt_profesor->fetch(PDO::FETCH_ASSOC);

    if ($profesor) {
        // Obtener el id_prof del profesor
        $id_prof = $profesor['id_prof'];

        // Obtener la fecha actual
        date_default_timezone_set('America/La_Paz');
        $fecha_actual = date('d-m-Y');

        // Crear el PDF
        $pdf = new TCPDF('P', 'mm', 'LETTER', true, 'UTF-8', false); // Orientación vertical (P)
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Colegio');
        $pdf->SetTitle('Datos del Profesor');
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

        // Datos del profesor
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Datos del Profesor', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(140, 8, 'Nombre: ' . $profesor['nombre'], 0, 0, 'L');
        $pdf->Cell(0, 8, 'CI: ' . $profesor['ci'] . ' ' . $profesor['exp'], 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);

        // Crear tabla para el resto de la información
        $pdf->SetY($pdf->GetY() + 5); // Espacio entre el encabezado y la tabla
        $html = '
            <table border="1" cellpadding="4">
                <tr>
                    <td width="50%"><b>Zona:</b> ' . $profesor['zona'] . '</td>
                    <td width="50%"><b>Calle:</b> ' . $profesor['calle'] . '</td>
                </tr>
                <tr>
                    <td><b>Número de Casa:</b> ' . $profesor['casa'] . '</td>
                    <td><b>Celular:</b> ' . $profesor['celular'] . '</td>
                </tr>
                <tr>
                    <td><b>Género:</b> ' . $profesor['genero'] . '</td>
                    <td><b>Especialidad de Egreso:</b> ' . $profesor['esp_egreso'] . '</td>
                </tr>
                <tr>
                    <td><b>Categoría de Gestión:</b> ' . $profesor['cat_gestion'] . '</td>
                    <td><b>Categoría:</b> ' . $profesor['categoria'] . '</td>
                </tr>
                <tr>
                    <td><b>Año de Servicio:</b> ' . $profesor['anio_ser'] . '</td>
                    <td><b>RDA Profesor:</b> ' . $profesor['rda_prof'] . '</td>
                </tr>
                <tr>
                    <td colspan="2"><b>Fecha de Nacimiento:</b> ' . $profesor['fecha_nac'] . '</td>
                </tr>
            </table>';

        // Agregar la tabla al PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Consulta para obtener los títulos del profesor
        $sql_titulos = "SELECT nom_tit, institucion, anio_obtencion 
                        FROM titulos 
                        WHERE id_prof = :id_prof";
        $stmt_titulos = $conexion->prepare($sql_titulos);
        $stmt_titulos->execute([':id_prof' => $id_prof]);

        // Agregar títulos al PDF
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Títulos del Profesor', 0, 1, 'L');
        $pdf->SetFont('helvetica', '', 10);

        $html_titulos = '<table border="1" cellpadding="4">
            <tr>
                <th><b>Título</b></th>
                <th><b>Institución</b></th>
                <th><b>Año de Obtención</b></th>
            </tr>';

        while ($titulo = $stmt_titulos->fetch(PDO::FETCH_ASSOC)) {
            $html_titulos .= '
            <tr>
                <td>' . $titulo['nom_tit'] . '</td>
                <td>' . $titulo['institucion'] . '</td>
                <td>' . $titulo['anio_obtencion'] . '</td>
            </tr>';
        }

        $html_titulos .= '</table>';
        $pdf->writeHTML($html_titulos, true, false, true, false, '');

        // Salida del PDF
        $pdf->Output('perfil_profesor.pdf', 'D');
    } else {
        echo "No se encontraron datos para el profesor con DD de usuario: " . $id_usuario;
    }
}
?>
