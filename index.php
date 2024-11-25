<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ClockWise - Registro de Asistencia</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Registro de Asistencia</h1>
        <form id="attendanceForm">
            <label for="employee">Selecciona tu nombre:</label>
            <select id="employee" name="employee" required>
                <option value="">--Seleccionar--</option>
                <?php
                include 'db_connection.php';
                $result = $conn->query("SELECT id, name FROM employees");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                }
                ?>
            </select>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="button" id="btnEntrada">Registrar Entrada</button>
            <button type="button" id="btnSalida">Registrar Salida</button>
            <div id="message" class="message"></div>
        </form>

        <div class="report-form">
            <h3>Generar Reporte</h3>
            <label for="start_date">Desde:</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">Hasta:</label>
            <input type="date" id="end_date" name="end_date" required>

            <label for="admin_password">Contraseña del Administrador:</label>
            <input type="password" id="admin_password" name="admin_password" required>
            <button id="generateReportBtn">Generar Reporte</button>
            
            <div id="reportMessage" class="message"></div>
        </div>

    </div>
    <script src="script.js"></script>
</body>
</html>
