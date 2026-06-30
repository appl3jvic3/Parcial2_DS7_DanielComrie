-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-06-2026 a las 14:39:10
-- Versión del servidor: 8.4.7
-- Versión de PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_participantes`
--

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `buscar_participantes`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `buscar_participantes` (IN `termino_busqueda` VARCHAR(100))   BEGIN
    SELECT * FROM reporte_completo_participantes 
    WHERE nombre_completo LIKE CONCAT('%', termino_busqueda, '%')
       OR documento_identidad LIKE CONCAT('%', termino_busqueda, '%')
       OR correo_electronico LIKE CONCAT('%', termino_busqueda, '%')
    ORDER BY fecha_inscripcion DESC;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_tecnologicas`
--

DROP TABLE IF EXISTS `categorias_tecnologicas`;
CREATE TABLE IF NOT EXISTS `categorias_tecnologicas` (
  `id_categoria` int NOT NULL AUTO_INCREMENT,
  `nombre_categoria` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `nivel_dificultad` enum('Básico','Intermedio','Avanzado','Experto') COLLATE utf8mb4_unicode_ci DEFAULT 'Intermedio',
  `duracion_estimada_horas` int DEFAULT '40',
  `certificacion` tinyint(1) DEFAULT '0',
  `demanda_laboral` enum('Baja','Media','Alta','Muy Alta') COLLATE utf8mb4_unicode_ci DEFAULT 'Media',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_categoria`),
  UNIQUE KEY `nombre_categoria` (`nombre_categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias_tecnologicas`
--

INSERT INTO `categorias_tecnologicas` (`id_categoria`, `nombre_categoria`, `descripcion`, `nivel_dificultad`, `duracion_estimada_horas`, `certificacion`, `demanda_laboral`, `fecha_registro`) VALUES
(1, 'Cloud Computing', 'Infraestructura en la nube, AWS, Azure, Google Cloud', 'Intermedio', 60, 1, 'Muy Alta', '2026-06-30 14:19:51'),
(2, 'Big Data', 'Procesamiento de grandes volúmenes de datos, Hadoop, Spark', 'Avanzado', 80, 1, 'Alta', '2026-06-30 14:19:51'),
(3, 'Desarrollo Móvil', 'Aplicaciones para iOS y Android, React Native, Flutter', 'Intermedio', 50, 1, 'Muy Alta', '2026-06-30 14:19:51'),
(4, 'Ciberseguridad', 'Protección de sistemas, Ethical Hacking, Seguridad Informática', 'Avanzado', 70, 1, 'Muy Alta', '2026-06-30 14:19:51'),
(5, 'IoT (Internet de las Cosas)', 'Dispositivos conectados, Arduino, Raspberry Pi', 'Intermedio', 45, 0, 'Alta', '2026-06-30 14:19:51'),
(6, 'Machine Learning', 'Inteligencia Artificial, Algoritmos de aprendizaje automático', 'Avanzado', 90, 1, 'Muy Alta', '2026-06-30 14:19:51'),
(7, 'DevOps', 'Integración continua, Docker, Kubernetes, Automatización', 'Intermedio', 55, 1, 'Alta', '2026-06-30 14:19:51'),
(8, 'Python', 'Lenguaje de programación versátil, Data Science, Web', 'Básico', 40, 0, 'Muy Alta', '2026-06-30 14:19:51'),
(9, 'Blockchain', 'Tecnología descentralizada, Criptomonedas, Smart Contracts', 'Avanzado', 75, 1, 'Media', '2026-06-30 14:19:51'),
(10, 'Realidad Virtual', 'VR, AR, Metaverso, Unity, Unreal Engine', 'Intermedio', 65, 0, 'Media', '2026-06-30 14:19:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_sistema`
--

DROP TABLE IF EXISTS `configuracion_sistema`;
CREATE TABLE IF NOT EXISTS `configuracion_sistema` (
  `id_config` int NOT NULL AUTO_INCREMENT,
  `clave_config` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_config` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo_dato` enum('String','Integer','Boolean','JSON') COLLATE utf8mb4_unicode_ci DEFAULT 'String',
  `descripcion_config` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_config`),
  UNIQUE KEY `clave_config` (`clave_config`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `configuracion_sistema`
--

INSERT INTO `configuracion_sistema` (`id_config`, `clave_config`, `valor_config`, `tipo_dato`, `descripcion_config`, `fecha_actualizacion`) VALUES
(1, 'VERSION_SISTEMA', '2.0.0', 'String', 'Versión actual del sistema', '2026-06-30 14:19:52'),
(2, 'MAXIMO_EDAD', '120', 'Integer', 'Edad máxima permitida', '2026-06-30 14:19:52'),
(3, 'MINIMO_EDAD', '1', 'Integer', 'Edad mínima permitida', '2026-06-30 14:19:52'),
(4, 'FORMATO_TELEFONO', '/^[0-9+-s()]+$/', 'String', 'Expresión regular para teléfono', '2026-06-30 14:19:52'),
(5, 'TIEMPO_SESION', '3600', 'Integer', 'Tiempo de sesión en segundos', '2026-06-30 14:19:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_actividad`
--

DROP TABLE IF EXISTS `historial_actividad`;
CREATE TABLE IF NOT EXISTS `historial_actividad` (
  `id_historial` int NOT NULL AUTO_INCREMENT,
  `participante_id` int NOT NULL,
  `accion_realizada` enum('Inscripción','Modificación','Eliminación','Exportación') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_cambio` text COLLATE utf8mb4_unicode_ci,
  `ip_origen` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `usuario_sistema` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Sistema',
  `fecha_evento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_historial`),
  KEY `participante_id` (`participante_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_actividad`
--

INSERT INTO `historial_actividad` (`id_historial`, `participante_id`, `accion_realizada`, `descripcion_cambio`, `ip_origen`, `usuario_sistema`, `fecha_evento`) VALUES
(1, 1, 'Inscripción', 'Nuevo participante: María González', NULL, 'Sistema', '2026-06-30 14:19:52'),
(2, 2, 'Inscripción', 'Nuevo participante: Carlos Martínez', NULL, 'Sistema', '2026-06-30 14:19:52'),
(3, 3, 'Inscripción', 'Nuevo participante: Ana Rodríguez', NULL, 'Sistema', '2026-06-30 14:19:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes_evento`
--

DROP TABLE IF EXISTS `participantes_evento`;
CREATE TABLE IF NOT EXISTS `participantes_evento` (
  `id_participante` int NOT NULL AUTO_INCREMENT,
  `documento_identidad` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `primer_nombre` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(80) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `edad` int NOT NULL,
  `genero` enum('Masculino','Femenino','No binario','Prefiero no decirlo') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Prefiero no decirlo',
  `ubicacion_id` int NOT NULL,
  `nacionalidad_oficial` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `correo_electronico` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_movil` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telefono_fijo` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nivel_educativo` enum('Primaria','Secundaria','Técnico','Universitario','Posgrado','Doctorado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ocupacion_actual` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experiencia_previa` tinyint(1) DEFAULT '0',
  `anos_experiencia` int DEFAULT '0',
  `comentarios_adicionales` text COLLATE utf8mb4_unicode_ci,
  `firma_digital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inscripcion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_participante` enum('Activo','Inactivo','En espera','Cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'Activo',
  PRIMARY KEY (`id_participante`),
  UNIQUE KEY `documento_identidad` (`documento_identidad`),
  UNIQUE KEY `correo_electronico` (`correo_electronico`),
  KEY `ubicacion_id` (`ubicacion_id`),
  KEY `idx_correo` (`correo_electronico`),
  KEY `idx_documento` (`documento_identidad`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `participantes_evento`
--

INSERT INTO `participantes_evento` (`id_participante`, `documento_identidad`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `edad`, `genero`, `ubicacion_id`, `nacionalidad_oficial`, `correo_electronico`, `telefono_movil`, `telefono_fijo`, `nivel_educativo`, `ocupacion_actual`, `experiencia_previa`, `anos_experiencia`, `comentarios_adicionales`, `firma_digital`, `fecha_inscripcion`, `estado_participante`) VALUES
(1, '8-123-456', 'María', 'Isabel', 'González', 'Pérez', 28, 'Femenino', 1, 'Panameña', 'maria.gonzalez@email.com', '507-6123-4567', NULL, 'Universitario', 'Ingeniera de Software', 1, 5, 'Interesada en ciberseguridad', NULL, '2026-06-30 14:19:52', 'Activo'),
(2, 'PE-789-123', 'Carlos', 'Andrés', 'Martínez', 'López', 35, 'Masculino', 4, 'Colombiana', 'carlos.martinez@email.com', '57-310-123-4567', NULL, 'Posgrado', 'Arquitecto de Datos', 1, 8, 'Experiencia en Big Data', NULL, '2026-06-30 14:19:52', 'Activo'),
(3, '1-234-567', 'Ana', 'Lucía', 'Rodríguez', 'García', 22, 'Femenino', 2, 'Estadounidense', 'ana.rodriguez@email.com', '1-555-123-4567', NULL, 'Secundaria', 'Estudiante', 0, 0, 'Quiere aprender desarrollo móvil', NULL, '2026-06-30 14:19:52', 'En espera');

--
-- Disparadores `participantes_evento`
--
DROP TRIGGER IF EXISTS `after_participante_insert`;
DELIMITER $$
CREATE TRIGGER `after_participante_insert` AFTER INSERT ON `participantes_evento` FOR EACH ROW BEGIN
    INSERT INTO historial_actividad (participante_id, accion_realizada, descripcion_cambio)
    VALUES (NEW.id_participante, 'Inscripción', 
            CONCAT('Nuevo participante: ', NEW.primer_nombre, ' ', NEW.primer_apellido));
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participante_categoria`
--

DROP TABLE IF EXISTS `participante_categoria`;
CREATE TABLE IF NOT EXISTS `participante_categoria` (
  `id_relacion` int NOT NULL AUTO_INCREMENT,
  `participante_id` int NOT NULL,
  `categoria_id` int NOT NULL,
  `fecha_asignacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `prioridad` enum('Baja','Media','Alta') COLLATE utf8mb4_unicode_ci DEFAULT 'Media',
  `nota_adicional` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_relacion`),
  UNIQUE KEY `unique_participante_categoria` (`participante_id`,`categoria_id`),
  KEY `categoria_id` (`categoria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `participante_categoria`
--

INSERT INTO `participante_categoria` (`id_relacion`, `participante_id`, `categoria_id`, `fecha_asignacion`, `prioridad`, `nota_adicional`) VALUES
(1, 1, 4, '2026-06-30 14:19:52', 'Alta', NULL),
(2, 1, 1, '2026-06-30 14:19:52', 'Media', NULL),
(3, 2, 2, '2026-06-30 14:19:52', 'Alta', NULL),
(4, 2, 7, '2026-06-30 14:19:52', 'Media', NULL),
(5, 3, 3, '2026-06-30 14:19:52', 'Alta', NULL);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `reporte_completo_participantes`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `reporte_completo_participantes`;
CREATE TABLE IF NOT EXISTS `reporte_completo_participantes` (
`id_participante` int
,`documento_identidad` varchar(25)
,`nombre_completo` varchar(323)
,`edad` int
,`genero` enum('Masculino','Femenino','No binario','Prefiero no decirlo')
,`pais_residencia` varchar(100)
,`continente` varchar(50)
,`nacionalidad_oficial` varchar(100)
,`correo_electronico` varchar(120)
,`telefono_movil` varchar(30)
,`nivel_educativo` enum('Primaria','Secundaria','Técnico','Universitario','Posgrado','Doctorado')
,`ocupacion_actual` varchar(100)
,`temas_interes` text
,`cantidad_temas` bigint
,`experiencia_previa` tinyint(1)
,`anos_experiencia` int
,`comentarios_adicionales` text
,`firma_digital` varchar(255)
,`fecha_inscripcion` timestamp
,`estado_participante` enum('Activo','Inactivo','En espera','Cancelado')
,`dias_inscrito` int
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones_geograficas`
--

DROP TABLE IF EXISTS `ubicaciones_geograficas`;
CREATE TABLE IF NOT EXISTS `ubicaciones_geograficas` (
  `id_ubicacion` int NOT NULL AUTO_INCREMENT,
  `codigo_pais` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_pais` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `continente` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'América',
  `poblacion_aproximada` bigint DEFAULT NULL,
  `idioma_oficial` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moneda` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_ubicacion`),
  UNIQUE KEY `codigo_pais` (`codigo_pais`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ubicaciones_geograficas`
--

INSERT INTO `ubicaciones_geograficas` (`id_ubicacion`, `codigo_pais`, `nombre_pais`, `continente`, `poblacion_aproximada`, `idioma_oficial`, `moneda`, `activo`, `fecha_creacion`) VALUES
(1, 'PAN', 'República de Panamá', 'América Central', 4500000, 'Español', 'Balboa / Dólar', 1, '2026-06-30 14:19:50'),
(2, 'USA', 'Estados Unidos de América', 'América del Norte', 331000000, 'Inglés', 'Dólar', 1, '2026-06-30 14:19:50'),
(3, 'MEX', 'Estados Unidos Mexicanos', 'América del Norte', 126000000, 'Español', 'Peso Mexicano', 1, '2026-06-30 14:19:50'),
(4, 'COL', 'República de Colombia', 'América del Sur', 50880000, 'Español', 'Peso Colombiano', 1, '2026-06-30 14:19:50'),
(5, 'ARG', 'República Argentina', 'América del Sur', 45380000, 'Español', 'Peso Argentino', 1, '2026-06-30 14:19:50'),
(6, 'ESP', 'Reino de España', 'Europa', 47350000, 'Español', 'Euro', 1, '2026-06-30 14:19:50'),
(7, 'CHL', 'República de Chile', 'América del Sur', 19110000, 'Español', 'Peso Chileno', 1, '2026-06-30 14:19:50'),
(8, 'PER', 'República del Perú', 'América del Sur', 32970000, 'Español', 'Sol', 1, '2026-06-30 14:19:50'),
(9, 'BRA', 'República Federativa de Brasil', 'América del Sur', 213000000, 'Portugués', 'Real', 1, '2026-06-30 14:19:50'),
(10, 'CAN', 'Canadá', 'América del Norte', 38000000, 'Inglés/Francés', 'Dólar Canadiense', 1, '2026-06-30 14:19:50');

-- --------------------------------------------------------

--
-- Estructura para la vista `reporte_completo_participantes`
--
DROP TABLE IF EXISTS `reporte_completo_participantes`;

DROP VIEW IF EXISTS `reporte_completo_participantes`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `reporte_completo_participantes`  AS SELECT `p`.`id_participante` AS `id_participante`, `p`.`documento_identidad` AS `documento_identidad`, concat(`p`.`primer_nombre`,' ',coalesce(`p`.`segundo_nombre`,''),' ',`p`.`primer_apellido`,' ',coalesce(`p`.`segundo_apellido`,'')) AS `nombre_completo`, `p`.`edad` AS `edad`, `p`.`genero` AS `genero`, `u`.`nombre_pais` AS `pais_residencia`, `u`.`continente` AS `continente`, `p`.`nacionalidad_oficial` AS `nacionalidad_oficial`, `p`.`correo_electronico` AS `correo_electronico`, `p`.`telefono_movil` AS `telefono_movil`, `p`.`nivel_educativo` AS `nivel_educativo`, `p`.`ocupacion_actual` AS `ocupacion_actual`, group_concat(distinct `c`.`nombre_categoria` order by `c`.`nombre_categoria` ASC separator ', ') AS `temas_interes`, count(distinct `c`.`id_categoria`) AS `cantidad_temas`, `p`.`experiencia_previa` AS `experiencia_previa`, `p`.`anos_experiencia` AS `anos_experiencia`, `p`.`comentarios_adicionales` AS `comentarios_adicionales`, `p`.`firma_digital` AS `firma_digital`, `p`.`fecha_inscripcion` AS `fecha_inscripcion`, `p`.`estado_participante` AS `estado_participante`, (to_days(curdate()) - to_days(`p`.`fecha_inscripcion`)) AS `dias_inscrito` FROM (((`participantes_evento` `p` join `ubicaciones_geograficas` `u` on((`p`.`ubicacion_id` = `u`.`id_ubicacion`))) left join `participante_categoria` `pc` on((`p`.`id_participante` = `pc`.`participante_id`))) left join `categorias_tecnologicas` `c` on((`pc`.`categoria_id` = `c`.`id_categoria`))) GROUP BY `p`.`id_participante` ORDER BY `p`.`fecha_inscripcion` DESC ;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_actividad`
--
ALTER TABLE `historial_actividad`
  ADD CONSTRAINT `historial_actividad_ibfk_1` FOREIGN KEY (`participante_id`) REFERENCES `participantes_evento` (`id_participante`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `participantes_evento`
--
ALTER TABLE `participantes_evento`
  ADD CONSTRAINT `participantes_evento_ibfk_1` FOREIGN KEY (`ubicacion_id`) REFERENCES `ubicaciones_geograficas` (`id_ubicacion`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Filtros para la tabla `participante_categoria`
--
ALTER TABLE `participante_categoria`
  ADD CONSTRAINT `participante_categoria_ibfk_1` FOREIGN KEY (`participante_id`) REFERENCES `participantes_evento` (`id_participante`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `participante_categoria_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_tecnologicas` (`id_categoria`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
