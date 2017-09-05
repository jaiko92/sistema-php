-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 17-08-2017 a las 16:02:26
-- Versión del servidor: 5.7.14
-- Versión de PHP: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";



--
-- Base de datos: `reportes_new`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `meta_mensual`
--

CREATE TABLE `meta_mensual` (
  `mes` date NOT NULL,
  `porcentaje_utilidad` decimal(20,4) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `valor_meta` decimal(20,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `meta_mensual`
--

INSERT INTO `meta_mensual` (`mes`, `porcentaje_utilidad`, `id_usuario`, `valor_meta`) VALUES
('2017-08-01', '35.0000', 1, '2000000.0000'),
('2017-07-01', '35.0000', 1, '2000000.0000');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `meta_mensual`
--
ALTER TABLE `meta_mensual`
  ADD PRIMARY KEY (`mes`);

