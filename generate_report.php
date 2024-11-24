<?php
include 'db_connection.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validación de entrada
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $admin_password = $_POST['admin_password'];

    // Limpiar buffer de salida
    ob_start();

    // Validar contraseña del administrador
    $sql = "SELECT * FROM employees WHERE role = 'Administrador' AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $admin_password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        ob_end_clean(); // Limpiar buffer si la contraseña es incorrecta
        echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    $stmt->close(); // Cerrar la consulta de validación

    // Obtener registros en el rango de fechas
    $sql = "SELECT e.name, a.date, a.time_in, a.time_out 
            FROM attendance a
            INNER JOIN employees e ON a.employee_id = e.id
            WHERE a.date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        ob_end_clean(); // Limpiar buffer si no hay datos
        echo json_encode(['status' => 'error', 'message' => 'No hay datos para el rango de fechas seleccionado.']);
        $stmt->close();
        $conn->close();
        exit;
    }

    // Crear archivo Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Reporte de Asistencia');

    // Encabezados
    $sheet->setCellValue('A1', 'Nombre');
    $sheet->setCellValue('B1', 'Fecha');
    $sheet->setCellValue('C1', 'Hora de Entrada');
    $sheet->setCellValue('D1', 'Hora de Salida');

    // Agregar datos
    $row = 2;
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$row}", $data['name']);
        $sheet->setCellValue("B{$row}", $data['date']);
        $sheet->setCellValue("C{$row}", $data['time_in']);
        $sheet->setCellValue("D{$row}", $data['time_out']);
        $row++;
    }

    // Encabezados para descargar archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_asistencia.xlsx"');
    header('Cache-Control: max-age=0');

    // Enviar archivo al navegador
    $writer = new Xlsx($spreadsheet);

    // Limpiar buffer antes de enviar el archivo
    ob_end_clean();
    $writer->save('php://output');

    $stmt->close();
    $conn->close();
    exit;
}
?>
