<?php
include 'db_connection.php';
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $admin_password = $_POST['admin_password'] ?? null;

    if (!$start_date || !$end_date || !$admin_password) {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos.']);
        exit;
    }

    // Validar contraseña del administrador
    $sql = "SELECT password FROM employees WHERE role = 'Administrador' AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $admin_password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        $stmt->close(); // Cierra el statement
        $conn->close(); // Cierra la conexión
        exit; // Detiene la ejecución
    }
    $stmt->close();


    // Obtener los registros en el rango de fechas
    $sql = "SELECT e.name, a.date, a.time_in, a.time_out 
            FROM attendance a
            INNER JOIN employees e ON a.employee_id = e.id
            WHERE a.date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
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

    // Datos
    $row = 2;
    while ($data = $result->fetch_assoc()) {
        $sheet->setCellValue("A{$row}", $data['name']);
        $sheet->setCellValue("B{$row}", $data['date']);
        $sheet->setCellValue("C{$row}", $data['time_in']);
        $sheet->setCellValue("D{$row}", $data['time_out']);
        $row++;
    }

    // Guardar archivo
    $filename = 'reporte_asistencia_' . date('Ymd_His') . '.xlsx';
    $filePath = "reports/{$filename}";
    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    echo json_encode(['status' => 'success', 'file' => $filePath]);
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
$conn->close();
?>
