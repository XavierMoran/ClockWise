<?php

// Establecer la zona horaria al inicio del archivo
date_default_timezone_set('America/Mexico_City'); // Cambia esto a tu zona horaria

// Ejemplo de uso de date() o time() después de configurar la zona horaria
echo "La fecha y hora actual es: " . date('Y-m-d H:i:s');

// get_attendance.php
include 'db_connection.php';

// Verificar que se haya recibido una solicitud GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $employee_id = $_GET['employee_id'];

    if (!empty($employee_id)) {
        // Consultar los registros de asistencia
        $sql = "SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $employee_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $attendance_records = [];

            while ($row = $result->fetch_assoc()) {
                $attendance_records[] = $row;
            }

            echo json_encode(['status' => 'success', 'data' => $attendance_records]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al recuperar los registros de asistencia']);
        }

        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'ID de empleado no proporcionado']);
    }
}

$conn->close();
?>
