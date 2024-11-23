<?php
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $password = $_POST['password'];
    $action = $_POST['action'];

    // Validar contraseña y obtener el nombre del empleado
    $sql = "SELECT name, password FROM employees WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $employee_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($employee_name, $db_password);
        $stmt->fetch();

        if ($password === $db_password) { // Cambiar a hash_equals si usas contraseñas encriptadas
            $date = date('Y-m-d');
            $time = date('H:i:s');

            if ($action === 'entrada') {
                // Registrar hora de entrada
                $sql = "INSERT INTO attendance (employee_id, employee_name, date, time_in) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isss', $employee_id, $employee_name, $date, $time);
            } elseif ($action === 'salida') {
                // Verificar si ya hay una entrada registrada para el empleado
                $check_sql = "SELECT time_in FROM attendance WHERE employee_id = ? AND date = ? AND time_out IS NULL";
                $check_stmt = $conn->prepare($check_sql);
                $check_stmt->bind_param('is', $employee_id, $date);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    // Registrar hora de salida
                    $sql = "UPDATE attendance SET time_out = ? WHERE employee_id = ? AND date = ? AND time_out IS NULL";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sis', $time, $employee_id, $date);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'No se puede registrar la salida sin una entrada previa.']);
                    exit;
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Acción no válida.']);
                exit;
            }

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Registro actualizado correctamente.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el registro.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Contraseña incorrecta.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Empleado no encontrado.']);
    }
}

$stmt->close();
$conn->close();
?>
