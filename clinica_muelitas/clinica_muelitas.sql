-- phpMyAdmin SQL Dump
-- version 4.4.14
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-02-2017 a las 02:04:38
-- Versión del servidor: 5.6.26
-- Versión de PHP: 5.6.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clinica_muelitas`
--
CREATE DATABASE IF NOT EXISTS `clinica_muelitas` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `clinica_muelitas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE IF NOT EXISTS `citas` (
  `CitNumero` int(11) NOT NULL,
  `CitFecha` date NOT NULL,
  `CitHora` time NOT NULL,
  `CitPaciente` varchar(20) NOT NULL,
  `CitMedico` varchar(20) NOT NULL,
  `CitConsultorio` int(11) NOT NULL,
  `CitEstado` varchar(20) NOT NULL,
  `CitObservaciones` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`CitNumero`, `CitFecha`, `CitHora`, `CitPaciente`, `CitMedico`, `CitConsultorio`, `CitEstado`, `CitObservaciones`) VALUES
(2, '2016-12-12', '08:00:00', '1', '1', 2, 'solicitada', 'ninguna'),
(3, '2016-12-12', '08:20:00', '1', '1', 2, 'solicitada', 'ninguna');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consultorios`
--

CREATE TABLE IF NOT EXISTS `consultorios` (
  `ConNumero` int(11) NOT NULL,
  `ConNombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `consultorios`
--

INSERT INTO `consultorios` (`ConNumero`, `ConNombre`) VALUES
(2, 'cirujia dental');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horas`
--

CREATE TABLE IF NOT EXISTS `horas` (
  `hora` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `horas`
--

INSERT INTO `horas` (`hora`) VALUES
('08:00:00'),
('08:20:00'),
('08:40:00'),
('09:00:00'),
('09:20:00'),
('09:40:00'),
('10:00:00'),
('10:20:00'),
('10:40:00'),
('11:00:00'),
('11:20:00'),
('11:40:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicos`
--

CREATE TABLE IF NOT EXISTS `medicos` (
  `MedIdentificacion` varchar(20) NOT NULL,
  `MedNombres` varchar(50) NOT NULL,
  `MedApellidos` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `medicos`
--

INSERT INTO `medicos` (`MedIdentificacion`, `MedNombres`, `MedApellidos`) VALUES
('1', 'Jose', 'López');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE IF NOT EXISTS `pacientes` (
  `PacIdentificacion` varchar(20) NOT NULL,
  `PacNombres` varchar(50) NOT NULL,
  `PacApellidos` varchar(50) NOT NULL,
  `PacFechaNacimiento` date NOT NULL,
  `PacSexo` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`PacIdentificacion`, `PacNombres`, `PacApellidos`, `PacFechaNacimiento`, `PacSexo`) VALUES
('1', 'leo', 'river', '2015-11-11', 'F');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tratamientos`
--

CREATE TABLE IF NOT EXISTS `tratamientos` (
  `TraNumero` int(10) unsigned NOT NULL,
  `TraFechaAsignado` date NOT NULL,
  `TraDescripcion` text NOT NULL,
  `TraFechaInicio` date NOT NULL,
  `TraFechaFin` date NOT NULL,
  `TraObservaciones` text NOT NULL,
  `TraPaciente` varchar(20) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tratamientos`
--

INSERT INTO `tratamientos` (`TraNumero`, `TraFechaAsignado`, `TraDescripcion`, `TraFechaInicio`, `TraFechaFin`, `TraObservaciones`, `TraPaciente`) VALUES
(1, '2015-10-29', 'Tratamiento para las muelas cordales', '2015-10-29', '2015-11-27', 'Finalizo muy bien', 'Manuel'),
(2, '2015-10-29', 'Tratamiento para las muelas cordales', '2015-10-29', '2015-11-27', 'Finalizo muy bien', 'Manuel');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`CitNumero`),
  ADD KEY `CitMedico` (`CitMedico`),
  ADD KEY `CitConsultorio` (`CitConsultorio`),
  ADD KEY `CitPaciente` (`CitPaciente`);

--
-- Indices de la tabla `consultorios`
--
ALTER TABLE `consultorios`
  ADD PRIMARY KEY (`ConNumero`);

--
-- Indices de la tabla `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`MedIdentificacion`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`PacIdentificacion`);

--
-- Indices de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  ADD PRIMARY KEY (`TraNumero`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `CitNumero` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT de la tabla `tratamientos`
--
ALTER TABLE `tratamientos`
  MODIFY `TraNumero` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`CitMedico`) REFERENCES `medicos` (`MedIdentificacion`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`CitConsultorio`) REFERENCES `consultorios` (`ConNumero`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`CitPaciente`) REFERENCES `pacientes` (`PacIdentificacion`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
