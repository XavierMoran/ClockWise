-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-11-2024 a las 05:11:58
-- Versión del servidor: 9.1.0
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clockwise_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attendance`
--

CREATE TABLE `attendance` (
  `employee_id` int NOT NULL,
  `employee_name` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `attendance`
--

INSERT INTO `attendance` (`employee_id`, `employee_name`, `date`, `time_in`, `time_out`) VALUES
(1, 'Ana Martínez', '2024-11-16', '08:40:42', '08:41:19'),
(2, 'José Pérez', '2024-11-16', '08:40:48', '08:41:27'),
(3, 'María González', '2024-11-16', '08:40:54', '08:41:33'),
(4, 'Carlos López', '2024-11-16', '08:41:01', '08:41:42'),
(5, 'Fernanda Torres', '2024-11-16', '08:41:08', '08:41:59'),
(1, 'Ana Martínez', '2024-11-16', '08:57:45', '08:57:58'),
(4, 'Carlos López', '2024-11-16', '09:43:13', '09:43:24'),
(5, 'Fernanda Torres', '2024-11-16', '09:46:01', '09:46:08'),
(1, 'Ana Martínez', '2024-11-21', '01:43:24', '01:43:49'),
(1, 'Ana Martínez', '2024-11-22', '06:02:08', '06:02:56'),
(2, 'José Pérez', '2024-11-22', '06:02:46', '06:03:04'),
(1, 'Ana Martínez', '2024-11-23', '05:45:54', '05:46:21'),
(1, 'Ana Martínez', '2024-11-23', '05:47:01', '05:48:10'),
(9, 'Daniela Castro', '2024-11-23', '05:58:52', '05:58:58'),
(3, 'María González', '2024-11-23', '06:40:32', '06:40:42'),
(10, 'Andrés Gutiérrez', '2024-11-24', '23:27:47', '23:28:53'),
(3, 'María González', '2024-11-24', '23:51:47', '23:52:26'),
(2, 'José Pérez', '2024-11-24', '23:52:17', '23:52:33'),
(1, 'Ana Martínez', '2024-11-25', '01:18:50', '01:18:56'),
(1, 'Ana Martínez', '2024-11-25', '02:26:00', '02:26:06'),
(1, 'Ana Martínez', '2024-11-25', '02:33:49', '02:34:15'),
(2, 'José Pérez', '2024-11-25', '03:00:12', '03:00:52'),
(7, 'Sofía Hernández', '2024-11-25', '03:58:39', '03:58:45'),
(2, 'José Pérez', '2024-11-25', '04:37:48', '04:39:33'),
(5, 'Fernanda Torres', '2024-11-25', '04:46:00', '04:46:15'),
(4, 'Carlos López', '2024-11-25', '04:48:16', '04:48:48'),
(4, 'Carlos López', '2024-11-25', '04:48:30', '04:48:48'),
(3, 'María González', '2024-11-24', '21:57:26', '21:57:42');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `employees`
--

CREATE TABLE `employees` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `employees`
--

INSERT INTO `employees` (`id`, `name`, `role`, `password`) VALUES
(1, 'Ana Martínez', 'Administrador', 'Ana12345'),
(2, 'José Pérez', 'Empleado', 'Jose12345'),
(3, 'María González', 'Empleado', 'Maria12345'),
(4, 'Carlos López', 'Empleado', 'Carlos12345'),
(5, 'Fernanda Torres', 'Empleado', 'Fernanda12345'),
(6, 'Alejandro Ramírez', 'Empleado', 'Alejandro12345'),
(7, 'Sofía Hernández', 'Empleado', 'Sofia12345'),
(8, 'Luis Mendoza', 'Empleado', 'Luis12345'),
(9, 'Daniela Castro', 'Empleado', 'Daniela12345'),
(10, 'Andrés Gutiérrez', 'Empleado', 'Andres12345'),
(11, 'Xavier Moran', 'Administrador', 'Admin12345');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `attendance`
--
ALTER TABLE `attendance`
  ADD KEY `employee_id` (`employee_id`);

--
-- Indices de la tabla `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
