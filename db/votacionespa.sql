-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 24-09-2014 a las 16:40:06
-- Versión del servidor: 5.5.38-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `votacionespa`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asuntos_diputados`
--

CREATE TABLE IF NOT EXISTS `asuntos_diputados` (
  `asuntoId` int(11) NOT NULL AUTO_INCREMENT,
  `sesion` varchar(255) NOT NULL,
  `asunto` text NOT NULL,
  `ano` int(4) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `base` varchar(255) NOT NULL,
  `mayoria` varchar(255) NOT NULL,
  `resultado` varchar(255) NOT NULL,
  `presidente` varchar(255) NOT NULL,
  `presentes` int(11) NOT NULL,
  `ausentes` int(11) NOT NULL,
  `abstenciones` int(11) NOT NULL,
  `afirmativos` int(11) NOT NULL,
  `negativos` int(11) NOT NULL,
  `votopresidente` varchar(255) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  PRIMARY KEY (`asuntoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=49 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bloques_diputados`
--

CREATE TABLE IF NOT EXISTS `bloques_diputados` (
  `bloqueId` int(11) NOT NULL,
  `bloque` varchar(50) NOT NULL,
  `color` varchar(50) NOT NULL,
  PRIMARY KEY (`bloqueId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `bloques_diputados`
--

INSERT INTO `bloques_diputados` (`bloqueId`, `bloque`, `color`) VALUES
(1, 'ANR', '#FF0000'),
(2, 'PLRA', '#0000FF'),
(3, 'AP', '#FFFFFF'),
(4, 'IND', '#FF00D3'),
(5, 'FG', '#008000'),
(6, 'PEN', '#FFFF00'),
(7, 'PCH', '#3e799a');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `diputados`
--

