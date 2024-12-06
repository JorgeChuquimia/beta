-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-11-2024 a las 01:47:29
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db_irenenava`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administrador`
--

CREATE TABLE `administrador` (
  `id_adm` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `exp` varchar(5) NOT NULL,
  `zona` varchar(40) NOT NULL,
  `calle` varchar(30) NOT NULL,
  `casa` int(11) NOT NULL,
  `celular` int(11) NOT NULL,
  `genero` varchar(10) NOT NULL,
  `profesion` varchar(40) DEFAULT NULL,
  `categoria` varchar(40) DEFAULT NULL,
  `antiguedad` varchar(40) DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `administrador`
--

INSERT INTO `administrador` (`id_adm`, `id_usuario`, `nombre`, `ci`, `exp`, `zona`, `calle`, `casa`, `celular`, `genero`, `profesion`, `categoria`, `antiguedad`, `fecha_nac`) VALUES
(1, 1, 'HELEN ASUNCION POMA ARUQUIPA', '99999', 'LPZ', 'ZONA CENTRAL NORTE', 'CALLE LOS PINOS', 155, 77766999, 'F', 'SISTEMAS INFORMATICOS', 'SISTEMAS', '1 año', '2000-12-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignar`
--

CREATE TABLE `asignar` (
  `id_asi` int(11) NOT NULL,
  `id_gra` int(11) NOT NULL,
  `id_par` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asignar`
--

INSERT INTO `asignar` (`id_asi`, `id_gra`, `id_par`, `id_usuario`) VALUES
(1, 8, 5, 4),
(2, 2, 7, 25),
(3, 7, 1, 17);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calificaciones`
--

CREATE TABLE `calificaciones` (
  `id_cal` int(11) NOT NULL,
  `b1` int(11) NOT NULL,
  `b2` int(11) NOT NULL,
  `b3` int(11) NOT NULL,
  `promedio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_mat` int(11) NOT NULL,
  `anio` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `calificaciones`
--

INSERT INTO `calificaciones` (`id_cal`, `b1`, `b2`, `b3`, `promedio`, `id_usuario`, `id_mat`, `anio`) VALUES
(2, 100, 26, 80, 68, 3, 7, 2024),
(3, 100, 100, 100, 100, 1, 8, 2024),
(5, 67, 90, 56, 68, 3, 9, 2024),
(8, 60, 45, 80, 61, 3, 8, 2024),
(9, 70, 80, 80, 77, 1, 15, 2024),
(10, 90, 60, 90, 80, 23, 8, 2024),
(11, 80, 45, 90, 79, 23, 8, 2024),
(12, 100, 90, 90, 93, 23, 7, 2024),
(14, 78, 80, 90, 83, 1, 5, 2024),
(15, 0, 0, 0, 0, 1, 7, 2024),
(17, 0, 0, 0, 0, 1, 10, 2024),
(18, 0, 0, 0, 0, 1, 11, 2024),
(19, 0, 0, 0, 0, 1, 12, 2024),
(20, 0, 0, 0, 0, 1, 13, 2024),
(21, 89, 67, 67, 74, 1, 14, 2024);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

CREATE TABLE `estudiante` (
  `id_est` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `exp` varchar(5) NOT NULL,
  `zona` varchar(40) NOT NULL,
  `calle` varchar(30) NOT NULL,
  `casa` int(11) NOT NULL,
  `celular` bigint(20) NOT NULL,
  `genero` varchar(10) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `ci_ppff` varchar(15) DEFAULT NULL,
  `ocupacion_ppff` varchar(50) DEFAULT NULL,
  `nom_ppff` varchar(255) NOT NULL,
  `cel_ppff` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`id_est`, `id_usuario`, `nombre`, `ci`, `exp`, `zona`, `calle`, `casa`, `celular`, `genero`, `fecha_nac`, `ci_ppff`, `ocupacion_ppff`, `nom_ppff`, `cel_ppff`) VALUES
(1, 3, 'ESTUDIANTE DE PRUEBAS SISTEMA', '12321', 'SCZ', 'ZONA SUR', 'CALLE 2', 1231, 77777777, 'M', '2012-12-12', '1234567', 'MEDICO', 'TUTOR DEL ESTUDIANTE DE PRUEBAS', '77751515');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grado`
--

CREATE TABLE `grado` (
  `id_gra` int(11) NOT NULL,
  `abr_gra` int(11) NOT NULL,
  `nom_gra` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `grado`
--

INSERT INTO `grado` (`id_gra`, `abr_gra`, `nom_gra`) VALUES
(1, 0, 'INICIAL'),
(2, 1, 'PRIMERO DE PRIMARIA'),
(4, 3, 'TERCERO DE PRIMARIA'),
(6, 2, 'SEGUNDO DE PRIMARIA'),
(7, 4, 'CUARTO DE PRIMARIA'),
(8, 5, 'QUINTO DE PRIMARIA'),
(10, 6, 'SEXTO DE PRIMARIA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones`
--

CREATE TABLE `inscripciones` (
  `id_ins` int(11) NOT NULL,
  `id_gra` int(11) NOT NULL,
  `id_par` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inscripciones`
--

INSERT INTO `inscripciones` (`id_ins`, `id_gra`, `id_par`, `fecha`, `id_usuario`) VALUES
(2, 7, 2, '2024-10-09', 3),
(3, 7, 7, '2024-10-23', 23),
(4, 8, 1, '2024-10-24', 24),
(5, 8, 5, '2024-11-09', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materia`
--

CREATE TABLE `materia` (
  `id_mat` int(11) NOT NULL,
  `nom_mat` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `materia`
--

INSERT INTO `materia` (`id_mat`, `nom_mat`) VALUES
(1, 'INICIAL'),
(5, 'MATEMATICA'),
(7, 'COMUNICACION Y LENGUAJES'),
(8, 'EDUCACION MUSICAL'),
(10, 'CIENCIAS SOCIALES'),
(11, 'EDUCACION FISICA Y DEPORTES'),
(12, 'ARTES PLASTICAS Y VISUALES'),
(13, 'TECNICA TECNOLOGICA'),
(14, 'CIENCIAS NATURALES'),
(15, 'VALORES, ESPIRITUALIDAD Y RELIGION');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paralelo`
--

CREATE TABLE `paralelo` (
  `id_par` int(11) NOT NULL,
  `nom_par` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `paralelo`
--

INSERT INTO `paralelo` (`id_par`, `nom_par`) VALUES
(1, 'A'),
(2, 'B'),
(5, 'D'),
(7, 'C'),
(8, 'E');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

CREATE TABLE `profesor` (
  `id_prof` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(60) NOT NULL,
  `ci` varchar(15) NOT NULL,
  `exp` varchar(5) NOT NULL,
  `zona` varchar(40) NOT NULL,
  `calle` varchar(30) NOT NULL,
  `casa` int(11) NOT NULL,
  `celular` int(11) NOT NULL,
  `genero` varchar(10) NOT NULL,
  `fecha_nac` date DEFAULT NULL,
  `esp_egreso` varchar(40) DEFAULT NULL,
  `cat_gestion` varchar(40) DEFAULT NULL,
  `categoria` varchar(40) DEFAULT NULL,
  `anio_ser` varchar(25) DEFAULT NULL,
  `rda_prof` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`id_prof`, `id_usuario`, `nombre`, `ci`, `exp`, `zona`, `calle`, `casa`, `celular`, `genero`, `fecha_nac`, `esp_egreso`, `cat_gestion`, `categoria`, `anio_ser`, `rda_prof`) VALUES
(1, 4, 'PROFESOR DE PRUEBAS PARA EL SISTEMA', '99999', 'PT', 'ZONA LOS PINOSS', 'CALLE PANDO', 12, 11111111, 'MASCULINO', '1988-12-12', 'MATEMATICAS', '12312321', 'PRIMARIA', '2', 'RDA12312'),
(4, 22, 'SUSANA PEREZ RAMIREZ', '78945612', 'LPZ', 'ALTO CHIJINI', 'FINAL LA CHIMBA', 1096, 75698455, 'F', '1995-06-07', 'ARTES PLASTICAS', '2000', 'PRIMARIA', '5', '123489645'),
(5, 25, 'LIZETH POMA ARUQUIPA', '14229244', 'LPZ', 'SANTIAGO SEGUNDO', 'PLZ EL MINERO', 1234, 70589350, 'F', '1999-02-09', 'CIENCIAS NATURALES', '2022', '1', '2', '78956');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulos`
--

CREATE TABLE `titulos` (
  `id_tit` int(11) NOT NULL,
  `nom_tit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `institucion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `anio_obtencion` date NOT NULL,
  `id_prof` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `titulos`
--

INSERT INTO `titulos` (`id_tit`, `nom_tit`, `institucion`, `anio_obtencion`, `id_prof`) VALUES
(1, 'LICENCIATURA EN MUSICA', 'NORMAL', '2015-04-12', 1),
(2, 'CERTIFICADO DE MUSICA NACIONAL', 'CONCERVATORIO DE MUSICA', '2017-12-12', 1),
(3, 'LIC MATEMATICAS', 'UNIVERSIDAD MAYOR DE SAN ANDRES', '2023-07-07', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` varchar(50) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `usuario`, `password`, `rol`, `foto`, `nombre`) VALUES
(1, 'HELEN', '$2y$10$9FGIMT2h9irC.K/mUe.oDuzhMc6PCKQBCkELPL6TqoFUsFs9cRKBO', 'Administrador', 'img/fotos/66d0aa32b243f_perf.jpg', 'HELEN ASUNCION POMA ARUQUIPA'),
(3, 'ESTUDIANTE', '$2y$10$wZDuvk.ZKPcAJ1BG/FAmcOeJkdQSfCypqSyOE4xx6jfwyHys50wW2', 'Estudiante', 'img/fotos/66cfd9b0e929c_est.jpg', 'ESTUDIANTE DE PRUEBAS SISTEMA'),
(4, 'PROFESOR', '$2y$10$qFK67WjPTkts5bXGGp8bjeSC8l6iu34WPahA1DBvEqeqjV810wG/u', 'Profesor', 'img/fotos/66cfd9d428b9a_prof.jpeg', 'PROFESOR DE PRUEBAS PARA EL SISTEMA'),
(22, 'SUSANA', '$2y$10$YS3AlMD95xZg.VTN8ro1reLFlIc6oDA6g2o7FyYTYBwOWG9nPDGJC', 'Profesor', NULL, 'SUSANA PEREZ RAMIREZ'),
(25, 'LIZETH', '$2y$10$eu9oPTGMWXaKV36GmJc0XeTtsMxooxA1KWwdvDmHcbfTkicbfkUi6', 'Profesor', NULL, 'LIZETH POMA ARUQUIPA');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id_adm`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `asignar`
--
ALTER TABLE `asignar`
  ADD PRIMARY KEY (`id_asi`);

--
-- Indices de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  ADD PRIMARY KEY (`id_cal`);

--
-- Indices de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD PRIMARY KEY (`id_est`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `grado`
--
ALTER TABLE `grado`
  ADD PRIMARY KEY (`id_gra`);

--
-- Indices de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD PRIMARY KEY (`id_ins`),
  ADD KEY `id_gra` (`id_gra`),
  ADD KEY `id_par` (`id_par`);

--
-- Indices de la tabla `materia`
--
ALTER TABLE `materia`
  ADD PRIMARY KEY (`id_mat`);

--
-- Indices de la tabla `paralelo`
--
ALTER TABLE `paralelo`
  ADD PRIMARY KEY (`id_par`);

--
-- Indices de la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD PRIMARY KEY (`id_prof`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `titulos`
--
ALTER TABLE `titulos`
  ADD PRIMARY KEY (`id_tit`),
  ADD KEY `id_prof` (`id_prof`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id_adm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `asignar`
--
ALTER TABLE `asignar`
  MODIFY `id_asi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `calificaciones`
--
ALTER TABLE `calificaciones`
  MODIFY `id_cal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `estudiante`
--
ALTER TABLE `estudiante`
  MODIFY `id_est` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `grado`
--
ALTER TABLE `grado`
  MODIFY `id_gra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  MODIFY `id_ins` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `materia`
--
ALTER TABLE `materia`
  MODIFY `id_mat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `paralelo`
--
ALTER TABLE `paralelo`
  MODIFY `id_par` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `profesor`
--
ALTER TABLE `profesor`
  MODIFY `id_prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `titulos`
--
ALTER TABLE `titulos`
  MODIFY `id_tit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administrador`
--
ALTER TABLE `administrador`
  ADD CONSTRAINT `administrador_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `inscripciones`
--
ALTER TABLE `inscripciones`
  ADD CONSTRAINT `inscripciones_ibfk_1` FOREIGN KEY (`id_gra`) REFERENCES `grado` (`id_gra`),
  ADD CONSTRAINT `inscripciones_ibfk_2` FOREIGN KEY (`id_par`) REFERENCES `paralelo` (`id_par`);

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `titulos`
--
ALTER TABLE `titulos`
  ADD CONSTRAINT `titulos_ibfk_1` FOREIGN KEY (`id_prof`) REFERENCES `profesor` (`id_prof`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
