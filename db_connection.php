<?php
// db_connection.php

$servername = "localhost";
$username = "root"; // Reemplaza con tu usuario de MySQL si es diferente
$password = "Xavier22."; // Reemplaza con tu contraseña de MySQL
$dbname = "clockwise_db";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Configurar la zona horaria a El Salvador
date_default_timezone_set('America/El_Salvador');

// Mensaje opcional de conexión exitosa
// echo "Conexión exitosa a la base de datos";
?>