CREATE TABLE IF NOT EXISTS `diputados` (
  `diputadoId` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `distrito` varchar(255) NOT NULL,
  `bloqueId` int(11) NOT NULL,
  PRIMARY KEY (`diputadoId`),
  KEY `fk_diputados_1_idx` (`bloqueId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `diputados`
--

INSERT INTO `diputados` (`diputadoId`, `nombre`, `distrito`, `bloqueId`) VALUES
(1, 'Cynthia Tarragó', 'Capital', 1),
(2, 'Dany Durand', 'Capital', 1),
(3, 'Fabiola Oviedo', 'Capital', 1),
(4, 'Juan Félix Bogado', 'Capital', 2),
(5, 'Julio Ríos Bogado', 'Capital', 1),
(6, 'Karina Rodríguez', 'Capital', 3),
(7, 'Olga Ferreira', 'Capital', 4),
(8, 'Oscar Tuma', 'Capital', 1),
(9, 'Alsimio Casco', 'Concepción', 1),
(10, 'Bernardo Villalba', 'Concepción', 1),
(11, 'Mirta Mendoza', 'Concepción', 2),
(12, 'Carlos Maggi', 'San Pedro', 1),
(13, 'Freddy D''Ecclesiss', 'San Pedro', 1),
(14, 'José Gregorio Ledesma', 'San Pedro', 2),
(15, 'Pastor Vera Bejarano', 'San Pedro', 2),
(16, 'Perla A. de Vázquez', 'San Pedro', 4),
(17, 'Amado Florentín', 'Cordillera', 2),
(18, 'Nazario Rojas', 'Cordillera', 1),
(19, 'Pedro Duré', 'Cordillera', 2),
(20, 'Víctor González S.', 'Cordillera', 1),
(21, 'Eusebio Alvarenga', 'Guairá', 2),
(22, 'Félix Ortellado', 'Guairá', 1),
(23, 'Pedro Britos', 'Guairá', 1),
(24, 'Celso Kennedy', 'Caaguazú', 2),
(25, 'Eber Ovelar', 'Caaguazú', 1),
(26, 'E. Antonio Buzarquis', 'Caaguazú', 2),
(27, 'Esmérita Sánchez', 'Caaguazú', 2),
(28, 'Mario Soto', 'Caaguazú', 1),
(29, 'Miguel Del Puerto', 'Caaguazú', 1),
(30, 'Celso Troche', 'Caazapá', 1),
(31, 'Olimpio Rojas', 'Caazapá', 2),
(32, 'Edgar Ortíz R.', 'Itapúa', 2),
(33, 'Horacio Carísimo', 'Itapúa', 2),
(34, 'Luis Larré', 'Itapúa', 1),
(35, 'Mario Cáceres', 'Itapúa', 1),
(36, 'Ramón Duarte', 'Itapúa', 5),
(37, 'Walter Harms', 'Itapúa', 1),
(38, 'Asa González', 'Misiones', 2),
(39, 'Pablino Rodríguez', 'Misiones', 1),
(40, 'Clemente Barrios M.', 'Paraguarí', 1),
(41, 'Jorge Baruja F.', 'Paraguarí', 1),
(42, 'Jorge Avalos M.', 'Paraguarí', 2),
(43, 'Tomás Rivas', 'Paraguarí', 1),
(44, 'Andrés Retamozo', 'Alto Paraná', 1),
(45, 'Gustavo Cardozo', 'Alto Paraná', 2),
(46, 'Blanca Vargas de Caballero', 'Alto Paraná', 1),
(47, 'Carlos Portillo', 'Alto Paraná', 2),
(48, 'Concepción Quintana', 'Alto Paraná', 1),
(49, 'Elio Cabral', 'Alto Paraná', 1),
(50, 'Ramón Romero Roa', 'Alto Paraná', 1),
(51, 'Oscar González D.', 'Alto Paraná', 2),
(52, 'Atilio Penayo', 'Central', 1),
(53, 'Carlos Núñez S.', 'Central', 1),
(54, 'Celso Maldonado D.', 'Central', 2),
(55, 'Ariel Oviedo V.', 'Central', 1),
(56, 'Del Pilar Medina', 'Central', 1),
(57, 'Dionisio Amarilla', 'Central', 2),
(58, 'Edgar Acosta', 'Central', 2),
(59, 'Enrique Pereira', 'Central', 1),
(60, 'Héctor Lesme', 'Central', 2),
(61, 'Hugo Velázquez M.', 'Central', 1),
(62, 'Hugo Rubín', 'Central', 6),
(63, 'José María Ibáñez', 'Central', 1),
(64, 'María Carisimo', 'Central', 2),
(65, 'María Rocío Casco', 'Central', 3),
(66, 'Tadeo Rojas', 'Central', 1),
(67, 'Néstor Ferrer', 'Central', 1),
(68, 'Ricardo González', 'Central', 6),
(69, 'Salustiano Salinas M.', 'Central', 2),
(70, 'Sergio Rojas', 'Central', 2),
(71, 'Pedro Alliana', 'Ñeembucú', 1),
(72, 'Víctor Ríos O.', 'Ñeembucú', 2),
(73, 'Juan B. Ramírez', 'Amambay', 2),
(74, 'Marcial Lezcano', 'Amambay', 1),
(75, 'María Cristina Villalba', 'Canindeyú', 1),
(76, 'Purificación Morel', 'Canindeyú', 1),
(77, 'Julio Mineur D.', 'Presidente Hayes', 7),
(78, 'Oscar Venancio Núñez', 'Presidente Hayes', 1),
(79, 'José Adorno', 'Alto Paraguay', 1),
(80, 'Cornelius Sawatzky', 'Boquerón', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `votaciones_diputados`
--

CREATE TABLE IF NOT EXISTS `votaciones_diputados` (
  `asuntoId` int(11) NOT NULL,
  `diputadoId` int(11) NOT NULL,
  `bloqueId` int(11) NOT NULL,
  `voto` int(11) NOT NULL,
  PRIMARY KEY (`asuntoId`,`diputadoId`,`bloqueId`),
  KEY `fk_votaciones-diputados_2_idx` (`bloqueId`),
  KEY `fk_votaciones-diputados_3_idx` (`diputadoId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `diputados`
--
ALTER TABLE `diputados`
  ADD CONSTRAINT `fk_diputados_1` FOREIGN KEY (`bloqueId`) REFERENCES `bloques_diputados` (`bloqueId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `votaciones_diputados`
--
ALTER TABLE `votaciones_diputados`
  ADD CONSTRAINT `fk_votaciones-diputados_1` FOREIGN KEY (`asuntoId`) REFERENCES `asuntos_diputados` (`asuntoId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_votaciones-diputados_2` FOREIGN KEY (`bloqueId`) REFERENCES `bloques_diputados` (`bloqueId`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_votaciones-diputados_3` FOREIGN KEY (`diputadoId`) REFERENCES `diputados` (`diputadoId`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
