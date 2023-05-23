-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 03-09-2022 a las 15:52:58
-- Versión del servidor: 10.5.12-MariaDB-cll-lve
-- Versión de PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u301491897_credsziro`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actions`
--

CREATE TABLE `actions` (
  `id` int(11) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `ajax` int(11) DEFAULT 0,
  `auth` int(11) DEFAULT 1,
  `role` varchar(45) DEFAULT NULL,
  `state` int(11) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `actions`
--

INSERT INTO `actions` (`id`, `controller`, `action`, `ajax`, `auth`, `role`, `state`, `created`, `modified`) VALUES
(1, 'djskdjdsd', 'dskjdskj', 1, 1, 'sdsds', 1, '2020-07-06 01:46:26', '2020-07-06 01:46:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `automatics`
--

CREATE TABLE `automatics` (
  `id` int(11) NOT NULL,
  `min_value` float NOT NULL DEFAULT 0,
  `max_value` float NOT NULL DEFAULT 0,
  `type_value` int(11) NOT NULL DEFAULT 0,
  `score_min` int(11) NOT NULL DEFAULT 0,
  `aplica_cap` int(11) NOT NULL DEFAULT 0,
  `cap` int(11) DEFAULT NULL,
  `min_oblig` int(11) DEFAULT NULL,
  `aplica_min_value_oblig` int(11) NOT NULL DEFAULT 0,
  `min_value_oblig` int(11) DEFAULT NULL,
  `min_mora` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `automatics`
--

INSERT INTO `automatics` (`id`, `min_value`, `max_value`, `type_value`, `score_min`, `aplica_cap`, `cap`, `min_oblig`, `aplica_min_value_oblig`, `min_value_oblig`, `min_mora`, `state`, `created`, `modified`) VALUES
(1, 1301000, 1500000, 0, 700, 1, 50, 4, 0, NULL, 30, 1, '2022-02-16 23:42:45', '2022-02-16 23:42:45'),
(2, 1001000, 1300000, 0, 700, 1, 50, 3, 0, NULL, 30, 1, '2022-02-16 23:42:45', '2022-02-16 23:42:45'),
(3, 601000, 1000000, 0, 550, 1, 50, 2, 0, NULL, 30, 1, '2022-02-16 23:42:45', '2022-02-16 23:42:45'),
(4, 351000, 600000, 0, 350, 1, 50, 1, 0, 500000, 30, 1, '2022-02-16 23:42:45', '2022-02-16 23:42:45'),
(5, 50000, 350000, 0, 350, 1, 50, 0, 0, NULL, 30, 1, '2022-02-16 23:42:45', '2022-02-16 23:42:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `collection_fees`
--

CREATE TABLE `collection_fees` (
  `id` int(11) NOT NULL,
  `credits_line_id` int(11) NOT NULL,
  `day_ini` int(11) NOT NULL,
  `day_end` int(11) NOT NULL,
  `rate` float NOT NULL DEFAULT 0,
  `state` int(11) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `collection_fees`
--

INSERT INTO `collection_fees` (`id`, `credits_line_id`, `day_ini`, `day_end`, `rate`, `state`, `created`, `modified`) VALUES
(1, 2, 15, 29, 5, 1, '2020-08-26 11:37:30', '2020-08-26 11:37:35'),
(2, 2, 30, 59, 10, 1, '2020-08-26 11:37:32', '2020-08-26 11:37:36'),
(3, 2, 60, 89, 12, 1, '2020-08-26 11:37:34', '2020-08-26 11:37:37'),
(4, 2, 90, 1000, 20, 1, '2020-08-26 11:37:34', '2020-08-26 11:37:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `commitments`
--

CREATE TABLE `commitments` (
  `id` int(11) NOT NULL,
  `credits_plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `commitment` text NOT NULL,
  `deadline` date NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configs`
--

CREATE TABLE `configs` (
  `id` int(11) NOT NULL,
  `comision` double NOT NULL DEFAULT 0,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `configs`
--

INSERT INTO `configs` (`id`, `comision`, `updated`) VALUES
(1, 4.5, '2021-02-15 17:01:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credits`
--

CREATE TABLE `credits` (
  `id` int(11) NOT NULL,
  `value_request` int(11) NOT NULL,
  `value_aprooved` int(11) NOT NULL,
  `number_fee` int(11) NOT NULL,
  `credits_line_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `interes_rate` float NOT NULL,
  `others_rate` float NOT NULL,
  `debt_rate` float NOT NULL,
  `quota_value` float NOT NULL,
  `value_pending` float NOT NULL,
  `deadline` date NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `debt` int(11) NOT NULL DEFAULT 0,
  `debt_days` int(11) NOT NULL DEFAULT 0,
  `quote_days` int(11) NOT NULL DEFAULT 0,
  `juridico` int(11) NOT NULL DEFAULT 0,
  `date_juridico` date DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `credits_request_id` int(11) NOT NULL,
  `request_ant` int(11) DEFAULT NULL,
  `last_payment_date` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `admin_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credits`
--

INSERT INTO `credits` (`id`, `value_request`, `value_aprooved`, `number_fee`, `credits_line_id`, `type`, `interes_rate`, `others_rate`, `debt_rate`, `quota_value`, `value_pending`, `deadline`, `state`, `debt`, `debt_days`, `quote_days`, `juridico`, `date_juridico`, `customer_id`, `credits_request_id`, `request_ant`, `last_payment_date`, `user_id`, `admin_date`, `created`, `modified`) VALUES
(1, 300000, 300000, 2, 5, 1, 1.88, 8.54, 2, 173832, 300000, '2022-10-07', 0, 0, 0, -6, 0, NULL, 1, 0, 1, NULL, NULL, NULL, '2022-08-08 14:29:18', '2022-08-18 16:42:28'),
(2, 2000000, 2000000, 1, 7, 1, 0.00001, 0.00001, 2, 2000000, 2000000, '2022-09-16', 0, 0, 0, -15, 0, NULL, 8, 9, NULL, NULL, NULL, NULL, '2022-08-17 14:36:34', '2022-09-02 13:25:11'),
(3, 5000000, 5000000, 1, 7, 1, 0.00001, 0.00001, 2, 5000000, 0, '2022-09-16', 1, 0, 0, 0, 0, NULL, 9, 12, NULL, '2022-08-17', NULL, NULL, '2022-08-17 16:28:12', '2022-09-01 10:39:43'),
(4, 2000000, 5000000, 1, 7, 1, 0.0001, 0.0001, 2, 2000000, 2000000, '2022-09-16', 0, 0, 0, -15, 0, NULL, 9, 16, NULL, NULL, NULL, NULL, '2022-08-17 17:33:13', '2022-09-02 14:34:51'),
(5, 5000000, 5000000, 1, 7, 1, 0.0001, 0.0001, 2, 5000010, 5000000, '2022-09-17', 0, 0, 0, -16, 0, NULL, 10, 19, NULL, NULL, NULL, NULL, '2022-08-18 17:46:35', '2022-09-02 13:25:11'),
(6, 2000000, 3000000, 1, 7, 1, 0.0001, 0.0001, 2, 2000000, 2000000, '2022-09-17', 0, 0, 0, -16, 0, NULL, 9, 20, NULL, NULL, NULL, NULL, '2022-08-18 18:02:07', '2022-09-02 13:25:11'),
(7, 2000000, 4000000, 1, 7, 1, 0.0000001, 0, 0, 2000000, 2000000, '2022-10-02', 0, 0, 0, -30, 0, NULL, 11, 22, NULL, '2022-09-02', NULL, NULL, '2022-09-02 10:56:57', '2022-09-02 14:34:41'),
(8, 2000000, 2000000, 1, 7, 1, 0.0000001, 0, 0, 2000000, 2000000, '2022-10-02', 0, 0, 0, -30, 0, NULL, 11, 25, NULL, NULL, NULL, NULL, '2022-09-02 12:45:01', '2022-09-02 14:33:48'),
(9, 1000000, 1000000, 1, 7, 1, 0.0000001, 0, 0, 1000000, 0, '2022-10-02', 1, 0, 0, 0, 0, NULL, 11, 26, NULL, '2022-09-02', NULL, NULL, '2022-09-02 13:31:35', '2022-09-02 14:33:48');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credits_lines`
--

CREATE TABLE `credits_lines` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `interest_rate` float NOT NULL DEFAULT 0,
  `others_rate` float NOT NULL DEFAULT 0,
  `debt_rate` float NOT NULL DEFAULT 0,
  `state` int(11) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credits_lines`
--

INSERT INTO `credits_lines` (`id`, `name`, `description`, `interest_rate`, `others_rate`, `debt_rate`, `state`, `created`, `modified`) VALUES
(1, 'Crédito de consumo', 'Credito de consumo para los clientes', 2, 3, 2.1, 0, '2020-07-08 01:48:00', '2020-07-08 01:52:32'),
(2, 'Linea nacional', 'Linea de crédito nacional', 2, 3, 4, 0, '2020-07-23 19:02:26', '2020-07-23 19:02:26'),
(3, 'Linea normal', 'Línea norma', 3, 2, 5, 0, '2020-07-23 19:03:26', '2020-07-23 19:03:26'),
(4, 'Credito clasico', 'clasico', 2, 9, 2, 0, '2020-09-25 22:17:25', '2020-10-06 19:18:53'),
(5, 'Inauguración de Lanzamiento', 'Octubre 2020 - Actual', 1.88, 9.5, 2, 0, '2020-10-07 16:05:45', '2022-07-27 11:53:15'),
(6, 'pruebas', 'liena para el mes de junio', 0, 0, 0, 0, '2022-05-25 19:39:27', '2022-05-25 19:39:27'),
(7, 'credito sin interes', 'creditos para pymes', 0, 0, 0, 1, '2022-08-17 14:21:01', '2022-08-18 19:10:08'),
(8, 'linea de pruebas', 'solo pruebas', 0, 0, 0, 0, '2022-08-18 17:02:56', '2022-08-18 17:02:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credits_lines_details`
--

CREATE TABLE `credits_lines_details` (
  `id` int(11) NOT NULL,
  `credit_line_id` int(11) NOT NULL DEFAULT 0,
  `month` int(11) NOT NULL DEFAULT 0,
  `count` int(11) NOT NULL DEFAULT 0,
  `min_month` int(11) NOT NULL DEFAULT 0,
  `max_month` int(11) NOT NULL DEFAULT 0,
  `min_value` float NOT NULL DEFAULT 0,
  `max_value` float NOT NULL DEFAULT 0,
  `interest_rate` float NOT NULL DEFAULT 0,
  `others_rate` float NOT NULL DEFAULT 0,
  `debt_rate` float NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `credits_lines_details`
--

INSERT INTO `credits_lines_details` (`id`, `credit_line_id`, `month`, `count`, `min_month`, `max_month`, `min_value`, `max_value`, `interest_rate`, `others_rate`, `debt_rate`) VALUES
(1631, 6, 1, 1, 1, 2, 100000, 200000, 2, 5, 2),
(1632, 6, 2, 2, 1, 2, 100000, 200000, 2, 5, 2),
(1633, 6, 1, 1, 1, 4, 200001, 300000, 4, 5, 2),
(1634, 6, 2, 2, 1, 4, 200001, 300000, 4, 5, 2),
(1635, 6, 3, 3, 1, 4, 200001, 300000, 2, 5, 2),
(1658, 5, 1, 1, 1, 4, 30000, 149999, 1.88, 10, 2),
(1659, 5, 2, 2, 1, 4, 30000, 149999, 1.88, 8.54, 2),
(1660, 5, 3, 3, 1, 4, 30000, 149999, 1.88, 7.77, 2),
(1661, 5, 4, 4, 1, 4, 30000, 149999, 1.88, 7.28, 2),
(1662, 5, 2, 1, 1, 5, 150000, 399999, 1.88, 8.54, 2),
(1663, 5, 3, 2, 1, 5, 150000, 399999, 1.88, 7.77, 2),
(1664, 5, 4, 3, 1, 5, 150000, 399999, 1.88, 7.28, 2),
(1665, 5, 5, 4, 1, 5, 150000, 399999, 1.88, 6.18, 2),
(1666, 5, 6, 5, 1, 5, 150000, 399999, 1.88, 5.39, 2),
(1667, 5, 3, 1, 1, 6, 400000, 599999, 1.88, 7.77, 2),
(1668, 5, 4, 2, 1, 6, 400000, 599999, 1.88, 7.28, 2),
(1669, 5, 5, 3, 1, 6, 400000, 599999, 1.88, 6.18, 2),
(1670, 5, 6, 4, 1, 6, 400000, 599999, 1.88, 5.39, 2),
(1671, 5, 7, 5, 1, 6, 400000, 599999, 1.88, 4.79, 2),
(1672, 5, 8, 6, 1, 6, 400000, 599999, 1.88, 4.33, 2),
(1673, 5, 4, 1, 1, 5, 600000, 850000, 1.88, 7.28, 2),
(1674, 5, 5, 2, 1, 5, 600000, 850000, 1.88, 6.18, 2),
(1675, 5, 6, 3, 1, 5, 600000, 850000, 1.88, 5.39, 2),
(1676, 5, 7, 4, 1, 5, 600000, 850000, 1.88, 4.79, 2),
(1677, 5, 8, 5, 1, 5, 600000, 850000, 1.88, 4.33, 2),
(1684, 8, 1, 1, 1, 4, 100000, 200000, 5, 10, 2),
(1685, 8, 2, 2, 1, 4, 100000, 200000, 5, 10, 2),
(1686, 8, 3, 3, 1, 4, 100000, 200000, 5, 10, 2),
(1687, 8, 4, 4, 1, 4, 100000, 200000, 5, 10, 2),
(1688, 8, 1, 1, 1, 10, 200001, 800000, 2, 8, 2),
(1689, 8, 2, 2, 1, 10, 200001, 800000, 2, 8, 2),
(1690, 8, 3, 3, 1, 10, 200001, 800000, 2, 8, 2),
(1691, 8, 4, 4, 1, 10, 200001, 800000, 2, 8, 2),
(1692, 8, 5, 5, 1, 10, 200001, 800000, 2, 8, 2),
(1693, 8, 6, 6, 1, 10, 200001, 800000, 2, 8, 2),
(1694, 8, 7, 7, 1, 10, 200001, 800000, 2, 8, 2),
(1695, 8, 8, 8, 1, 10, 200001, 800000, 2, 8, 2),
(1696, 8, 9, 9, 1, 10, 200001, 800000, 2, 8, 2),
(1697, 8, 10, 10, 1, 10, 200001, 800000, 2, 8, 2),
(1705, 7, 1, 1, 1, 1, 500000, 6000000, 0.0000001, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credits_plans`
--

CREATE TABLE `credits_plans` (
  `id` int(11) NOT NULL,
  `number` int(11) NOT NULL,
  `credit_id` bigint(20) NOT NULL DEFAULT 0,
  `credit_old` int(11) DEFAULT NULL,
  `capital_value` float NOT NULL,
  `capital_payment` float NOT NULL DEFAULT 0,
  `capital_returned` float NOT NULL DEFAULT 0,
  `interest_value` float NOT NULL,
  `interest_payment` float NOT NULL DEFAULT 0,
  `others_value` float NOT NULL,
  `others_payment` float NOT NULL DEFAULT 0,
  `others_add` float NOT NULL DEFAULT 0,
  `interest_add` float NOT NULL DEFAULT 0,
  `debt_add` float NOT NULL DEFAULT 0,
  `dateini` date DEFAULT NULL,
  `deadline` date NOT NULL,
  `date_payment` date DEFAULT NULL,
  `date_debt` date DEFAULT NULL,
  `value_pending` float NOT NULL,
  `state` int(11) DEFAULT NULL,
  `days` int(11) NOT NULL DEFAULT 0,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `capital_value_proy` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credits_plans`
--

INSERT INTO `credits_plans` (`id`, `number`, `credit_id`, `credit_old`, `capital_value`, `capital_payment`, `capital_returned`, `interest_value`, `interest_payment`, `others_value`, `others_payment`, `others_add`, `interest_add`, `debt_add`, `dateini`, `deadline`, `date_payment`, `date_debt`, `value_pending`, `state`, `days`, `created`, `modified`, `capital_value_proy`) VALUES
(1, 1, 1, NULL, 142572, 0, 0, 5640, 0, 25620, 0, 0, 0, 0, '2022-08-08', '2022-09-08', NULL, NULL, 157428, 0, -6, '2022-08-08 14:29:18', '2022-08-08 14:29:18', 142572),
(2, 2, 1, NULL, 157428, 0, 0, 2960, 0, 13444, 0, 0, 0, 0, '2022-09-08', '2022-10-08', NULL, NULL, 0, 0, -36, '2022-08-08 14:29:18', '2022-08-08 14:29:18', 157428),
(3, 1, 2, NULL, 2000000, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2022-08-17', '2022-09-17', NULL, NULL, 0, 0, -15, '2022-08-17 14:36:34', '2022-08-17 14:36:34', 2000000),
(4, 1, 3, NULL, 5000000, 5000000, 0, 1, 1, 1, 1, 0, 0, 0, '2022-08-17', '2022-09-17', '2022-08-17', NULL, 0, 1, -31, '2022-08-17 16:28:12', '2022-08-17 16:28:12', 5000000),
(5, 1, 4, NULL, 2000000, 0, 0, 2, 0, 2, 0, 0, 0, 0, '2022-08-17', '2022-09-17', NULL, NULL, 0, 0, -15, '2022-08-17 17:33:13', '2022-08-22 16:56:47', 2000000),
(6, 1, 5, NULL, 5000000, 0, 0, 5, 0, 5, 0, 0, 0, 0, '2022-08-18', '2022-09-18', NULL, NULL, 0, 0, -16, '2022-08-18 17:46:35', '2022-08-18 17:46:35', 5000000),
(7, 1, 6, NULL, 2000000, 0, 0, 2, 0, 2, 0, 0, 0, 0, '2022-08-18', '2022-09-18', NULL, NULL, 0, 0, -16, '2022-08-18 18:02:07', '2022-08-18 18:02:07', 2000000),
(8, 1, 7, NULL, 2000000, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2022-09-02', '2022-10-02', NULL, NULL, 0, 0, -30, '2022-09-02 10:56:57', '2022-09-02 10:56:57', 2000000),
(9, 1, 8, NULL, 2000000, 0, 0, 0, 0, 0, 0, 0, 0, 0, '2022-09-02', '2022-10-02', NULL, NULL, 0, 0, -30, '2022-09-02 12:45:01', '2022-09-02 12:45:01', 2000000),
(10, 1, 9, NULL, 1000000, 1000000, 0, 0, 0, 0, 0, 0, 0, 0, '2022-09-02', '2022-10-02', '2022-09-02', NULL, 0, 1, -30, '2022-09-02 13:31:35', '2022-09-02 13:31:35', 1000000);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credits_requests`
--

CREATE TABLE `credits_requests` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `request_value` float NOT NULL,
  `request_number` int(11) NOT NULL,
  `request_type` int(11) NOT NULL DEFAULT 1,
  `credits_line_id` int(11) NOT NULL,
  `returned` int(11) NOT NULL DEFAULT 0,
  `extra` int(11) DEFAULT 0,
  `transfer` int(11) NOT NULL DEFAULT 0,
  `shop_commerce_id` int(11) NOT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_disbursed` int(11) DEFAULT NULL,
  `date_admin` datetime DEFAULT NULL,
  `reason_reject` varchar(255) DEFAULT NULL,
  `total_score` float DEFAULT NULL,
  `vars_score` longtext DEFAULT NULL,
  `response_score` longtext DEFAULT NULL,
  `number_approve` int(11) DEFAULT NULL,
  `value_approve` int(11) DEFAULT NULL,
  `date_disbursed` datetime DEFAULT NULL,
  `value_disbursed` float DEFAULT NULL,
  `credit_id` bigint(20) DEFAULT NULL,
  `simulator_id` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credits_requests`
--

INSERT INTO `credits_requests` (`id`, `customer_id`, `request_value`, `request_number`, `request_type`, `credits_line_id`, `returned`, `extra`, `transfer`, `shop_commerce_id`, `empresa_id`, `user_id`, `user_disbursed`, `date_admin`, `reason_reject`, `total_score`, `vars_score`, `response_score`, `number_approve`, `value_approve`, `date_disbursed`, `value_disbursed`, `credit_id`, `simulator_id`, `state`, `created`, `modified`) VALUES
(1, 1, 300000, 2, 1, 5, 1, 0, 0, 35, NULL, 1305, NULL, '2022-08-08 14:27:51', NULL, NULL, NULL, NULL, NULL, 300000, NULL, NULL, NULL, NULL, 3, '2022-08-08 14:26:47', '2022-08-08 14:27:51'),
(2, 2, 500000, 3, 1, 5, 0, 0, 0, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-08-12 12:07:38', '2022-08-12 12:07:38'),
(3, 3, 523900, 1, 1, 5, 0, 0, 0, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-08-12 15:17:30', '2022-08-12 15:17:30'),
(4, 4, 523900, 3, 1, 5, 0, 0, 0, 35, NULL, 1305, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-08-13 10:21:41', '2022-08-13 10:21:41'),
(5, 5, 550000, 7, 1, 5, 0, 0, 0, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-08-13 13:17:37', '2022-08-13 13:17:37'),
(6, 6, 231500, 3, 1, 5, 0, 0, 0, 35, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-08-17 12:50:17', '2022-08-17 12:50:17'),
(7, 7, 30000, 3, 1, 5, 0, 0, 0, 289, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '2022-08-17 13:11:37', '2022-08-17 13:11:37'),
(8, 8, 500000, 1, 1, 7, 0, 0, 0, 289, NULL, 1305, NULL, '2022-08-17 14:31:37', NULL, NULL, NULL, NULL, NULL, 5000000, NULL, NULL, NULL, NULL, 7, '2022-08-17 14:25:45', '2022-08-17 14:31:37'),
(9, 8, 2000000, 4, 1, 7, 0, 0, 1, 35, NULL, 1305, 10050, '2022-08-17 14:35:07', NULL, NULL, NULL, NULL, 4, 2000000, '2022-08-17 14:36:34', 2000000, 2, NULL, 5, '2022-08-17 14:35:07', '2022-08-17 14:35:07'),
(10, 9, 5000000, 1, 1, 7, 0, 0, 0, 289, NULL, 1305, NULL, '2022-08-17 16:25:25', NULL, NULL, NULL, NULL, NULL, 5000000, NULL, NULL, NULL, NULL, 7, '2022-08-17 16:23:39', '2022-08-17 16:25:25'),
(11, 9, 5000000, 4, 1, 7, 0, 0, 1, 289, NULL, 1305, NULL, '2022-08-17 16:27:13', NULL, NULL, NULL, NULL, 4, 5000000, NULL, NULL, NULL, NULL, 7, '2022-08-17 16:27:13', '2022-08-17 16:27:13'),
(12, 9, 5000000, 4, 1, 7, 0, 0, 1, 289, NULL, 1305, 10055, '2022-08-17 16:27:19', NULL, NULL, NULL, NULL, 4, 5000000, '2022-08-17 16:28:12', 5000000, 3, NULL, 5, '2022-08-17 16:27:19', '2022-08-17 16:27:19'),
(13, 9, 1000000, 1, 1, 7, 0, 1, 0, 289, NULL, 1305, NULL, '2022-08-18 18:46:51', NULL, NULL, NULL, NULL, NULL, 1000000, NULL, NULL, NULL, NULL, 3, '2022-08-17 17:01:27', '2022-08-18 18:46:51'),
(14, 9, 5000000, 4, 1, 7, 0, 0, 0, 289, NULL, 1305, NULL, '2022-08-17 17:24:25', NULL, NULL, NULL, NULL, 4, 5000000, NULL, NULL, NULL, NULL, 7, '2022-08-17 17:24:25', '2022-08-17 17:24:25'),
(15, 9, 5000000, 4, 1, 7, 0, 0, 0, 289, NULL, 1305, NULL, '2022-08-17 17:30:07', NULL, NULL, NULL, NULL, 4, 5000000, NULL, NULL, NULL, NULL, 7, '2022-08-17 17:30:07', '2022-08-17 17:30:07'),
(16, 9, 5000000, 4, 1, 7, 0, 0, 0, 289, NULL, 1305, 10057, '2022-08-17 17:30:17', NULL, NULL, NULL, NULL, 4, 5000000, '2022-08-17 17:33:13', 2000000, 4, NULL, 5, '2022-08-17 17:30:17', '2022-08-17 17:30:17'),
(17, 9, 3000000, 4, 1, 7, 0, 0, 0, 289, NULL, 1305, NULL, '2022-08-17 17:34:53', NULL, NULL, NULL, NULL, 4, 3000000, NULL, NULL, NULL, NULL, 7, '2022-08-17 17:34:53', '2022-08-17 17:34:53'),
(18, 10, 10000000, 1, 1, 7, 0, 0, 0, 290, NULL, 1305, NULL, '2022-08-18 17:37:53', NULL, NULL, NULL, NULL, NULL, 5000000, NULL, NULL, NULL, NULL, 7, '2022-08-18 17:31:59', '2022-08-18 17:37:53'),
(19, 10, 5000000, 4, 1, 7, 0, 0, 1, 289, NULL, 1305, 10062, '2022-08-18 17:44:57', NULL, NULL, NULL, NULL, 4, 5000000, '2022-08-18 17:46:35', 5000000, 5, NULL, 5, '2022-08-18 17:44:57', '2022-08-18 17:44:57'),
(20, 9, 3000000, 4, 1, 7, 0, 0, 0, 290, NULL, 1305, 10061, '2022-08-18 18:00:28', NULL, NULL, NULL, NULL, 4, 3000000, '2022-08-18 18:02:07', 2000000, 6, NULL, 5, '2022-08-18 18:00:28', '2022-08-18 18:00:28'),
(21, 11, 3000000, 1, 1, 7, 0, 0, 0, 35, NULL, 1305, NULL, '2022-09-02 10:24:43', NULL, NULL, NULL, NULL, NULL, 4000000, NULL, NULL, NULL, NULL, 7, '2022-09-02 10:18:56', '2022-09-02 10:24:43'),
(22, 11, 4000000, 4, 1, 7, 0, 0, 0, 289, NULL, 1305, 10052, '2022-09-02 10:54:49', NULL, NULL, NULL, NULL, 4, 4000000, '2022-09-02 10:56:57', 2000000, 7, NULL, 5, '2022-09-02 10:54:49', '2022-09-02 10:54:49'),
(23, 11, 10000000, 1, 1, 7, 0, 1, 0, 289, NULL, 1305, NULL, '2022-09-02 13:29:41', NULL, NULL, NULL, NULL, NULL, 10000000, NULL, NULL, NULL, NULL, 7, '2022-09-02 12:01:28', '2022-09-02 13:29:41'),
(24, 12, 3250000, 1, 1, 7, 0, 0, 0, 290, NULL, 1305, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2022-09-02 12:19:44', '2022-09-02 12:19:44'),
(25, 11, 2000000, 4, 1, 7, 0, 0, 1, 290, NULL, 1305, 10066, '2022-09-02 12:42:55', NULL, NULL, NULL, NULL, 4, 2000000, '2022-09-02 12:45:01', 2000000, 8, NULL, 5, '2022-09-02 12:42:55', '2022-09-02 12:42:55'),
(26, 11, 1000000, 4, 1, 7, 0, 0, 1, 289, NULL, 1305, 10066, '2022-09-02 13:30:54', NULL, NULL, NULL, NULL, 4, 1000000, '2022-09-02 13:31:35', 1000000, 9, NULL, 5, '2022-09-02 13:30:54', '2022-09-02 13:30:54'),
(27, 11, 1000000, 4, 1, 7, 0, 0, 1, 289, NULL, 1305, NULL, '2022-09-02 13:51:42', NULL, NULL, NULL, NULL, 4, 1000000, NULL, NULL, NULL, NULL, 7, '2022-09-02 13:51:42', '2022-09-02 13:51:42'),
(28, 11, 10000000, 4, 1, 7, 0, 0, 0, 289, NULL, 1305, NULL, '2022-09-02 14:33:55', NULL, NULL, NULL, NULL, 4, 10000000, NULL, NULL, NULL, NULL, 3, '2022-09-02 14:33:55', '2022-09-02 14:33:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credits_requests_comments`
--

CREATE TABLE `credits_requests_comments` (
  `id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `credits_request_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credits_requests_comments`
--

INSERT INTO `credits_requests_comments` (`id`, `type`, `comment`, `credits_request_id`, `user_id`, `created`, `modified`) VALUES
(1, 'Hay problemas con el documento de identidad', 'kjhkjkjhk', 8, 1305, '2022-08-17 14:30:19', '2022-08-17 14:30:19'),
(2, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 9, 1305, '2022-08-17 14:35:07', '2022-08-17 14:35:07'),
(3, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 11, 1305, '2022-08-17 16:27:13', '2022-08-17 16:27:13'),
(4, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 12, 1305, '2022-08-17 16:27:19', '2022-08-17 16:27:19'),
(5, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 14, 1305, '2022-08-17 17:24:25', '2022-08-17 17:24:25'),
(6, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 15, 1305, '2022-08-17 17:30:07', '2022-08-17 17:30:07'),
(7, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 16, 1305, '2022-08-17 17:30:17', '2022-08-17 17:30:17'),
(8, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 17, 1305, '2022-08-17 17:34:53', '2022-08-17 17:34:53'),
(9, 'Las fotos del documento no son legibles', 'comerctarios de pruebas', 18, 1305, '2022-08-18 17:35:36', '2022-08-18 17:35:36'),
(10, 'Devolución de crédito', 'fue un error', 18, 1305, '2022-08-18 17:37:30', '2022-08-18 17:37:30'),
(11, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 19, 1305, '2022-08-18 17:44:57', '2022-08-18 17:44:57'),
(12, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 20, 1305, '2022-08-18 18:00:28', '2022-08-18 18:00:28'),
(13, 'Devolución de crédito', 'prueba', 1, 422, '2022-08-22 16:56:14', '2022-08-22 16:56:14'),
(14, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 22, 1305, '2022-09-02 10:54:49', '2022-09-02 10:54:49'),
(15, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 25, 1305, '2022-09-02 12:42:55', '2022-09-02 12:42:55'),
(16, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 26, 1305, '2022-09-02 13:30:54', '2022-09-02 13:30:54'),
(17, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 27, 1305, '2022-09-02 13:51:42', '2022-09-02 13:51:42'),
(18, 'Se aprueba por preaprobado', 'Se aprueba por preaprobado', 28, 1305, '2022-09-02 14:33:55', '2022-09-02 14:33:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `credit_limits`
--

CREATE TABLE `credit_limits` (
  `id` int(11) NOT NULL,
  `value` float NOT NULL,
  `type_movement` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `reason` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `credit_id` bigint(20) DEFAULT NULL,
  `credits_request_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `credit_request_final` int(11) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `active` int(11) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `credit_limits`
--

INSERT INTO `credit_limits` (`id`, `value`, `type_movement`, `state`, `reason`, `deadline`, `credit_id`, `credits_request_id`, `user_id`, `customer_id`, `credit_request_final`, `payment_id`, `active`, `created`, `modified`) VALUES
(1, 300000, 1, 6, 'Aprobación de cupo', '2022-09-08', NULL, 1, 1305, 1, NULL, NULL, 1, '2022-08-08 14:27:51', '2022-08-08 14:27:51'),
(2, 300000, 2, 6, 'Desembolso de cupo', '2022-10-08', 1, 1, 10041, 1, NULL, NULL, 1, '2022-08-08 14:29:18', '2022-08-08 14:29:18'),
(3, 5000000, 1, 6, 'Aprobación de cupo', '2022-09-17', NULL, 8, 1305, 8, NULL, NULL, 1, '2022-08-17 14:31:37', '2022-08-17 14:31:37'),
(4, 2000000, 2, 6, 'Desembolso de cupo', '2022-09-17', 2, 9, 10050, 8, NULL, NULL, 1, '2022-08-17 14:36:34', '2022-08-17 14:36:34'),
(5, 3000000, 1, 5, 'Preaprobado por restante de solicitud', '2022-09-17', NULL, NULL, 10050, 8, NULL, NULL, 1, '2022-08-17 14:36:34', '2022-08-17 14:36:34'),
(6, 5000000, 1, 6, 'Aprobación de cupo', '2022-09-17', NULL, 10, 1305, 9, NULL, NULL, 1, '2022-08-17 16:25:25', '2022-08-17 16:25:25'),
(7, 5000000, 2, 6, 'Desembolso de cupo', '2022-09-17', 3, 12, 10055, 9, NULL, NULL, 1, '2022-08-17 16:28:12', '2022-08-17 16:28:12'),
(8, 5000000, 1, 6, 'Preaprobado por restante de solicitud', '2022-09-23', 3, 12, 10052, 9, NULL, 3, 0, '2022-08-17 16:57:52', '2022-08-17 16:57:52'),
(9, 5000000, 1, 6, 'Preaprobado de cupo', '2022-09-17', NULL, 14, 10057, 9, NULL, NULL, 0, '2022-08-17 17:24:25', '2022-08-17 17:24:25'),
(10, 5000000, 2, 8, 'Preaprobado de cupo', '2022-09-17', NULL, 14, 10057, 9, NULL, NULL, 1, '2022-08-17 17:24:25', '2022-08-17 17:24:25'),
(11, 5000000, 1, 6, 'Preaprobado de cupo', '2022-09-17', NULL, 15, 10057, 9, NULL, NULL, 0, '2022-08-17 17:30:07', '2022-08-17 17:30:07'),
(12, 5000000, 2, 8, 'Preaprobado de cupo', '2022-09-17', NULL, 15, 10057, 9, NULL, NULL, 1, '2022-08-17 17:30:07', '2022-08-17 17:30:07'),
(13, 5000000, 1, 6, 'Preaprobado de cupo', '2022-09-17', NULL, 16, 10057, 9, NULL, NULL, 1, '2022-08-17 17:30:17', '2022-08-17 17:30:17'),
(14, 5000000, 2, 8, 'Preaprobado de cupo', '2022-09-17', NULL, 16, 10057, 9, NULL, NULL, 1, '2022-08-17 17:30:17', '2022-08-17 17:30:17'),
(15, 2000000, 2, 6, 'Desembolso de cupo', '2022-09-17', 4, 16, 10057, 9, NULL, NULL, 1, '2022-08-17 17:33:13', '2022-08-17 17:33:13'),
(16, 3000000, 1, 6, 'Preaprobado por restante de solicitud', '2022-09-17', NULL, NULL, 10057, 9, NULL, NULL, 0, '2022-08-17 17:33:13', '2022-08-17 17:33:13'),
(17, 3000000, 1, 6, 'Preaprobado de cupo', '2022-09-17', NULL, 17, 10057, 9, NULL, NULL, 0, '2022-08-17 17:34:53', '2022-08-17 17:34:53'),
(18, 3000000, 2, 8, 'Preaprobado de cupo', '2022-09-17', NULL, 17, 10057, 9, NULL, NULL, 1, '2022-08-17 17:34:53', '2022-08-17 17:34:53'),
(19, 5000000, 1, 6, 'Aprobación de cupo', '2022-09-18', NULL, 18, 1305, 10, NULL, NULL, 1, '2022-08-18 17:37:53', '2022-08-18 17:37:53'),
(20, 5000000, 2, 6, 'Desembolso de cupo', '2022-09-18', 5, 19, 10062, 10, NULL, NULL, 1, '2022-08-18 17:46:35', '2022-08-18 17:46:35'),
(21, 3000000, 1, 6, 'Preaprobado de cupo', '2022-09-18', NULL, 20, 10061, 9, NULL, NULL, 1, '2022-08-18 18:00:28', '2022-08-18 18:00:28'),
(22, 3000000, 2, 8, 'Preaprobado de cupo', '2022-09-18', NULL, 20, 10061, 9, NULL, NULL, 1, '2022-08-18 18:00:28', '2022-08-18 18:00:28'),
(23, 2000000, 2, 6, 'Desembolso de cupo', '2022-09-18', 6, 20, 10061, 9, NULL, NULL, 1, '2022-08-18 18:02:07', '2022-08-18 18:02:07'),
(24, 1000000, 1, 5, 'Preaprobado por restante de solicitud', '2022-09-18', NULL, NULL, 10061, 9, NULL, NULL, 1, '2022-08-18 18:02:07', '2022-08-18 18:02:07'),
(28, 4000000, 1, 6, 'Aprobación de cupo', '2022-10-02', NULL, 21, 1305, 11, NULL, NULL, 0, '2022-09-02 10:24:43', '2022-09-02 10:24:43'),
(26, 1000000, 1, 1, 'Aprobación de cupo', '2022-09-18', NULL, 13, 1305, 9, NULL, NULL, 1, '2022-08-18 18:46:51', '2022-08-18 18:46:51'),
(27, 300000, 1, 1, 'Aprobación de cupo automático por devolución desde soporte', '2022-09-22', NULL, 1, 422, 1, NULL, NULL, 1, '2022-08-22 16:56:14', '2022-08-22 16:56:14'),
(29, 4000000, 1, 6, 'Preaprobado de cupo', '2022-10-02', NULL, 22, 10052, 11, NULL, NULL, 1, '2022-09-02 10:54:49', '2022-09-02 10:54:49'),
(30, 4000000, 2, 8, 'Preaprobado de cupo', '2022-10-02', NULL, 22, 10052, 11, NULL, NULL, 1, '2022-09-02 10:54:49', '2022-09-02 10:54:49'),
(31, 2000000, 2, 6, 'Desembolso de cupo', '2022-10-02', 7, 22, 10052, 11, NULL, NULL, 1, '2022-09-02 10:56:57', '2022-09-02 10:56:57'),
(32, 2000000, 1, 6, 'Preaprobado por restante de solicitud', '2022-10-02', NULL, NULL, 10052, 11, NULL, NULL, 1, '2022-09-02 10:56:57', '2022-09-02 10:56:57'),
(33, 2000000, 2, 6, 'Desembolso de cupo', '2022-10-02', 8, 25, 10066, 11, NULL, NULL, 1, '2022-09-02 12:45:01', '2022-09-02 12:45:01'),
(34, 10000000, 1, 6, 'Aprobación de cupo', '2022-10-02', NULL, 23, 1305, 11, NULL, NULL, 1, '2022-09-02 13:29:41', '2022-09-02 13:29:41'),
(35, 1000000, 2, 6, 'Desembolso de cupo', '2022-10-02', 9, 26, 10066, 11, NULL, NULL, 1, '2022-09-02 13:31:35', '2022-09-02 13:31:35'),
(36, 9000000, 1, 5, 'Preaprobado por restante de solicitud', '2022-10-02', NULL, NULL, 10066, 11, NULL, NULL, 0, '2022-09-02 13:31:35', '2022-09-02 13:31:35'),
(37, 1000000, 1, 5, 'Preaprobado por restante de solicitud', '2022-10-09', 9, 26, 10052, 11, NULL, 8, 0, '2022-09-02 13:33:44', '2022-09-02 13:33:44'),
(38, 10000000, 1, 5, 'Preaprobado de cupo', '2022-10-02', NULL, 28, 10048, 11, NULL, NULL, 1, '2022-09-02 14:33:55', '2022-09-02 14:33:55'),
(39, 10000000, 2, 8, 'Preaprobado de cupo', '2022-10-02', NULL, 28, 10048, 11, NULL, NULL, 1, '2022-09-02 14:33:55', '2022-09-02 14:33:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `nit` varchar(45) DEFAULT NULL,
  `buss_name` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `last_name` varchar(45) DEFAULT NULL,
  `identification_type` varchar(45) NOT NULL,
  `identification` varchar(45) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `identification_place` varchar(255) DEFAULT NULL,
  `document_file_up` varchar(255) DEFAULT NULL,
  `document_file_down` varchar(255) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `url_files` varchar(255) DEFAULT NULL,
  `cci` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tyc` int(11) NOT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `date_birth` date DEFAULT NULL,
  `city_birth` varchar(45) DEFAULT NULL,
  `identification_date` date DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `profession` text DEFAULT NULL,
  `serv_name` varchar(45) DEFAULT NULL,
  `monthly_income` float DEFAULT NULL,
  `monthly_expenses` float DEFAULT NULL,
  `type_contract` varchar(255) DEFAULT NULL,
  `politics` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `data_full` int(11) NOT NULL DEFAULT 0,
  `total_datacredito` float DEFAULT NULL,
  `vars_datacredito` text DEFAULT NULL,
  `response_datacredito` text DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `code` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `customers`
--

INSERT INTO `customers` (`id`, `nit`, `buss_name`, `name`, `last_name`, `identification_type`, `identification`, `email`, `identification_place`, `document_file_up`, `document_file_down`, `image_file`, `url_files`, `cci`, `tyc`, `gender`, `date_birth`, `city_birth`, `identification_date`, `occupation`, `profession`, `serv_name`, `monthly_income`, `monthly_expenses`, `type_contract`, `politics`, `state`, `data_full`, `total_datacredito`, `vars_datacredito`, `response_datacredito`, `created`, `modified`, `user_id`, `type`, `code`) VALUES
(1, NULL, NULL, 'Maria', 'Flores', 'CC', '12345678', 'mf27950@gmail.com', NULL, 'Customer_1659986755_62f1634371dd7_1659986755.jpg', 'Customer_1659986755_62f1634371e38_1659986755.jpg', 'Customer_1659986755_62f1634371e89_1659986755.jpg', NULL, '0', 1, NULL, '2001-08-08', NULL, NULL, 'Independiente', NULL, NULL, 72737, NULL, 'Indefinido', 1, 1, 1, NULL, NULL, NULL, '2022-08-08 14:25:55', '2022-08-08 14:26:44', NULL, 0, 73221084),
(2, NULL, NULL, 'Maria', 'Flores', 'CC', '984942889', 'pppp@gmail.com', NULL, 'Customer_1660324058_62f688da202ba_1660324058.jpg', 'Customer_1660324058_62f688da20321_1660324058.jpg', 'Customer_1660324058_62f688da2037c_1660324058.jpg', NULL, '0', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 54334, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-12 12:07:38', '2022-08-12 12:07:38', NULL, 0, 73221084),
(3, NULL, NULL, 'Maria', 'Flores', 'CC', '56789789', 'adjjjmin@gmail.com', NULL, 'Customer_1660335450_62f6b55a51bc6_1660335450.jpg', 'Customer_1660335450_62f6b55a51c4e_1660335450.jpg', 'Customer_1660335450_62f6b55a51cb1_1660335450.jpg', NULL, '0', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 67890, NULL, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-12 15:17:30', '2022-08-12 15:17:30', NULL, 0, 73221084),
(4, '38498439', 'lmknknk', 'Mariank', 'nkknknk', 'CC', '67677677', 'adnknkmin@gmail.com', NULL, 'Customer_1660404101_62f7c185b4467_1660404101.jpg', 'Customer_1660404101_62f7c185b44d0_1660404101.jpg', 'Customer_1660404101_62f7c185b4523_1660404101.jpg', NULL, '0', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'jbkjbjbkjbj', 7676680, 8888790, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-13 10:21:41', '2022-08-13 10:21:41', NULL, 0, 73221084),
(5, '123456789', 'Prueba Uno', 'Prueba Cliente', 'Credito', 'CE', '84548488', NULL, NULL, 'Customer_1660414657_62f7eac171d1f_1660414657.jpg', 'Customer_1660414657_62f7eac171d86_1660414657.jpg', 'Customer_1660414657_62f7eac171dd8_1660414657.jpg', NULL, '0', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'paginawe.com', 1000000, 900000, NULL, NULL, 1, 0, NULL, NULL, NULL, '2022-08-13 13:17:37', '2022-08-13 13:17:37', NULL, 0, 73221084),
(6, '9339388', 'Empresa', 'Nombre ', 'Apellido', 'CC', '8238388', 'mf2jjj7950@gmail.com', NULL, 'Customer_1660758617_62fd2a598b710_1660758617.jpg', 'Customer_1660758617_62fd2a598b778_1660758617.jpg', 'Customer_1660758617_62fd2a598b7cc_1660758617.jpg', NULL, 'no', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Pagina.com', 72377, 833778, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-17 12:50:17', '2022-08-17 12:50:17', NULL, 0, 73221084),
(7, '838383838', 'Prueba', 'Bzzbzb', 'Jwjwjwj', 'CC', '82838338', 'mfbhbb27950@gmail.com', NULL, 'Customer_1660759897_62fd2f59b4399_1660759897.jpg', 'Customer_1660759897_62fd2f59b4405_1660759897.jpg', 'Customer_1660759897_62fd2f59b445c_1660759897.jpg', NULL, 'si', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'Jsnsjs.Com', 73737, 7373740, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-17 13:11:37', '2022-08-17 13:11:37', NULL, 0, 63256894),
(8, '0898080980', 'daniel ', 'daniel', 'villa', 'CC', '1232323123', 'johngomezlondono@gmail.com', NULL, 'Customer_1660764345_62fd40b93a1f6_1660764345.jpg', 'Customer_1660764345_62fd40b93a27d_1660764345.jpg', 'Customer_1660764345_62fd40b93a2ea_1660764345.jpg', NULL, 'si', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'cred.com', 1200000, 1200000, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-17 14:25:45', '2022-08-17 14:25:45', NULL, 0, 63256894),
(9, '121342984', 'xd', 'juan', 'perez', 'CC', '1022978371', 'juancacreativo@outlook.com', NULL, 'Customer_1660771419_62fd5c5ba02c5_1660771419.jpg', 'Customer_1660771419_62fd5c5ba0329_1660771419.jpg', 'Customer_1660771419_62fd5c5ba037d_1660771419.jpg', NULL, 'si', 1, NULL, NULL, NULL, NULL, NULL, NULL, 'www.com-.com', 5000000, 2000000, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-17 16:23:39', '2022-08-18 18:46:06', NULL, 1, 63256894),
(10, '51515151', 'Tienda Pepito perez nana', 'John', 'Gomez', 'CC', '10537666666', 'john@gmail.com', NULL, 'Customer_1660861919_62febddf71eee_1660861919.jpg', 'Customer_1660861919_62febddf71f5f_1660861919.jpg', 'Customer_1660861919_62febddf71fb1_1660861919.jpg', NULL, 'si', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'wwweee', 123555, 22222, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-08-18 17:31:59', '2022-08-18 17:34:02', NULL, 0, 20782499),
(11, '901592209-1', 'Somos Ziro SAS', 'John', 'Gómez Londoño', 'CC', '1053764598', 'jagomezlondono@gmail.com', NULL, 'Customer_1662131936_63121ee0e2cae_1662131936.jpg', 'Customer_1662131936_63121ee0e2d26_1662131936.jpg', 'Customer_1662131936_63121ee0e2d8d_1662131936.jpg', NULL, 'si', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'www.somosziro.com', 20000000, 10000000, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-09-02 10:18:56', '2022-09-02 10:18:56', NULL, 0, 73221084),
(12, '1212121', 'Prueba', 'asasa', 'asa', 'CC', '111111111111', 'credito33@gmail.com', NULL, 'Customer_1662139184_63123b30b2caa_1662139184.jpg', 'Customer_1662139184_63123b30b2d19_1662139184.jpg', 'Customer_1662139184_63123b30b2d6f_1662139184.jpg', NULL, 'si', 0, NULL, NULL, NULL, NULL, NULL, NULL, 'asas', 100, 100, NULL, 1, 1, 1, NULL, NULL, NULL, '2022-09-02 12:19:44', '2022-09-02 12:19:44', NULL, 0, 20782499);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers_addresses`
--

CREATE TABLE `customers_addresses` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `address_type` int(11) NOT NULL DEFAULT 1,
  `address` varchar(255) NOT NULL,
  `address_city` varchar(255) NOT NULL,
  `address_street` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `customers_addresses`
--

INSERT INTO `customers_addresses` (`id`, `customer_id`, `address_type`, `address`, `address_city`, `address_street`, `state`, `created`, `modified`) VALUES
(1, 1, 1, 'Pradera, Floresta', 'LETICIA', 'Pradera, Floresta', 1, '2022-08-08 14:26:44', '2022-08-08 14:26:44'),
(2, 3, 1, 'Direccion', 'LETICIA', 'Direccion', 1, '2022-08-12 15:17:30', '2022-08-12 15:17:30'),
(3, 4, 1, 'jnkhkjhkj', 'LETICIA', 'kbkbjkjb', 1, '2022-08-13 10:21:41', '2022-08-13 10:21:41'),
(4, 5, 1, 'Direccion cliente', 'LA VICTORIA', 'Direccion empresa', 1, '2022-08-13 13:17:37', '2022-08-13 13:17:37'),
(5, 6, 1, 'Pradera, Floresta', 'LETICIA', 'Pradera, Floresta', 1, '2022-08-17 12:50:17', '2022-08-17 12:50:17'),
(6, 7, 1, 'Djdjdjj', 'LETICIA', 'Pradera, Floresta', 1, '2022-08-17 13:11:37', '2022-08-17 13:11:37'),
(7, 8, 1, 'aclle 50 ', 'ALEJANDRIA', 'calle 50 ', 1, '2022-08-17 14:25:45', '2022-08-17 14:25:45'),
(8, 9, 1, 'carera # 75 b 21 sur', 'BOGOTA DISTRITO CAPITAL', 'carrera 1 · 2 sur', 1, '2022-08-17 16:23:39', '2022-08-17 16:23:39'),
(9, 10, 1, 'Casa', 'LETICIA', 'sasasa', 1, '2022-08-18 17:31:59', '2022-08-18 17:31:59'),
(10, 0, 1, 'Casa', 'LETICIA', 'sasasa', 1, '2022-08-18 17:34:02', '2022-08-18 17:34:02'),
(11, 0, 1, 'carera # 75 b 21 sur', 'BOGOTA DISTRITO CAPITAL', 'carrera 1 · 2 sur', 1, '2022-08-18 18:46:06', '2022-08-18 18:46:06'),
(12, 11, 1, '0204', 'CALDAS', 'Av Santander 65-15 L.115', 1, '2022-09-02 10:18:56', '2022-09-02 10:18:56'),
(13, 12, 1, 'Mi casa', 'LETICIA', 'asas', 1, '2022-09-02 12:19:44', '2022-09-02 12:19:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers_codes`
--

CREATE TABLE `customers_codes` (
  `id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `deadline` bigint(20) NOT NULL,
  `type_code` int(11) NOT NULL,
  `credits_request_id` int(11) DEFAULT NULL,
  `ses_id` varchar(50) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `customers_codes`
--

INSERT INTO `customers_codes` (`id`, `code`, `customer_id`, `deadline`, `type_code`, `credits_request_id`, `ses_id`, `state`, `created`, `modified`) VALUES
(1, 590124, 1, 1659987496, 2, 1, NULL, 1, '2022-08-08 14:28:16', '2022-08-08 14:28:16'),
(2, 957972, 8, 1660765522, 2, 9, NULL, 1, '2022-08-17 14:35:22', '2022-08-17 14:35:22'),
(3, 407940, 9, 1660772249, 2, 11, NULL, 2, '2022-08-17 16:27:29', '2022-08-17 16:27:29'),
(4, 130977, 9, 1660772277, 2, 12, NULL, 2, '2022-08-17 16:27:57', '2022-08-17 16:27:57'),
(5, 268137, 9, 1660775703, 2, 14, NULL, 2, '2022-08-17 17:25:03', '2022-08-17 17:25:03'),
(6, 513417, 9, 1660776045, 2, 16, NULL, 1, '2022-08-17 17:30:45', '2022-08-17 17:30:45'),
(7, 339348, 9, 1660776306, 2, 17, NULL, 2, '2022-08-17 17:35:06', '2022-08-17 17:35:06'),
(8, 468782, 10, 1660863305, 2, 19, NULL, 1, '2022-08-18 17:45:05', '2022-08-18 17:45:05'),
(9, 645114, 9, 1660864246, 2, 20, NULL, 1, '2022-08-18 18:00:46', '2022-08-18 18:00:46'),
(10, 885734, 1, 1661206426, 2, 1, NULL, 1, '2022-08-22 17:03:46', '2022-08-22 17:03:46'),
(11, 788133, 11, 1662134740, 2, 22, NULL, 1, '2022-09-02 10:55:40', '2022-09-02 10:55:40'),
(12, 446037, 11, 1662141179, 2, 25, NULL, 1, '2022-09-02 12:42:59', '2022-09-02 12:42:59'),
(13, 302293, 11, 1662144060, 2, 26, NULL, 1, '2022-09-02 13:31:00', '2022-09-02 13:31:00'),
(14, 838992, 11, 1662145310, 2, 27, NULL, 1, '2022-09-02 13:51:50', '2022-09-02 13:51:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers_phones`
--

CREATE TABLE `customers_phones` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `phone_type` int(11) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `customers_phones`
--

INSERT INTO `customers_phones` (`id`, `customer_id`, `phone_type`, `phone_number`, `state`, `created`, `modified`) VALUES
(1, 1, 1, '3216110287', 1, '2022-08-08 14:26:44', '2022-08-08 14:26:44'),
(2, 2, 1, '3216110287', 1, '2022-08-12 12:07:38', '2022-08-12 12:07:38'),
(3, 3, 1, '3216110287', 1, '2022-08-12 15:17:30', '2022-08-12 15:17:30'),
(4, 4, 1, '3216110287', 1, '2022-08-13 10:21:41', '2022-08-13 10:21:41'),
(5, 5, 1, '3216110287', 1, '2022-08-13 13:17:37', '2022-08-13 13:17:37'),
(6, 6, 1, '3216110287', 1, '2022-08-17 12:50:17', '2022-08-17 12:50:17'),
(7, 7, 1, '3216110287', 1, '2022-08-17 13:11:37', '2022-08-17 13:11:37'),
(8, 8, 1, '3187312030', 1, '2022-08-17 14:25:45', '2022-08-17 14:25:45'),
(9, 9, 1, '3173038378', 1, '2022-08-17 16:23:39', '2022-08-18 18:46:06'),
(10, 10, 1, '3187302030', 1, '2022-08-18 17:31:59', '2022-08-18 17:34:02'),
(11, 11, 1, '3187312030', 1, '2022-09-02 10:18:56', '2022-09-02 10:18:56'),
(12, 12, 1, '3187312030', 1, '2022-09-02 12:19:44', '2022-09-02 12:19:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `customers_references`
--

CREATE TABLE `customers_references` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `relationship` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `reference_type` int(11) DEFAULT NULL,
  `state` int(11) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `customers_references`
--

INSERT INTO `customers_references` (`id`, `name`, `phone`, `customer_id`, `relationship`, `company`, `reference_type`, `state`, `created`, `modified`) VALUES
(1, 'Jjj', '3216110287', 1, 'Jjh', NULL, 1, 1, '2022-08-08 14:26:44', '2022-08-08 14:26:44'),
(2, 'Jhh', '3216110287', 1, 'Hh', NULL, 2, 1, '2022-08-08 14:26:44', '2022-08-08 14:26:44'),
(3, 'Bhh', '9888888', 1, 'Hhh', NULL, 3, 1, '2022-08-08 14:26:44', '2022-08-08 14:26:44'),
(4, 'gfd', '456789', 2, 'lknlnk', NULL, 1, 1, '2022-08-12 12:07:38', '2022-08-12 12:07:38'),
(5, 'bjhbhb', '567890', 2, 'jbhkn ,', NULL, 2, 1, '2022-08-12 12:07:38', '2022-08-12 12:07:38'),
(6, '', '', 0, NULL, NULL, NULL, 1, '2022-08-18 17:34:02', '2022-08-18 17:34:02'),
(7, '', '', 0, NULL, NULL, NULL, 1, '2022-08-18 18:46:06', '2022-08-18 18:46:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disbursements`
--

CREATE TABLE `disbursements` (
  `id` int(11) NOT NULL,
  `value` float NOT NULL,
  `credit_id` bigint(20) NOT NULL DEFAULT 0,
  `shop_commerce_id` int(11) NOT NULL,
  `shop_payment_request_id` int(11) DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `disbursements`
--

INSERT INTO `disbursements` (`id`, `value`, `credit_id`, `shop_commerce_id`, `shop_payment_request_id`, `state`, `created`, `modified`) VALUES
(7, 2000000, 7, 289, 4, 2, '2022-09-02 10:56:57', '2022-09-02 10:56:57'),
(2, 2000000, 2, 35, NULL, 1, '2022-08-17 14:36:34', '2022-08-17 14:36:34'),
(3, 5000000, 3, 289, 1, 3, '2022-08-17 16:28:12', '2022-08-17 16:28:12'),
(4, 2000000, 4, 289, 2, 2, '2022-08-17 17:33:13', '2022-08-17 17:33:13'),
(5, 5000000, 5, 289, NULL, 1, '2022-08-18 17:46:35', '2022-08-18 17:46:35'),
(6, 2000000, 6, 290, 3, 3, '2022-08-18 18:02:07', '2022-08-18 18:02:07'),
(8, 2000000, 8, 290, NULL, 1, '2022-09-02 12:45:01', '2022-09-02 12:45:01'),
(9, 1000000, 9, 289, NULL, 1, '2022-09-02 13:31:35', '2022-09-02 13:31:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `credits_request_id` int(11) NOT NULL,
  `file` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `type` int(11) NOT NULL DEFAULT 1,
  `state_request` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nit` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `social_reason` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `guild` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `account_bank` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `account_number` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `account_type` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `account_file` varchar(255) DEFAULT NULL,
  `chamber_commerce_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `rut_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `identification_account` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `name_admin` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `identification_admin` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `image_admin` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `cost_min` int(11) NOT NULL DEFAULT 1,
  `cost_max` int(11) NOT NULL DEFAULT 1,
  `payment_type` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `number_commerces` int(11) NOT NULL,
  `payment_total` float NOT NULL,
  `identification_up_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `identification_down_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `services_list` text CHARACTER SET utf8mb4 NOT NULL,
  `products_lists` text CHARACTER SET utf8mb4 NOT NULL,
  `phone` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `adviser` int(11) NOT NULL,
  `cellpone_admin` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_references`
--

CREATE TABLE `empresa_references` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `commerce` varchar(255) NOT NULL,
  `empresa_id` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `histories`
--

CREATE TABLE `histories` (
  `id` int(11) NOT NULL,
  `credits_plan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `type` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `money_collections`
--

CREATE TABLE `money_collections` (
  `id` int(11) NOT NULL,
  `value` float NOT NULL DEFAULT 0,
  `shop_commerce_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `payment_credit` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notes`
--

CREATE TABLE `notes` (
  `id` int(11) NOT NULL,
  `credits_plan_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `type` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notes_customers`
--

CREATE TABLE `notes_customers` (
  `id` int(11) NOT NULL,
  `credits_request_id` int(11) NOT NULL,
  `note` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `notes_customers`
--

INSERT INTO `notes_customers` (`id`, `credits_request_id`, `note`, `user_id`, `created`, `modified`) VALUES
(1, 18, 'Se edito la información del usuario', 1305, '2022-08-18 17:34:02', '2022-08-18 17:34:02'),
(2, 18, 'noatas depruebas', 1305, '2022-08-18 17:34:40', '2022-08-18 17:34:40'),
(3, 13, 'Se edito la información del usuario', 1305, '2022-08-18 18:46:06', '2022-08-18 18:46:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `credits_plan_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `shop_commerce_id` int(11) DEFAULT 0,
  `shop_payment_request_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `juridic` int(11) NOT NULL DEFAULT 0,
  `state` int(11) NOT NULL DEFAULT 0,
  `web` text DEFAULT NULL,
  `state_credishop` int(11) NOT NULL DEFAULT 0,
  `date_credishop` bigint(20) DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `temporal` tinyint(1) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `payments`
--

INSERT INTO `payments` (`id`, `credits_plan_id`, `value`, `user_id`, `shop_commerce_id`, `shop_payment_request_id`, `type`, `juridic`, `state`, `web`, `state_credishop`, `date_credishop`, `receipt_id`, `uid`, `created`, `modified`, `temporal`) VALUES
(1, 4, 1, 10052, 289, NULL, 3, 0, 0, NULL, 1, 1661207927, 1, '166077347262fd6460322f5', '2022-08-17 16:57:52', '2022-08-17 16:57:52', NULL),
(2, 4, 1, 10052, 289, NULL, 2, 0, 0, NULL, 1, 1661207927, 1, '166077347262fd6460322f5', '2022-08-17 16:57:52', '2022-08-17 16:57:52', NULL),
(3, 4, 5000000, 10052, 289, NULL, 1, 0, 0, NULL, 1, 1661207927, 1, '166077347262fd6460322f5', '2022-08-17 16:57:52', '2022-08-17 16:57:52', NULL),
(7, 8, 1500000, 10067, 289, NULL, 1, 0, 0, NULL, 0, NULL, 3, '166213574563122dc1435ed', '2022-09-02 11:22:25', '2022-09-02 11:22:25', NULL),
(8, 10, 1000000, 10052, 289, NULL, 1, 0, 0, NULL, 0, NULL, 4, '166214362363124c87f1aa5', '2022-09-02 13:33:44', '2022-09-02 13:33:44', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `value` float NOT NULL DEFAULT 0,
  `credits_plan_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT 0,
  `shop_commerce_id` int(11) DEFAULT NULL,
  `ext` int(11) NOT NULL DEFAULT 0,
  `state` int(11) NOT NULL DEFAULT 1,
  `state_credit` int(11) DEFAULT NULL,
  `saldo` float NOT NULL DEFAULT 0,
  `disponible` float NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `receipts`
--

INSERT INTO `receipts` (`id`, `value`, `credits_plan_id`, `user_id`, `shop_commerce_id`, `ext`, `state`, `state_credit`, `saldo`, `disponible`, `created`, `modified`) VALUES
(1, 5000000, 4, 10052, 289, 0, 1, 1, 0, 5000000, '2022-08-17 16:57:57', '2022-08-17 16:57:57'),
(3, 1500000, 8, 10067, 289, 0, 1, 0, 500000, 2000000, '2022-09-02 11:22:30', '2022-09-02 11:22:30'),
(4, 1000000, 10, 10052, 289, 0, 1, 1, 0, 10000000, '2022-09-02 13:33:49', '2022-09-02 13:33:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repayments`
--

CREATE TABLE `repayments` (
  `id` int(11) NOT NULL,
  `credits_plan_id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop_commerce_id` int(11) DEFAULT 0,
  `shop_payment_request_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `juridic` int(11) NOT NULL DEFAULT 0,
  `state` int(11) NOT NULL DEFAULT 0,
  `state_credishop` int(11) NOT NULL DEFAULT 0,
  `date_credishop` bigint(20) DEFAULT NULL,
  `receipt_id` int(11) DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `repayments`
--

INSERT INTO `repayments` (`id`, `credits_plan_id`, `value`, `user_id`, `shop_commerce_id`, `shop_payment_request_id`, `type`, `juridic`, `state`, `state_credishop`, `date_credishop`, `receipt_id`, `created`, `modified`) VALUES
(4, 5, 2, 10061, 290, NULL, 3, 0, 0, 1, 1660864895, 2, '2022-08-18 18:03:38', '2022-08-18 18:03:38'),
(5, 5, 2, 10061, 290, NULL, 2, 0, 0, 1, 1660864895, 2, '2022-08-18 18:03:38', '2022-08-18 18:03:38'),
(6, 5, 2000000, 10061, 290, NULL, 1, 0, 0, 1, 1660864895, 2, '2022-08-18 18:03:38', '2022-08-18 18:03:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `identification` int(11) NOT NULL,
  `shop_commerce_id` int(11) NOT NULL,
  `code` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `state_request_payment` int(11) DEFAULT NULL,
  `requests_detail_id` int(11) DEFAULT NULL,
  `requests_payment_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `date_payment` date DEFAULT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `requests`
--

INSERT INTO `requests` (`id`, `identification`, `shop_commerce_id`, `code`, `value`, `state`, `state_request_payment`, `requests_detail_id`, `requests_payment_id`, `user_id`, `date_payment`, `created`, `modified`) VALUES
(1, 9898, 4, 735931, 10000, 0, NULL, NULL, NULL, 132, NULL, '2021-02-09 11:54:50', '2021-02-09 11:54:50'),
(2, 71775067, 4, 538965, 15000, 1, 1, 1, 1, 132, '2021-02-09', '2021-02-09 12:40:29', '2021-02-09 12:40:29'),
(3, 43023596, 4, 143172, 11000, 1, 1, 2, 2, 57, '2021-02-13', '2021-02-12 15:40:00', '2021-02-12 15:40:00'),
(4, 98772500, 4, 404195, 16000, 0, NULL, NULL, NULL, 132, NULL, '2021-02-16 10:45:06', '2021-02-16 10:45:06'),
(5, 91120123, 4, 432996, 10500, 1, 1, 3, 3, 132, '2021-02-24', '2021-02-24 17:19:02', '2021-02-24 17:19:02'),
(6, 43559610, 4, 929442, 12000, 1, 1, 4, 4, 132, '2021-03-15', '2021-03-15 14:47:51', '2021-03-15 14:55:38'),
(7, 1000895416, 4, 266951, 11900, 0, NULL, NULL, NULL, 132, NULL, '2021-05-29 13:51:55', '2021-05-29 13:51:55'),
(8, 8246106, 4, 964412, 19000, 0, NULL, NULL, NULL, 132, NULL, '2021-09-27 18:42:52', '2021-09-27 18:42:52'),
(9, 71775067, 4, 209906, 11100, 1, 1, 5, 5, 132, '2022-01-27', '2022-01-27 18:11:45', '2022-01-27 18:11:45'),
(10, 25799818, 4, 423717, 10500, 1, 1, 6, 6, 132, '2022-04-07', '2022-04-07 16:04:28', '2022-04-07 16:04:28'),
(11, 1035520268, 4, 176479, 109400, 1, NULL, 7, NULL, 132, '2022-04-08', '2022-04-08 19:10:27', '2022-04-08 19:10:27'),
(12, 220722, 43, 848493, 10000, 0, NULL, NULL, NULL, 1144, NULL, '2022-07-22 18:42:57', '2022-07-22 18:42:57');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requests_details`
--

CREATE TABLE `requests_details` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `state_payment` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `response` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `requests_details`
--

INSERT INTO `requests_details` (`id`, `request_id`, `state_payment`, `value`, `response`, `created`, `modified`) VALUES
(1, 2, 1, 15000, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"39981627\",\"x_id_factura\":\"20210209124117\",\"x_id_invoice\":\"20210209124117\",\"x_description\":\"Pago a comercio c\\u00c3\\u00b3digo:538965\",\"x_amount\":\"15000\",\"x_amount_country\":\"15000\",\"x_amount_ok\":\"15000\",\"x_tax\":\"0\",\"x_amount_base\":\"0\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"BANCOLOMBIA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"887913944\",\"x_transaction_id\":\"48771612892599\",\"x_fecha_transaccion\":\"2021-02-09 12:43:19\",\"x_transaction_date\":\"2021-02-09 12:43:19\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"PSE\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"71775067\",\"x_customer_name\":\"Jaime\",\"x_customer_lastname\":\"uribe\",\"x_customer_email\":\"jaimeuribe08@hotmail.com\",\"x_customer_phone\":\"3186442694\",\"x_customer_movil\":\"3186442694\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"190.248.149.210\",\"x_signature\":\"c720e434dc5af0eebcdfa6938ce844616ca65881f24b1e0a375f2110cd5227fd\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"2\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2021-02-09 12:46:25', '2021-02-09 12:46:25'),
(2, 3, 1, 11000, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"40256713\",\"x_id_factura\":\"20210212154038\",\"x_id_invoice\":\"20210212154038\",\"x_description\":\"Pago a comercio codigo143172\",\"x_amount\":\"11000\",\"x_amount_country\":\"11000\",\"x_amount_ok\":\"11000\",\"x_tax\":\"0\",\"x_amount_base\":\"11000\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"GANA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"94013261\",\"x_transaction_id\":\"48771653419227\",\"x_fecha_transaccion\":\"2021-02-12 15:41:54\",\"x_transaction_date\":\"2021-02-12 15:41:54\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"GA\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"43023596\",\"x_customer_name\":\"Yanira\",\"x_customer_lastname\":\"agamez\",\"x_customer_email\":\"jaimeuribe08@hotmail.com\",\"x_customer_phone\":\"N\\/A\",\"x_customer_movil\":\"N\\/A\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"10.8.1.239\",\"x_signature\":\"11e7767cf28189c002dda7634d81e2f2150485e725562935856f604e24b86302\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"3\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2021-02-13 17:34:12', '2021-02-13 17:34:12'),
(3, 5, 1, 10500, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"41179075\",\"x_id_factura\":\"20210224173622\",\"x_id_invoice\":\"20210224173622\",\"x_description\":\"Pago a comercio c\\u00c3\\u00b3digo:432996\",\"x_amount\":\"10500\",\"x_amount_country\":\"10500\",\"x_amount_ok\":\"10500\",\"x_tax\":\"0\",\"x_amount_base\":\"0\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"BANCOLOMBIA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"903350703\",\"x_transaction_id\":\"48771614206529\",\"x_fecha_transaccion\":\"2021-02-24 17:42:09\",\"x_transaction_date\":\"2021-02-24 17:42:09\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"PSE\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"43023596\",\"x_customer_name\":\"Yanira\",\"x_customer_lastname\":\"gamez\",\"x_customer_email\":\"jaimeuribe08@hotail.com\",\"x_customer_phone\":\"3186442694\",\"x_customer_movil\":\"3186442694\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"190.248.149.210\",\"x_signature\":\"d7d481616ba132551478fc6595a50c71ac57b1302ad6bcd8a335d276a669db83\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"5\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2021-02-24 17:45:40', '2021-02-24 17:45:40'),
(4, 6, 1, 12000, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"42878213\",\"x_id_factura\":\"20210315144953\",\"x_id_invoice\":\"20210315144953\",\"x_description\":\"Pago a comercio c\\u00c3\\u00b3digo:929442\",\"x_amount\":\"12000\",\"x_amount_country\":\"12000\",\"x_amount_ok\":\"12000\",\"x_tax\":\"0\",\"x_amount_base\":\"0\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"BANCOLOMBIA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"924093622\",\"x_transaction_id\":\"4877161583788925\",\"x_fecha_transaccion\":\"2021-03-15 14:51:29\",\"x_transaction_date\":\"2021-03-15 14:51:29\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"PSE\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"43023596\",\"x_customer_name\":\"Yanira\",\"x_customer_lastname\":\"agamez\",\"x_customer_email\":\"jaimeuribe08@hotmail.com\",\"x_customer_phone\":\"3186442694\",\"x_customer_movil\":\"3186442694\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"N\\/A\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"190.248.149.210\",\"x_signature\":\"af76f29552150dc03291f0ac8729bdb9e0a9714fcbca25c6a9b3a64586ae795d\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"6\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2021-03-15 15:56:07', '2021-03-15 15:56:07'),
(5, 9, 1, 11100, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"76495779\",\"x_id_factura\":\"20220127181814\",\"x_id_invoice\":\"20220127181814\",\"x_description\":\"Pago a comercio c\\u00c3\\u00b3digo:209906\",\"x_amount\":\"11100\",\"x_amount_country\":\"11100\",\"x_amount_ok\":\"11100\",\"x_tax\":\"0\",\"x_tax_ico\":\"0\",\"x_amount_base\":\"0\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"BANCOLOMBIA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"0\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"1301361264\",\"x_transaction_id\":\"764957791643325551\",\"x_fecha_transaccion\":\"2022-01-27 18:19:11\",\"x_transaction_date\":\"2022-01-27 18:19:11\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"PSE\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"71775067\",\"x_customer_name\":\"Jaime\",\"x_customer_lastname\":\"uribe\",\"x_customer_email\":\"jaimeuribe08@hotmail.com\",\"x_customer_phone\":\"3186442694\",\"x_customer_movil\":\"3186442694\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"N\\/A\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"191.95.34.90\",\"x_signature\":\"bde8b4050cf1576d264d34087f9826377ae60bd6ad536e1cc7d9c8b21e508b76\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"9\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2022-01-27 18:21:29', '2022-01-27 18:21:29'),
(6, 10, 1, 10500, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"84730845\",\"x_id_factura\":\"20220407160512\",\"x_id_invoice\":\"20220407160512\",\"x_description\":\"Pago a comercio c\\u00c3\\u00b3digo:423717\",\"x_amount\":\"10500\",\"x_amount_country\":\"10500\",\"x_amount_ok\":\"10500\",\"x_tax\":\"0\",\"x_tax_ico\":\"0\",\"x_amount_base\":\"0\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"BANCOLOMBIA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"0\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"1403987768\",\"x_transaction_id\":\"847308451649365596\",\"x_fecha_transaccion\":\"2022-04-07 16:06:36\",\"x_transaction_date\":\"2022-04-07 16:06:36\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"PSE\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"25799818\",\"x_customer_name\":\"Yanira\",\"x_customer_lastname\":\"agamez\",\"x_customer_email\":\"jaimeuribe08@hotmail.com\",\"x_customer_phone\":\"3186442694\",\"x_customer_movil\":\"3186442694\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"N\\/A\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"190.248.149.210\",\"x_signature\":\"3d3d19407265bdab9e767106f96dcf02dea195a44c07b7a57850fa2a112e99ab\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"10\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2022-04-07 16:08:39', '2022-04-07 16:08:39'),
(7, 11, 1, 109400, '{\"x_cust_id_cliente\":\"491354\",\"x_ref_payco\":\"84871895\",\"x_id_factura\":\"20220408191225\",\"x_id_invoice\":\"20220408191225\",\"x_description\":\"Pago a comercio c\\u00c3\\u00b3digo:176479\",\"x_amount\":\"109400\",\"x_amount_country\":\"109400\",\"x_amount_ok\":\"109400\",\"x_tax\":\"0\",\"x_tax_ico\":\"0\",\"x_amount_base\":\"0\",\"x_currency_code\":\"COP\",\"x_bank_name\":\"BANCO DAVIVIENDA\",\"x_cardnumber\":\"*******\",\"x_quotas\":\"0\",\"x_respuesta\":\"Aceptada\",\"x_response\":\"Aceptada\",\"x_approval_code\":\"1406013554\",\"x_transaction_id\":\"848718951649463266\",\"x_fecha_transaccion\":\"2022-04-08 19:14:26\",\"x_transaction_date\":\"2022-04-08 19:14:26\",\"x_cod_respuesta\":\"1\",\"x_cod_response\":\"1\",\"x_response_reason_text\":\"00-Aprobada\",\"x_errorcode\":\"00\",\"x_cod_transaction_state\":\"1\",\"x_transaction_state\":\"Aceptada\",\"x_franchise\":\"PSE\",\"x_business\":\"\",\"x_customer_doctype\":\"CC\",\"x_customer_document\":\"43023596\",\"x_customer_name\":\"yanira\",\"x_customer_lastname\":\"agamez\",\"x_customer_email\":\"jaimeuribe08@hotmail.com\",\"x_customer_phone\":\"\",\"x_customer_movil\":\"3226361723\",\"x_customer_ind_pais\":\"\",\"x_customer_country\":\"CO\",\"x_customer_city\":\"N\\/A\",\"x_customer_address\":\"N\\/A\",\"x_customer_ip\":\"190.248.149.210\",\"x_signature\":\"1344ecd6422fe7856546eac44c9541137591afef4ddc64451951dc64bf646c4f\",\"x_test_request\":\"FALSE\",\"x_extra1\":\"11\",\"x_extra2\":\"\",\"x_extra3\":\"\",\"x_extra4\":\"\",\"x_extra5\":\"\",\"x_extra6\":\"\",\"x_extra7\":\"\",\"x_extra8\":\"\",\"x_extra9\":\"\",\"x_extra10\":\"\"}', '2022-04-08 19:15:27', '2022-04-08 19:15:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `requests_payments`
--

CREATE TABLE `requests_payments` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `value` double NOT NULL,
  `shop_commerce_id` int(11) NOT NULL,
  `comision_percentaje` double NOT NULL,
  `comision_value` double NOT NULL,
  `date_payment` date DEFAULT NULL,
  `date_pending` datetime DEFAULT NULL,
  `note` text DEFAULT NULL,
  `note_payment` text DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `nit` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `social_reason` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `guild` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `department` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `account_bank` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `account_number` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `account_type` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `account_file` varchar(255) DEFAULT NULL,
  `chamber_commerce_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `rut_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `identification_account` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `name_admin` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `identification_admin` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `image_admin` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `cost_min` int(11) NOT NULL DEFAULT 1,
  `cost_max` int(11) NOT NULL DEFAULT 1,
  `payment_type` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `number_commerces` int(11) NOT NULL,
  `payment_total` float NOT NULL,
  `identification_up_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `identification_down_file` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `services_list` text CHARACTER SET utf8mb4 NOT NULL,
  `products_lists` text CHARACTER SET utf8mb4 NOT NULL,
  `phone` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `adviser` int(11) NOT NULL,
  `cellpone_admin` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `credits_line_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `shops`
--

INSERT INTO `shops` (`id`, `nit`, `social_reason`, `guild`, `department`, `city`, `address`, `account_bank`, `account_number`, `account_type`, `account_file`, `chamber_commerce_file`, `rut_file`, `identification_account`, `email`, `name_admin`, `identification_admin`, `image_admin`, `user_id`, `plan`, `type`, `cost_min`, `cost_max`, `payment_type`, `number_commerces`, `payment_total`, `identification_up_file`, `identification_down_file`, `services_list`, `products_lists`, `phone`, `adviser`, `cellpone_admin`, `state`, `created`, `modified`, `credits_line_id`) VALUES
(36, '9010962671', 'CREDIVENTAS', 'Tecnología', 'antioquia', 'medellin', 'cll 50 nro 46-36', 'Bancolombia', '61382547194', 'AHORROS', NULL, 'Shop_1612668455_601f5e277a8aa_1612668455.pdf', 'Shop_1612668455_601f5e277a927_1612668455.pdf', '901096267', 'comercial1@crediventas.com', 'Cristian Daniel Villa Morales', '11521845871', 'Shop_1612668455_601f5e277aa36_1612668455.jpeg', 891, 1, 1, 4, 1, '2', 3, 761600, 'Shop_1612668455_601f5e277a989_1612668455.jpeg', 'Shop_1612668455_601f5e277a9de_1612668455.jpeg', 'ventas por comercios', '1', '5904603', 10047, '3207045856', 1, '2021-02-06 22:27:35', '2022-08-18 16:53:33', NULL),
(201, '123456789', 'COMERCIO ONLINE', 'Accesorios', 'Antioquia', 'Medellin', 'Calle 00 # 00-00', 'Banco de Bogotá', '00000000000000', 'AHORROS', NULL, 'Shop_1660759624_62fd2e48085a9_1660759624.pdf', 'Shop_1660759624_62fd2e4808610_1660759624.pdf', '0000000000', 'comercioonline@gmail.com', 'ADMIN COMERCIO', '0000000000', 'Shop_1660759624_62fd2e4808705_1660759624.jpeg', 10048, 1, 0, 4, 0, '1', 1, 0, 'Shop_1660759624_62fd2e4808664_1660759624.jpeg', 'Shop_1660759624_62fd2e48086b6_1660759624.jpeg', 'Comercio', '1', '3200000000', 10047, '3000000000', 1, '2022-08-17 13:07:04', '2022-09-02 14:39:33', 5),
(202, '2222222999', 'Pruebas Soagro', 'Tatuajes', 'cas', 'as', 'as', 'Banco de Bogotá', '35353535353535', 'AHORROS', NULL, 'Shop_1660769544_62fd55084cb66_1660769544.pdf', 'Shop_1660769544_62fd55084cbce_1660769544.pdf', '105355656565', 'efi@soagro.net', 'Efi', '1053811148', '', 10053, 1, 0, 4, 7, '1', 2, 0, '', '', 'Herramientas', '1', '9999999', 10047, '3126502153', 1, '2022-08-17 15:52:24', '2022-08-17 15:52:24', 7),
(203, '878798723434', 'prueba comercio', 'Accesorios Militares', 'antiquia', 'medelolin', 'calle 50 # 46 - 36 oficina 911 ed furatena  - la canadelaria', 'Bancolombia', '23232323213', 'AHORROS', NULL, 'Shop_1660859408_62feb410c0e49_1660859408.pdf', 'Shop_1660859408_62feb410c0ecb_1660859408.pdf', '1039468574', 'invercreativos@gmail.com', 'tomas carrasquilla', '12345678', 'Shop_1660859408_62feb410c100c_1660859408.jpeg', 10060, 1, 0, 10, 5, '1', 1, 0, 'Shop_1660859408_62feb410c0f3a_1660859408.jpeg', 'Shop_1660859408_62feb410c0fa4_1660859408.jpeg', 'celulares, accesorios,', '1', '797873434', 10047, '3207045856', 1, '2022-08-18 16:50:08', '2022-08-18 16:50:08', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shops_debts`
--

CREATE TABLE `shops_debts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop_commerce_id` int(11) NOT NULL,
  `credit_id` bigint(20) DEFAULT NULL,
  `shop_payment_request_id` int(11) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 1,
  `value` float NOT NULL,
  `reason` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `shops_debts`
--

INSERT INTO `shops_debts` (`id`, `user_id`, `shop_commerce_id`, `credit_id`, `shop_payment_request_id`, `type`, `value`, `reason`, `state`, `created`, `modified`) VALUES
(1, 0, 289, NULL, 1, 2, 400000, 'Comisión Pago 1', 2, '2022-08-17 16:32:17', '2022-08-17 16:32:17'),
(2, 0, 289, NULL, 2, 2, 160000, 'Comisión Pago 1', 1, '2022-08-17 17:37:09', '2022-08-17 17:37:09'),
(3, 0, 290, NULL, 3, 2, 200000, 'Comisión Pago 1', 2, '2022-08-18 18:12:57', '2022-08-18 18:12:57'),
(4, 0, 289, NULL, 4, 2, 80000, 'Comisión Pago 1', 1, '2022-09-02 11:14:08', '2022-09-02 11:14:08'),
(5, 0, 289, NULL, NULL, 2, 0, 'Comisión Pago 2', 0, '2022-09-02 11:40:27', '2022-09-02 11:40:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_commerces`
--

CREATE TABLE `shop_commerces` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `image` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `shop_id` int(11) NOT NULL,
  `code` bigint(20) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `shop_commerces`
--

INSERT INTO `shop_commerces` (`id`, `name`, `address`, `phone`, `image`, `state`, `shop_id`, `code`, `created`, `modified`) VALUES
(35, 'Crediventas Medellín', 'cll 50 nro 56- 36', '5904603', 'ShopCommerce_1612668744_601f5f482b6c5_1612668744.jpeg', 1, 36, 73221084, '2021-02-06 22:32:24', '2021-02-14 20:00:20'),
(132, 'punto de experiencia Niquia', 'C.C. NIQUIA', '3045577024', 'ShopCommerce_1632161610_6148cf4a81470_1632161610.jpg', 0, 36, 34630516, '2021-09-20 13:13:30', '2021-09-20 13:13:30'),
(265, 'Prueba', 'Direccion', '89898898898', '', 0, 36, 85253452, '2022-06-15 12:38:24', '2022-06-15 12:38:24'),
(289, 'Principal', 'Cale 00 #00-00', '30000000', '', 1, 201, 63256894, '2022-08-17 13:09:23', '2022-08-17 13:09:23'),
(290, 'SOAGRO', 'CALLE 50 ', '3157474199', 'ShopCommerce_1660861244_62febb3c1f4f8_1660861244.jpeg', 1, 203, 20782499, '2022-08-18 17:20:44', '2022-08-18 17:20:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_payments`
--

CREATE TABLE `shop_payments` (
  `id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `shop_id` int(11) NOT NULL DEFAULT 0,
  `outstanding_balance` float NOT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `image_payment` varchar(255) DEFAULT NULL,
  `payment_value` float DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `shop_payments`
--

INSERT INTO `shop_payments` (`id`, `date`, `shop_id`, `outstanding_balance`, `state`, `image_payment`, `payment_value`, `notes`, `payment_date`, `created`, `modified`) VALUES
(1, '2022-08-17 13:07:04', 201, 0, 1, NULL, 0, NULL, '2022-08-17 13:07:28', '2022-08-17 13:07:28', '2022-08-17 13:07:28'),
(2, '2022-08-17 15:52:24', 202, 0, 1, NULL, 0, NULL, '2022-08-17 16:14:29', '2022-08-17 16:14:29', '2022-08-17 16:14:29'),
(3, '2022-08-18 16:50:08', 203, 0, 1, NULL, 0, NULL, '2022-08-18 16:50:37', '2022-08-18 16:50:37', '2022-08-18 16:50:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_payment_requests`
--

CREATE TABLE `shop_payment_requests` (
  `id` int(11) NOT NULL,
  `final_value` float DEFAULT NULL,
  `request_value` float NOT NULL,
  `payment_type` int(11) NOT NULL,
  `iva` float DEFAULT NULL,
  `iva_final` float DEFAULT NULL,
  `request_date` datetime NOT NULL,
  `date_pending` datetime DEFAULT NULL,
  `final_date` datetime DEFAULT NULL,
  `shop_commerce_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `state` int(11) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `shop_payment_requests`
--

INSERT INTO `shop_payment_requests` (`id`, `final_value`, `request_value`, `payment_type`, `iva`, `iva_final`, `request_date`, `date_pending`, `final_date`, `shop_commerce_id`, `user_id`, `notes`, `state`, `created`, `modified`) VALUES
(1, 4524000, 5000000, 1, 76000, 76000, '2022-08-17 16:32:17', NULL, '2022-08-17 00:00:00', 289, 10048, 'Pagado', 1, '2022-08-17 16:32:17', '2022-08-17 16:35:18'),
(2, NULL, 2000000, 1, 30400, NULL, '2022-08-17 17:37:09', NULL, NULL, 289, 10048, NULL, 0, '2022-08-17 17:37:09', '2022-08-17 17:37:09'),
(3, 1762000, 2000000, 1, 38000, 38000, '2022-08-18 18:12:57', '2022-08-18 18:14:44', '2022-08-18 00:00:00', 290, 10060, 'sdfdfdsfsdf', 1, '2022-08-18 18:12:57', '2022-08-18 18:15:52'),
(4, NULL, 2000000, 1, 15200, NULL, '2022-09-02 11:14:08', NULL, NULL, 289, 10068, NULL, 0, '2022-09-02 11:14:08', '2022-09-02 11:14:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shop_references`
--

CREATE TABLE `shop_references` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(45) NOT NULL,
  `commerce` varchar(255) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `shop_references`
--

INSERT INTO `shop_references` (`id`, `name`, `phone`, `commerce`, `shop_id`, `state`, `created`, `modified`) VALUES
(7, 'Referes 1', '22992929292', 'Tienda', 13, 1, '2020-07-19 19:04:21', '2020-07-19 19:04:21'),
(8, 'Referes 2', '2020202012', 'Casa', 13, 1, '2020-07-19 19:04:21', '2020-07-19 19:04:21'),
(9, 'Jaime Uribe', '3186442694', 'Coquetas', 14, 1, '2020-09-25 17:59:57', '2020-09-25 17:59:57'),
(10, 'Sebastian Uribe', '3174428540', 'Vouthic', 14, 1, '2020-09-25 17:59:57', '2020-09-25 17:59:57'),
(11, 'Luisa Henao', '3186442695', 'Picaras', 15, 1, '2020-09-25 20:44:43', '2020-09-25 20:44:43'),
(12, 'Sebastian Uribe', '3174428540', 'Vouthic', 15, 1, '2020-09-25 20:44:43', '2020-09-25 20:44:43'),
(13, 'Luisa Henao', '3186442695', 'Picaras', 16, 1, '2020-09-25 21:35:18', '2020-09-25 21:35:18'),
(14, 'Jaime Uribe', '3186442694', 'Coquetas', 16, 1, '2020-09-25 21:35:18', '2020-09-25 21:35:18'),
(15, 'Estefania Garcia', '3004592233', 'Mulatas', 17, 1, '2020-09-30 17:02:13', '2020-09-30 17:02:13'),
(16, 'Yolanda David', '3148692951', 'Gimnasio', 17, 1, '2020-09-30 17:02:13', '2020-09-30 17:02:13'),
(17, 'betraiz morales', '32423432423', 'dfsdfdfdf', 18, 1, '2020-09-30 23:39:58', '2020-09-30 23:39:58'),
(18, 'mario ', '34234234324', 'fsdfdfsdf', 18, 1, '2020-09-30 23:39:58', '2020-09-30 23:39:58'),
(19, 'jhkjh', '9879878323423', 'jkkjhjh', 19, 1, '2020-10-01 13:48:06', '2020-10-01 13:48:06'),
(20, 'kjhkjh', '98798734234', 'fsdfdfsdf', 19, 1, '2020-10-01 13:48:06', '2020-10-01 13:48:06'),
(21, 'Jeison Panesso', '3046614046', 'Cadillac', 20, 1, '2020-10-01 14:54:26', '2020-10-01 14:54:26'),
(22, 'Arley Panesso', '3162418517', 'Connect', 20, 1, '2020-10-01 14:54:26', '2020-10-01 14:54:26'),
(23, 'Jeison Panesso', '3046614046', 'Cadillac', 21, 1, '2020-10-01 16:20:28', '2020-10-01 16:20:28'),
(24, 'Daniel Meneses', '3005121725', 'Nexus', 21, 1, '2020-10-01 16:20:28', '2020-10-01 16:20:28'),
(25, 'Arley Panesso', '3162418517', 'Cadillac 1', 22, 1, '2020-10-01 16:48:13', '2020-10-01 16:48:13'),
(26, 'Daniel Meneses', '3005121725', 'Nexus', 22, 1, '2020-10-01 16:48:13', '2020-10-01 16:48:13'),
(27, 'Daniel Felipe Menesses', '3005121725', 'Nexus', 23, 1, '2020-10-05 17:40:12', '2020-10-05 17:40:12'),
(28, 'John Ferney Giraldo', '3155754622', 'Walking', 23, 1, '2020-10-05 17:40:12', '2020-10-05 17:40:12'),
(29, 'no', '00000000000', 'no', 24, 1, '2020-10-26 18:33:30', '2020-10-26 18:33:30'),
(30, 'no', '000000000000', 'no', 24, 1, '2020-10-26 18:33:30', '2020-10-26 18:33:30'),
(31, '0', '0000000000', '0', 25, 1, '2020-11-09 15:18:23', '2020-11-09 15:18:23'),
(32, '0', '0000000000', '0', 25, 1, '2020-11-09 15:18:23', '2020-11-09 15:18:23'),
(33, 'a', '1234567123', 'a', 26, 1, '2020-11-10 16:56:52', '2020-11-10 16:56:52'),
(34, 'a', '1234567123', 'a', 26, 1, '2020-11-10 16:56:52', '2020-11-10 16:56:52'),
(35, 'a', '1234567890', 'q', 27, 1, '2020-11-10 17:03:32', '2020-11-10 17:03:32'),
(36, 'a', '1234567890', 'a', 27, 1, '2020-11-10 17:03:32', '2020-11-10 17:03:32'),
(37, 'a', '12343567890', 'a', 28, 1, '2020-11-10 17:20:01', '2020-11-10 17:20:01'),
(38, 'a', '12343567890', 'a', 28, 1, '2020-11-10 17:20:01', '2020-11-10 17:20:01'),
(39, '0', '0000000000', '0', 29, 1, '2020-11-24 15:53:51', '2020-11-24 15:53:51'),
(40, '0', '0000000000', '0', 29, 1, '2020-11-24 15:53:51', '2020-11-24 15:53:51'),
(41, 'j', '0000000000', 'j', 30, 1, '2020-11-27 10:49:42', '2020-11-27 10:49:42'),
(42, 'j', '0000000000', 'j', 30, 1, '2020-11-27 10:49:42', '2020-11-27 10:49:42'),
(43, 'N1', '0123456789', 'C1', 31, 1, '2020-12-05 10:06:22', '2020-12-05 10:06:22'),
(44, 'N2', '0123456789', 'C2', 31, 1, '2020-12-05 10:06:22', '2020-12-05 10:06:22'),
(45, 'Referencia 1', '1234567890', 'Uno', 32, 1, '2021-01-15 10:20:33', '2021-01-15 10:20:33'),
(46, 'Referencia 2', '1234567890', 'Dos', 32, 1, '2021-01-15 10:20:33', '2021-01-15 10:20:33'),
(47, 'Jaime Andrés Uribe', '3186442694', 'Coquetas & Business Company', 33, 1, '2021-01-26 10:28:57', '2021-01-26 10:28:57'),
(48, 'Edison Zapata', '3128711101', 'Local-Comercio Contiguo', 33, 1, '2021-01-26 10:28:57', '2021-01-26 10:28:57'),
(49, 'Hernán Mesa Ramírez', '3168742795', 'Comercio Calzado Melosos', 34, 1, '2021-02-04 19:22:42', '2021-02-04 19:22:42'),
(50, 'Nohemí  Barrera Cataño', '3216392916', 'Ropa línea infantil  Melao', 34, 1, '2021-02-04 19:22:42', '2021-02-04 19:22:42'),
(51, 'Samuel Hoyos', '0345117870', 'Inversiones AXER', 35, 1, '2021-02-06 12:08:09', '2021-02-06 12:08:09'),
(52, 'Ruth Nancy Flórez', '3152553844', 'CORBETA', 35, 1, '2021-02-06 12:08:09', '2021-02-06 12:08:09'),
(53, 'na', '00000000000', 'na', 36, 1, '2021-02-06 22:27:35', '2021-02-06 22:27:35'),
(54, 'na', '0000000000', 'na', 36, 1, '2021-02-06 22:27:35', '2021-02-06 22:27:35'),
(55, 'Juan Esteban  Díaz Quintero', '5742280663', 'ONIX CEL', 37, 1, '2021-02-12 18:36:18', '2021-02-12 18:36:18'),
(56, 'Alejandro Oreste Urrea', '3043704094', 'Estación Tecnología', 37, 1, '2021-02-12 18:36:18', '2021-02-12 18:36:18'),
(57, 'RUTH NANCY FLOREZ', '3152553844', 'Colombiana de Comercio Corbeta', 38, 1, '2021-02-25 18:10:49', '2021-02-25 18:10:49'),
(58, 'Daid Ospina', '5745115584', 'Sumiistros FARGO S.A.S.', 38, 1, '2021-02-25 18:10:49', '2021-02-25 18:10:49'),
(59, 'Paula Aguilar', '3236979579', 'Carol - Peluquería Estética', 39, 1, '2021-02-25 19:04:14', '2021-02-25 19:04:14'),
(60, 'Compra Oro Amalfi', '3128434398', 'Yesika Gil ', 39, 1, '2021-02-25 19:04:14', '2021-02-25 19:04:14'),
(61, 'Astrid Alzate Hincapié', '3172345354', 'Servilentes', 40, 1, '2021-03-31 10:58:05', '2021-03-31 10:58:05'),
(62, 'Edison Alexander Franco Giraldo', '5742515113', 'Servioptica', 40, 1, '2021-03-31 10:58:05', '2021-03-31 10:58:05'),
(63, 'Jorge Luis Munibe', '3043682930', 'Comercio Vida de Encanto', 41, 1, '2021-04-26 15:20:17', '2021-04-26 15:20:17'),
(64, 'Patricia Gallego', '3106574664', 'Proveedor de Marca de Ropa', 41, 1, '2021-04-26 15:20:17', '2021-04-26 15:20:17'),
(65, 'Andrea Agudelo', '3005325831', 'Vision Óptica', 42, 1, '2021-05-07 15:22:28', '2021-05-07 15:22:28'),
(66, 'Consuelo Alzate', '3105909178', 'Calle 50 # 47 28 of 406', 42, 1, '2021-05-07 15:22:28', '2021-05-07 15:22:28'),
(67, 'Paulina Guerra', '573045456933', 'Estilo Woman', 43, 1, '2021-05-27 11:31:00', '2021-05-27 11:31:00'),
(68, 'Ludy Mora', '5742934611', 'Wintex', 43, 1, '2021-05-27 11:31:00', '2021-05-27 11:31:00'),
(69, '0', '00000000000', '0', 44, 1, '2021-07-08 15:47:02', '2021-07-08 15:47:02'),
(70, '0', '00000000000', '0', 44, 1, '2021-07-08 15:47:02', '2021-07-08 15:47:02'),
(71, '0', '0000000000', '0', 45, 1, '2021-07-08 16:22:20', '2021-07-08 16:22:20'),
(72, '0', '0000000000', '0', 45, 1, '2021-07-08 16:22:20', '2021-07-08 16:22:20'),
(73, 'Referencia 1', '1234567890', 'Vecino', 46, 1, '2021-07-13 12:08:41', '2021-07-13 12:08:41'),
(74, 'Referencia 2', '1234567890', 'Vecino', 46, 1, '2021-07-13 12:08:41', '2021-07-13 12:08:41'),
(75, 'Uno', '1234567890', 'Uno', 47, 1, '2021-07-13 13:46:04', '2021-07-13 13:46:04'),
(76, 'Dos', '1234567890', 'Dos', 47, 1, '2021-07-13 13:46:04', '2021-07-13 13:46:04'),
(77, 'Jose Luis Rodríguez', '5744448520', 'Así sea', 48, 1, '2021-07-14 11:55:42', '2021-07-14 11:55:42'),
(78, 'Jose Luis Rodríguez', '5744448520', 'Así sea', 48, 1, '2021-07-14 11:55:42', '2021-07-14 11:55:42'),
(79, '0', '0000000000', '0', 49, 1, '2021-07-15 11:00:09', '2021-07-15 11:00:09'),
(80, '0', '00000000000', '0', 49, 1, '2021-07-15 11:00:09', '2021-07-15 11:00:09'),
(81, 'Jaime Andrés Uribe', '3186442694', 'Coquetas', 50, 1, '2021-07-15 12:48:18', '2021-07-15 12:48:18'),
(82, 'Lida Giraldo', '3127010413', 'Attica Store', 50, 1, '2021-07-15 12:48:18', '2021-07-15 12:48:18'),
(83, 'Jairo Bernal', '3004365344', 'THE GUAYAQUIL TIMES', 51, 1, '2021-07-15 15:48:02', '2021-07-15 15:48:02'),
(84, 'Duber Ledesma', '3005166020', 'CENTROUNIDO', 51, 1, '2021-07-15 15:48:02', '2021-07-15 15:48:02'),
(85, 'Jaime Andrés Uribe', '3186442694', 'Coquetas & Business Company', 52, 1, '2021-07-23 17:14:53', '2021-07-23 17:14:53'),
(86, 'Jaime Andrés Uribe', '3186442694', 'Coquetas & Business Company', 52, 1, '2021-07-23 17:14:53', '2021-07-23 17:14:53'),
(87, 'Gerardo Esteban Gomez Montoya', '3013404317', 'Ten box', 53, 1, '2021-07-26 14:58:22', '2021-07-26 14:58:22'),
(88, 'Guillermo Alonso Gomez Rotavista', '3128694939', 'CONTAINER SPORT', 53, 1, '2021-07-26 14:58:22', '2021-07-26 14:58:22'),
(89, 'Maria Yarley Hoyos Gómez', '3005133877', 'RARÉ', 54, 1, '2021-07-26 15:35:17', '2021-07-26 15:35:17'),
(90, 'Maria Yarley Hoyos Gómez', '3005133877', 'RARÉ', 54, 1, '2021-07-26 15:35:17', '2021-07-26 15:35:17'),
(91, 'Juan Pablo', '3218173289', 'X Zone', 55, 1, '2021-07-26 16:00:24', '2021-07-26 16:00:24'),
(92, 'Juan Pablo', '3218173289', 'X Zone', 55, 1, '2021-07-26 16:00:24', '2021-07-26 16:00:24'),
(93, 'Maria Yarley Hoyos Gómez', '3005133877', 'RARÉ', 56, 1, '2021-07-27 13:59:08', '2021-07-27 13:59:08'),
(94, 'Maria Yarley Hoyos Gómez', '3005133877', 'RARÉ', 56, 1, '2021-07-27 13:59:08', '2021-07-27 13:59:08'),
(95, 'Duber Ledesma', '3005166020', 'Centro Unido', 57, 1, '2021-07-28 14:19:45', '2021-07-28 14:19:45'),
(96, 'Duber Ledesma', '3005166020', 'Centro Unido', 57, 1, '2021-07-28 14:19:45', '2021-07-28 14:19:45'),
(97, 'Jaime Andrés Uribe', '3186442694', '3186442694', 58, 1, '2021-07-29 14:35:39', '2021-07-29 14:35:39'),
(98, 'Jaime Andrés Uribe', '3186442694', '3186442694', 58, 1, '2021-07-29 14:35:39', '2021-07-29 14:35:39'),
(99, 'Gerardo Esteban Gomez Montoya', '3148647096', 'TEN BOX', 59, 1, '2021-07-30 17:41:48', '2021-07-30 17:41:48'),
(100, 'Daniel Felipe Meneses', '3005121725', 'NEXUS', 59, 1, '2021-07-30 17:41:48', '2021-07-30 17:41:48'),
(101, 'Jaime Andrés Uribe', '3186442694', 'Coquetas & Business Company', 60, 1, '2021-07-31 09:46:04', '2021-07-31 09:46:04'),
(102, 'Jairo Bernal', '3004365344', 'Guayaquil Times & Business Company', 60, 1, '2021-07-31 09:46:04', '2021-07-31 09:46:04'),
(103, 'Gerardo Esteban Gomez Montoya', '3148647096', 'TEN BOX', 61, 1, '2021-07-31 17:24:58', '2021-07-31 17:24:58'),
(104, 'Daniel Felipe Meneses', '3005121725', 'NEXUS', 61, 1, '2021-07-31 17:24:58', '2021-07-31 17:24:58'),
(105, 'Evelyn Cerra Meléndez', '3133655727', 'Ebenezer MODA ', 62, 1, '2021-08-03 12:19:16', '2021-08-03 12:19:16'),
(106, 'Evelyn Cerra Meléndez', '3133655727', 'Ebenezer MODA ', 62, 1, '2021-08-03 12:19:16', '2021-08-03 12:19:16'),
(107, 'Daniel Fernando Molano Vidal', '3223762518', 'FULL LOCKER SHOES', 63, 1, '2021-08-03 15:00:29', '2021-08-03 15:00:29'),
(108, 'Daniel Felipe Meneses', '3005121725', 'NEXUS', 63, 1, '2021-08-03 15:00:29', '2021-08-03 15:00:29'),
(109, 'PLUS SPORT SAS    ', '5722248876', 'PLUS SPORT SAS    ', 64, 1, '2021-08-04 19:08:41', '2021-08-04 19:08:41'),
(110, 'REPRESENTACIONES SIERRA BETANCUR SAS', '5742702193', 'REPRESENTACIONES SIERRA BETANCUR SAS', 64, 1, '2021-08-04 19:08:41', '2021-08-04 19:08:41'),
(111, 'Diana Jiménez', '3053533242', 'Óptica Unidad Médica Visual', 65, 1, '2021-08-05 13:58:12', '2021-08-05 13:58:12'),
(112, 'Cristián Novales', '5742512052', 'Laboratorio', 65, 1, '2021-08-05 13:58:12', '2021-08-05 13:58:12'),
(113, 'Diana Jiménez', '3053533242', 'Óptica Unidad Médica Visual', 66, 1, '2021-08-10 11:33:13', '2021-08-10 11:33:13'),
(114, 'Cristián Novales', '5742512052', 'Laboratorio', 66, 1, '2021-08-10 11:33:14', '2021-08-10 11:33:14'),
(115, 'Juan Pablo', '3218173289', 'X Zone', 67, 1, '2021-08-10 12:39:51', '2021-08-10 12:39:51'),
(116, 'Juan Pablo', '3218173289', 'X Zone', 67, 1, '2021-08-10 12:39:51', '2021-08-10 12:39:51'),
(117, '...de Pasto', '1234567890', '...de Pasto', 68, 1, '2021-08-12 16:13:57', '2021-08-12 16:13:57'),
(118, '...de Pasto', '1234567890', '...de Pasto', 68, 1, '2021-08-12 16:13:57', '2021-08-12 16:13:57'),
(119, 'Melisa Agudelo', '3226326838', 'Trendy Fashion', 69, 1, '2021-08-12 17:26:38', '2021-08-12 17:26:38'),
(120, 'Melisa Agudelo', '3226326838', 'Trendy Fashion', 69, 1, '2021-08-12 17:26:38', '2021-08-12 17:26:38'),
(121, 'Mónica Suárez', '3218507744', 'Blue Connection', 70, 1, '2021-08-14 09:45:59', '2021-08-14 09:45:59'),
(122, 'David Ospina', '3218748081', 'Modatec', 70, 1, '2021-08-14 09:45:59', '2021-08-14 09:45:59'),
(123, 'Gladis de Jesús Taborda franco', '3017865740', 'Tienda de Ropa Angeles', 71, 1, '2021-08-17 19:25:00', '2021-08-17 19:25:00'),
(124, 'Ledys Restrepo', '3052402408', 'Belleza Integral', 71, 1, '2021-08-17 19:25:00', '2021-08-17 19:25:00'),
(125, 'Claudia Helena', '3113950083', 'Tienda SPORT C.J.', 72, 1, '2021-08-18 13:13:22', '2021-08-18 13:13:22'),
(126, 'doña Nissan', '3006527970', 'New York', 72, 1, '2021-08-18 13:13:22', '2021-08-18 13:13:22'),
(127, 'Manuela Restrepo', '3186514630', 'Almacén Milagros', 73, 1, '2021-08-18 18:43:52', '2021-08-18 18:43:52'),
(128, 'Elizabeth Vásquez', '3137361421', 'Modatech', 73, 1, '2021-08-18 18:43:52', '2021-08-18 18:43:52'),
(129, 'DISLENS SAS', '3219825209', 'DISLENS SAS', 74, 1, '2021-08-19 09:04:39', '2021-08-19 09:04:39'),
(130, 'FALCON', '5745578292', 'FALCON', 74, 1, '2021-08-19 09:04:39', '2021-08-19 09:04:39'),
(131, 'Edison Pequeñin', '3045448137', 'Pequeñin', 75, 1, '2021-08-19 09:32:25', '2021-08-19 09:32:25'),
(132, 'Evelyn Rodriguez', '3218168432', 'Nestle', 75, 1, '2021-08-19 09:32:25', '2021-08-19 09:32:25'),
(133, 'Camilo', '3136016750', 'Tienda de arte ', 76, 1, '2021-08-19 10:39:06', '2021-08-19 10:39:06'),
(134, 'Felipe', '3122131064', 'Remate Locura', 76, 1, '2021-08-19 10:39:06', '2021-08-19 10:39:06'),
(135, 'Catalina', '3205581048', 'DIFFERENT', 77, 1, '2021-08-20 11:00:53', '2021-08-20 11:00:53'),
(136, 'Juan Carlos', '3012425525', 'CHALA JEANS', 77, 1, '2021-08-20 11:00:53', '2021-08-20 11:00:53'),
(137, 'Administrador', '5744443260', 'Trilogy', 78, 1, '2021-08-25 19:13:53', '2021-08-25 19:13:53'),
(138, 'Administrador', '5744443260', 'Trilogy', 78, 1, '2021-08-25 19:13:53', '2021-08-25 19:13:53'),
(139, 'Jaime Andrés Uribe', '573186442694', 'Coquetas & Business Company', 79, 1, '2021-08-26 12:20:19', '2021-08-26 12:20:19'),
(140, 'Luisa Fernanda Henao', '573186442695', 'Picaras', 79, 1, '2021-08-26 12:20:19', '2021-08-26 12:20:19'),
(141, 'Alejandro Rodríguez', '3152471523', 'Fabricante de calzado', 80, 1, '2021-08-27 16:58:06', '2021-08-27 16:58:06'),
(142, 'Jhon Flavio Giraldo', '3107919364', 'Ready shoes', 80, 1, '2021-08-27 16:58:06', '2021-08-27 16:58:06'),
(143, 'Ana maria Giraldo', '3014433762', 'Amaia', 81, 1, '2021-08-27 19:31:33', '2021-08-27 19:31:33'),
(144, 'Isabel Mena', '3003383614', 'Epika', 81, 1, '2021-08-27 19:31:33', '2021-08-27 19:31:33'),
(145, 'Bibiana Londoño', '3207202530', 'VIPAZ', 82, 1, '2021-08-28 16:29:03', '2021-08-28 16:29:03'),
(146, 'Cristian Camilo Arboleda', '3012727228', ' Brothers Fashion (Local  140)', 82, 1, '2021-08-28 16:29:03', '2021-08-28 16:29:03'),
(147, 'Kelly Dahiana Zapata Marín ', '3053533084', 'Perfumería Armani', 83, 1, '2021-08-31 08:15:48', '2021-08-31 08:15:48'),
(148, 'Luz Elena duque Yepes', '3135803450', 'Leidany D.Y. Variedades', 83, 1, '2021-08-31 08:15:48', '2021-08-31 08:15:48'),
(149, 'Yulieth Aristizábal', '3103892605', 'Tienda de la Moda', 84, 1, '2021-08-31 09:36:46', '2021-08-31 09:36:46'),
(150, 'Manuela Restrepo', '3116102458', 'Shalo Jeans', 84, 1, '2021-08-31 09:36:46', '2021-08-31 09:36:46'),
(151, 'Martín Rueda', '3007810572', 'Martin’S Accesorios', 85, 1, '2021-09-02 19:13:19', '2021-09-02 19:13:19'),
(152, 'Margarita Isaza', '3127539545', 'Mowic', 85, 1, '2021-09-02 19:13:19', '2021-09-02 19:13:19'),
(153, 'Luz Miriam García', '3128873197', 'Argar', 86, 1, '2021-09-03 09:29:25', '2021-09-03 09:29:25'),
(154, 'Everlides García', '3218361381', 'Sarya Moda', 86, 1, '2021-09-03 09:29:25', '2021-09-03 09:29:25'),
(155, 'Gloria Patricia Castro Franco', '3217872505', 'Gloria Patricia Castro Franco', 87, 1, '2021-09-08 19:01:29', '2021-09-08 19:01:29'),
(156, 'Maria Fernanda Galeano Serna', '3205397372', 'Maria Fernanda Galeano Serna', 87, 1, '2021-09-08 19:01:29', '2021-09-08 19:01:29'),
(157, 'Laura Londoño', '3105993223', 'NOHEA - Asesora Comercial', 88, 1, '2021-09-09 08:07:15', '2021-09-09 08:07:15'),
(158, 'Jaime Zuluaga', '3003122302', 'Almacen Papiros', 88, 1, '2021-09-09 08:07:15', '2021-09-09 08:07:15'),
(159, 'Ana Maria Giraldo', '3003765906', 'Amaia', 89, 1, '2021-09-09 08:21:07', '2021-09-09 08:21:07'),
(160, 'Yazmin Giraldo', '3217925893', 'Ziela', 89, 1, '2021-09-09 08:21:07', '2021-09-09 08:21:07'),
(161, 'Marling Lopez', '3008325735', 'Almacen ZOPEL´s, Local 149', 90, 1, '2021-09-09 09:15:57', '2021-09-09 09:15:57'),
(162, 'Francy', '3014180404', 'Almacen MEGORE', 90, 1, '2021-09-09 09:15:57', '2021-09-09 09:15:57'),
(163, 'Jaime Andrés Uribe', '3186442694', 'Coquetas & Business Company', 91, 1, '2021-09-17 08:57:55', '2021-09-17 08:57:55'),
(164, 'Jhon Ferney Giraldo Zuluaga', '3107347561', 'Walking Tennis', 91, 1, '2021-09-17 08:57:55', '2021-09-17 08:57:55'),
(165, 'Everlides García', '3218361381', 'Sarys moda', 92, 1, '2021-09-18 11:41:39', '2021-09-18 11:41:39'),
(166, 'Milena Quintero', '3117281506', 'Modas Mile', 92, 1, '2021-09-18 11:41:39', '2021-09-18 11:41:39'),
(167, 'Miguel', '3212756397', 'Ebenezer MODA ', 93, 1, '2021-09-21 08:46:02', '2021-09-21 08:46:02'),
(168, 'Alba Ruth Gelvez', '3122878080', 'Calzado Geral\'s', 93, 1, '2021-09-21 08:46:02', '2021-09-21 08:46:02'),
(169, 'Everlides Garcia', '3218361381', 'AlmacenSarys Moda', 94, 1, '2021-09-21 10:29:12', '2021-09-21 10:29:12'),
(170, 'Viviana Londoño', '3207202530', 'Almacen Modas vipaz', 94, 1, '2021-09-21 10:29:12', '2021-09-21 10:29:12'),
(171, 'Edison Quintero', '3104661255', 'Hospiplay', 95, 1, '2021-09-21 10:56:35', '2021-09-21 10:56:35'),
(172, 'Juan Jose Montoya', '3154468072', 'Encanto', 95, 1, '2021-09-21 10:56:35', '2021-09-21 10:56:35'),
(173, 'Francy', '3014180404', 'Megore con Stylo', 96, 1, '2021-09-21 11:18:17', '2021-09-21 11:18:17'),
(174, 'Ivoon', '3146112001', 'Ivoon shop', 96, 1, '2021-09-21 11:18:17', '2021-09-21 11:18:17'),
(175, 'Juan Guillermo Gómez', '3103961623', 'Krombi', 97, 1, '2021-09-22 10:40:42', '2021-09-22 10:40:42'),
(176, 'Juan Guillermo Giraldo', '3116348212', 'Krombi', 97, 1, '2021-09-22 10:40:42', '2021-09-22 10:40:42'),
(177, 'Ana Maria Londoño', '3207318127', 'Santa Pasión', 98, 1, '2021-09-24 13:56:43', '2021-09-24 13:56:43'),
(178, 'Andrea Maya', '3122438876', 'baby bom', 98, 1, '2021-09-24 13:56:43', '2021-09-24 13:56:43'),
(179, 'Dahiana', '3053533084', 'Perfumeria Armani', 99, 1, '2021-09-24 14:38:05', '2021-09-24 14:38:05'),
(180, 'Camilo Vélez', '3185358314', 'Click 2 Action', 99, 1, '2021-09-24 14:38:05', '2021-09-24 14:38:05'),
(181, 'Albeiro Gómez', '3122613119', 'Calzado FOX Sport', 100, 1, '2021-09-25 10:20:07', '2021-09-25 10:20:07'),
(182, 'Francisco Aristizábal', '3022883608', 'Éxito del Calzado', 100, 1, '2021-09-25 10:20:07', '2021-09-25 10:20:07'),
(183, 'Eliana Bermúdez', '3136426690', 'Almacén angeles', 101, 1, '2021-09-25 11:42:50', '2021-09-25 11:42:50'),
(184, 'Yurley londoño', '3208449945', 'Asesora  Comercial', 101, 1, '2021-09-25 11:42:50', '2021-09-25 11:42:50'),
(185, 'Dahourj Manneh Riad', '3012891752', 'NUNI Pañalera ', 102, 1, '2021-10-02 10:02:00', '2021-10-02 10:02:00'),
(186, 'Sergio Junkla', '3174274728', 'De Marca Junkla', 102, 1, '2021-10-02 10:02:00', '2021-10-02 10:02:00'),
(187, 'Mary Luz Cano', '3136804388', 'Modas de HOY', 103, 1, '2021-10-02 11:07:54', '2021-10-02 11:07:54'),
(188, 'Margarita Ramírez', '3175862073', 'Esposa', 103, 1, '2021-10-02 11:07:54', '2021-10-02 11:07:54'),
(189, 'Tatiana Márquez', '3246726283', 'Tamaga', 104, 1, '2021-10-02 11:42:53', '2021-10-02 11:42:53'),
(190, 'Linda Nathaly Pacheco', '3127689731', 'Morenitas', 104, 1, '2021-10-02 11:42:53', '2021-10-02 11:42:53'),
(191, 'Osmary Ovando', '3206602943', 'Pétalos Pink', 105, 1, '2021-10-02 12:20:43', '2021-10-02 12:20:43'),
(192, 'Carlos Trujillo', '3242014089', 'Mega Centro', 105, 1, '2021-10-02 12:20:43', '2021-10-02 12:20:43'),
(193, 'Lisseth Buritica', '3128207224', 'Urban & Co', 106, 1, '2021-10-02 12:56:19', '2021-10-02 12:56:19'),
(194, 'Tatiana Zea', '3024110525', 'Sin Límite', 106, 1, '2021-10-02 12:56:19', '2021-10-02 12:56:19'),
(195, 'Omaira Quiceno', '3014555858', 'You Jeans', 107, 1, '2021-10-02 13:18:18', '2021-10-02 13:18:18'),
(196, 'Nallely Nieto', '3008892462', 'Megore', 107, 1, '2021-10-02 13:18:18', '2021-10-02 13:18:18'),
(197, 'Liliana Mejía Tobón', '3006303428', 'Tienda LILIs', 108, 1, '2021-10-02 19:26:58', '2021-10-02 19:26:58'),
(198, 'Carolina Marín', '3004406646', 'Las Reatas', 108, 1, '2021-10-02 19:26:58', '2021-10-02 19:26:58'),
(199, 'Deisy Márquez', '3113216614', 'Celucentral Urabá', 109, 1, '2021-10-06 11:33:45', '2021-10-06 11:33:45'),
(200, 'Alejandra', '3207450498', 'Biogroup', 109, 1, '2021-10-06 11:33:45', '2021-10-06 11:33:45'),
(201, 'Paula Restrepo', '3146791730', 'Supermercado El Favorito', 110, 1, '2021-10-06 11:51:40', '2021-10-06 11:51:40'),
(202, 'Javier Taborda', '3044582505', 'Clínica del celular', 110, 1, '2021-10-06 11:51:40', '2021-10-06 11:51:40'),
(203, 'Riad Dahrouj Manneh', '3012891752', 'Amazon Nuni', 111, 1, '2021-10-06 14:16:01', '2021-10-06 14:16:01'),
(204, 'Ahmad Daychoum Saad', '3008841655', 'Inversiones Medio Oriente (Rionegro)', 111, 1, '2021-10-06 14:16:01', '2021-10-06 14:16:01'),
(205, 'Hernán Argaez', '3122610298', 'HyM Tecnology', 112, 1, '2021-10-11 08:19:26', '2021-10-11 08:19:26'),
(206, 'Leidy Blandón', '3102744510', 'LS Accesorios', 112, 1, '2021-10-11 08:19:26', '2021-10-11 08:19:26'),
(207, 'Yeison Ortega', '3008187263', 'Fox Visual', 113, 1, '2021-10-16 12:45:01', '2021-10-16 12:45:01'),
(208, 'Yesid Fonseca', '3006754295', 'Óptica Ulens', 113, 1, '2021-10-16 12:45:01', '2021-10-16 12:45:01'),
(209, 'Francy', '3014180404', 'Megore con Stylo', 114, 1, '2021-10-16 13:09:43', '2021-10-16 13:09:43'),
(210, 'Marling López', '3508487559', 'Zopel\'s', 114, 1, '2021-10-16 13:09:43', '2021-10-16 13:09:43'),
(211, 'Fernando Álvarez', '3113717049', 'Optica Kors', 115, 1, '2021-10-22 08:21:58', '2021-10-22 08:21:58'),
(212, 'Johan Marulanda', '3205344126', 'Max Vision', 115, 1, '2021-10-22 08:21:58', '2021-10-22 08:21:58'),
(213, 'Raúl Tangarife', '3113686755', 'Óptica Express', 116, 1, '2021-10-22 08:37:41', '2021-10-22 08:37:41'),
(214, 'Johan Marulanda', '3205344126', 'Max Visual', 116, 1, '2021-10-22 08:37:41', '2021-10-22 08:37:41'),
(215, 'Johan Marulanda', '3205314126', 'Max Visual #2', 117, 1, '2021-10-22 15:42:25', '2021-10-22 15:42:25'),
(216, 'Yesid Orozco Patiño', '3105915510', 'Max Visual #1', 117, 1, '2021-10-22 15:42:25', '2021-10-22 15:42:25'),
(217, 'Carlos Esteban Betarcur Vergara', '3108339837', 'Hotel Puerto Azul', 118, 1, '2021-10-22 16:17:10', '2021-10-22 16:17:10'),
(218, 'Rolando Gómez Agamez', '3004003000', 'Credishop', 118, 1, '2021-10-22 16:17:10', '2021-10-22 16:17:10'),
(219, 'Edilson Betancur', '3146206508', 'JE Motos', 119, 1, '2021-10-26 16:34:10', '2021-10-26 16:34:10'),
(220, 'Francisco Javier Betancur Yépes', '3144207916', 'Estilo Fotográfico', 119, 1, '2021-10-26 16:34:10', '2021-10-26 16:34:10'),
(221, 'Rubén Avendaño', '3013149970', 'Boom Móvil', 120, 1, '2021-10-27 09:23:11', '2021-10-27 09:23:11'),
(222, 'Juan David Sepúlveda', '604305240991', 'Linkon', 120, 1, '2021-10-27 09:23:11', '2021-10-27 09:23:11'),
(223, 'Santiago Borja', '3147303546', 'Centro Comercial 3 vías, Local 105', 121, 1, '2021-10-29 14:52:30', '2021-10-29 14:52:30'),
(224, 'Yuli Atehortua', '3205033015', 'Asesora- Urban Club', 121, 1, '2021-10-29 14:52:30', '2021-10-29 14:52:30'),
(225, 'Cristian Zuluaga', '3193172479', 'Laboratorio Novalens', 122, 1, '2021-10-29 16:19:56', '2021-10-29 16:19:56'),
(226, 'Jhoan Marulanda', '3205314126', 'Max Visual', 122, 1, '2021-10-29 16:19:56', '2021-10-29 16:19:56'),
(227, 'Claudia Castaño', '3122112579', 'Estanquillo Granada -Admin-', 123, 1, '2021-10-29 17:49:43', '2021-10-29 17:49:43'),
(228, 'Luz Elena Zuluaga', '3008113555', 'Unlokc Móvil', 123, 1, '2021-10-29 17:49:43', '2021-10-29 17:49:43'),
(229, 'Claudia Serna', '3125056685', 'Claudia Serna Almácen', 124, 1, '2021-10-30 10:36:07', '2021-10-30 10:36:07'),
(230, 'Claudia Camacho', '3206082267', 'Azúl Celeste', 124, 1, '2021-10-30 10:36:07', '2021-10-30 10:36:07'),
(231, 'Melany Agudelo', '3022716237', 'Dulce Menta', 125, 1, '2021-10-30 12:27:02', '2021-10-30 12:27:02'),
(232, 'Dahiana Collazos', '3116471801', 'Casa de Reyes', 125, 1, '2021-10-30 12:27:02', '2021-10-30 12:27:02'),
(233, 'Leimar Leandra Gómez Rojas', '3006108874', 'Visión Lego', 126, 1, '2021-11-02 09:18:20', '2021-11-02 09:18:20'),
(234, 'Hermes Edilson Giraldo Giraldo', '3124346602', 'Surtiopticas Girbro Medellín', 126, 1, '2021-11-02 09:18:20', '2021-11-02 09:18:20'),
(235, 'Ricardo Ramírez', '3104537160', 'Surtidor Óptico  ', 127, 1, '2021-11-02 10:32:42', '2021-11-02 10:32:42'),
(236, 'Maryeli Panesso', '3106161363', 'Maxibisel', 127, 1, '2021-11-02 10:32:42', '2021-11-02 10:32:42'),
(237, 'Juan Guillermo Gómez', '3103961623', 'Krombi & Socio BC', 128, 1, '2021-11-03 12:35:42', '2021-11-03 12:35:42'),
(238, 'Juan Guillermo Giraldo', '3116348212', 'Krombi & Socio BC', 128, 1, '2021-11-03 12:35:42', '2021-11-03 12:35:42'),
(239, 'Deiber Loaiza Pérez', '3232108152', 'Óptica New Visión', 129, 1, '2021-11-05 18:47:10', '2021-11-05 18:47:10'),
(240, 'Sady Loaiza Pérez', '3007944287', 'Óptica Visión Blue', 129, 1, '2021-11-05 18:47:10', '2021-11-05 18:47:10'),
(241, 'Manuela Giraldo', '3054562955', 'Tamaga', 130, 1, '2021-11-05 19:18:23', '2021-11-05 19:18:23'),
(242, 'Eliana Cardona', '3008293689', 'Jumpy', 130, 1, '2021-11-05 19:18:23', '2021-11-05 19:18:23'),
(243, 'Laura Loaiza', '3208541608', 'Alma Mía', 131, 1, '2021-11-13 17:20:30', '2021-11-13 17:20:30'),
(244, 'Yenifer Olarte', '3152625303', 'Chulas Boutique', 131, 1, '2021-11-13 17:20:30', '2021-11-13 17:20:30'),
(247, 'Adriana Espitia Anaya', '3003745905', 'Enfóque Visual', 133, 1, '2021-11-17 09:26:50', '2021-11-17 09:26:50'),
(248, 'Laura Andrea Presiga Caro', '3504108088', 'Optica Visión Próxima', 133, 1, '2021-11-17 09:26:50', '2021-11-17 09:26:50'),
(249, 'Esperanza Guzmán', '3113361798', 'Open Móvil', 134, 1, '2021-11-17 10:00:03', '2021-11-17 10:00:03'),
(250, 'Claudia Palacio', '3105485504', 'San Judas', 134, 1, '2021-11-17 10:00:03', '2021-11-17 10:00:03'),
(251, 'Jarin José Marimon Torrenegra', '3204607018', 'Yalusca', 135, 1, '2021-11-17 13:00:58', '2021-11-17 13:00:58'),
(252, 'Sindy Sánchez', '3124967526', 'Alex Móvil.com', 135, 1, '2021-11-17 13:00:58', '2021-11-17 13:00:58'),
(253, 'Carlos Mario Marín', '3102127706', 'SMC Colombia', 136, 1, '2021-11-24 19:28:15', '2021-11-24 19:28:15'),
(254, 'Rolando Gómez Agamez', '3004003000', 'Credishop', 136, 1, '2021-11-24 19:28:15', '2021-11-24 19:28:15'),
(257, 'Estela María Vélez', '3206730402', 'Variedades ISARI', 138, 1, '2021-12-01 11:53:03', '2021-12-01 11:53:03'),
(258, 'Juan Felipe Restrepo', '3206734389', 'Variedades EL TRIUNFO', 138, 1, '2021-12-01 11:53:03', '2021-12-01 11:53:03'),
(259, 'Laura Presiga', '3504108088', 'Óptica Visión Próxima', 139, 1, '2021-12-03 11:25:55', '2021-12-03 11:25:55'),
(260, 'Leonor Castiblanca', '3112818872', 'Tendencias Óptica', 139, 1, '2021-12-03 11:25:55', '2021-12-03 11:25:55'),
(261, 'Yarlid Fernando Noreña', '3013861178', 'UNLOCK MÓVIL', 140, 1, '2021-12-15 09:31:27', '2021-12-15 09:31:27'),
(262, 'Érica Milena Montes', '3104189872', 'ZULCEL', 140, 1, '2021-12-15 09:31:27', '2021-12-15 09:31:27'),
(263, 'Sady Loaiza ', '3007944287', 'Visión Blue', 141, 1, '2022-01-05 19:31:01', '2022-01-05 19:31:01'),
(264, 'Fernando Álvarez', '3113717049', 'Óptica KORS', 141, 1, '2022-01-05 19:31:01', '2022-01-05 19:31:01'),
(265, 'Geimar Loaiza', '3164974744', 'MAXILENTES', 142, 1, '2022-01-19 12:39:03', '2022-01-19 12:39:03'),
(266, 'Duberney García', '3206500810', 'MAXIBISEL', 142, 1, '2022-01-19 12:39:03', '2022-01-19 12:39:03'),
(267, 'Mauricio Salazar', '3103749352', 'CELUPLAY', 143, 1, '2022-01-20 19:21:12', '2022-01-20 19:21:12'),
(268, 'Elizabeth Aguilar', '3148619464', 'Almacén ZUELAS J.A.', 143, 1, '2022-01-20 19:21:12', '2022-01-20 19:21:12'),
(269, 'Deisy Gómez Mora', '3137608104', 'Palo Rosa Joyería', 144, 1, '2022-01-26 14:28:39', '2022-01-26 14:28:39'),
(270, 'Luciano Zuluaga', '3044556619', 'Metales y Brillantes', 144, 1, '2022-01-26 14:28:39', '2022-01-26 14:28:39'),
(271, 'Tatiana Zea', '3024110525', 'SIN LÍMITES', 145, 1, '2022-01-26 15:47:30', '2022-01-26 15:47:30'),
(272, 'Francy', '3014180404', 'MEGORE con STYLO', 145, 1, '2022-01-26 15:47:30', '2022-01-26 15:47:30'),
(273, 'Luciano Zuluaga', '3044556619', 'METALES Y BRILLANTES', 146, 1, '2022-01-26 16:27:03', '2022-01-26 16:27:03'),
(274, 'Oscar Argumedo', '3105497987', 'ARGU JOYAS', 146, 1, '2022-01-26 16:27:03', '2022-01-26 16:27:03'),
(275, 'Andres Felipe Betancur', '3504660605', 'Asesor Comercial INDEPENDIENTE', 147, 1, '2022-02-02 17:21:22', '2022-02-02 17:21:22'),
(276, 'Casanareña de Belleza ', '3212121217', 'Sergio Torres', 147, 1, '2022-02-02 17:21:22', '2022-02-02 17:21:22'),
(277, 'Liliana Jaramillo', '3007175240', 'COMERCIALIZADORA B & J', 148, 1, '2022-02-19 11:06:51', '2022-02-19 11:06:51'),
(278, 'Viviana Quiroga Ramos', '3206786716', 'CELULARES 3000', 148, 1, '2022-02-19 11:06:51', '2022-02-19 11:06:51'),
(279, 'Fernando Álvarez Duque', '3113717049', 'ÓPTICA KORS', 149, 1, '2022-02-19 16:12:09', '2022-02-19 16:12:09'),
(280, 'Margarita Isled Ramírez', '3137335933', 'ÓPTICA MAXILENTES', 149, 1, '2022-02-19 16:12:09', '2022-02-19 16:12:09'),
(281, 'Juan Estéban Álvarez', '3137008051', 'DAVID BARBER SHOP', 150, 1, '2022-02-24 14:34:55', '2022-02-24 14:34:55'),
(282, 'Elisa M. Pinillos', '3147166116', 'ULTRAVISION OPTICS', 150, 1, '2022-02-24 14:34:55', '2022-02-24 14:34:55'),
(283, 'Paula Andrea Carrasco', '3185325362', 'HENKEL COLOMBIANA SAS', 151, 1, '2022-02-25 12:07:24', '2022-02-25 12:07:24'),
(284, 'Edison Giraldo', '3176689026', 'ALMACEN LA PULGA', 151, 1, '2022-02-25 12:07:24', '2022-02-25 12:07:24'),
(285, 'Jorge Montoya', '3003438733', 'Clínic Óptica', 152, 1, '2022-02-26 10:12:28', '2022-02-26 10:12:28'),
(286, 'Mateo Pareja', '3244625905', 'Óptica Estatus', 152, 1, '2022-02-26 10:12:28', '2022-02-26 10:12:28'),
(287, 'Diana María Ramírez', '3202022361', 'ÓPTICAWINNY BISEL', 153, 1, '2022-02-26 12:58:01', '2022-02-26 12:58:01'),
(288, 'Yolima Aristizábal Giraldo', '3004723016', 'DISTRILENTES', 153, 1, '2022-02-26 12:58:01', '2022-02-26 12:58:01'),
(289, 'SANDRA BIBIANA BORJA', '32069786716', 'CELULARES 3000', 154, 1, '2022-03-04 19:30:13', '2022-03-04 19:30:13'),
(290, 'HUBER PRIETO', '3122956448', 'PRIETO CELL', 154, 1, '2022-03-04 19:30:13', '2022-03-04 19:30:13'),
(291, 'Sebastián Minita', '3152077560', 'Padre Nuestro Restaurante', 155, 1, '2022-03-07 19:08:59', '2022-03-07 19:08:59'),
(292, 'Andres Agudelo', '3113729624', 'Barberia Cavalier', 155, 1, '2022-03-07 19:08:59', '2022-03-07 19:08:59'),
(293, 'Aliz Ruiz', '3117792087', 'TALLA PLUS', 156, 1, '2022-03-09 10:37:13', '2022-03-09 10:37:13'),
(294, 'Angie Toro', '3135353856', 'Almacén JEREMÍAS', 156, 1, '2022-03-09 10:37:13', '2022-03-09 10:37:13'),
(295, 'Daisy Nebis Márquez Rodríguez', '3113216614', 'CELUCENTRAL URABA', 157, 1, '2022-03-09 11:23:55', '2022-03-09 11:23:55'),
(296, 'Administración & Finanzas', '6043649777', 'CORBETA', 157, 1, '2022-03-09 11:23:55', '2022-03-09 11:23:55'),
(297, 'Gicela Muñoz', '3108452002', 'SANTO PIE', 158, 1, '2022-03-18 12:45:41', '2022-03-18 12:45:41'),
(298, 'Gabriel Vélez', '3006653909', 'DISPERFUMES', 158, 1, '2022-03-18 12:45:41', '2022-03-18 12:45:41'),
(301, 'Fabio Zora', '3176779098', 'CASA ODONTOLÓGICA DE ANTIOQUIA', 160, 1, '2022-03-25 13:43:43', '2022-03-25 13:43:43'),
(302, 'Alejandra Marín', '3218164017', 'ABC DENTAL', 160, 1, '2022-03-25 13:43:43', '2022-03-25 13:43:43'),
(303, 'Fabián Córdoba', '3214493533', 'INDUSTRIAS CERCO', 161, 1, '2022-03-29 11:15:20', '2022-03-29 11:15:20'),
(304, 'Jimmy Alexander-Eulalia (Encargada)', '3125271128', 'FERRETERIA JIMMY A MORALES', 161, 1, '2022-03-29 11:15:20', '2022-03-29 11:15:20'),
(309, 'Ronald Díaz', '3113322010', 'CELUCENTRAL URABA', 164, 1, '2022-04-04 17:38:48', '2022-04-04 17:38:48'),
(310, 'Yineth Tamara Escobar', '3008365401', 'CREDIHOGAR SAMY 2', 164, 1, '2022-04-04 17:38:48', '2022-04-04 17:38:48'),
(311, 'Sandra Molina', '3045737992', 'Sublitodo', 165, 1, '2022-04-07 13:31:04', '2022-04-07 13:31:04'),
(312, 'Ricardo Cardona', '6042396514', 'Tapiautos Ricard', 165, 1, '2022-04-07 13:31:04', '2022-04-07 13:31:04'),
(313, 'Johan Zapata ', '3508486004', 'Mundo luces y accesorios', 166, 1, '2022-04-08 11:44:02', '2022-04-08 11:44:02'),
(314, 'Juan ledesma ', '3176362269', 'Luxus ', 166, 1, '2022-04-08 11:44:02', '2022-04-08 11:44:02'),
(315, 'Yonatan Montes', '3106920635', 'IMPERIUM SHOES', 167, 1, '2022-04-09 10:08:47', '2022-04-09 10:08:47'),
(316, 'Jhon Flaudio Giraldo', '3107919364', 'READY SHOES', 167, 1, '2022-04-09 10:08:47', '2022-04-09 10:08:47'),
(317, 'Cristian Barrios Gutiérrez', '3168854422', 'TECNOCELL', 168, 1, '2022-04-11 11:46:31', '2022-04-11 11:46:31'),
(318, 'Bernardo Velásquez ', '3205083359', 'ACCESORIOS TRAVOLTA', 168, 1, '2022-04-11 11:46:31', '2022-04-11 11:46:31'),
(319, 'duvan Herrera', '3016406058', 'Alma Blnaca Reposteria', 169, 1, '2022-04-13 18:27:53', '2022-04-13 18:27:53'),
(320, 'Elizabeth Passos', '3106914244', 'Fresh Perfum', 169, 1, '2022-04-13 18:27:53', '2022-04-13 18:27:53'),
(321, 'Jessica Benavides', '3004110312', 'Duquesa Boutique', 170, 1, '2022-04-16 14:50:01', '2022-04-16 14:50:01'),
(322, 'Rosa León', '3012900879', 'Estilo Y Moda', 170, 1, '2022-04-16 14:50:01', '2022-04-16 14:50:01'),
(323, 'carlos alberto  herrera', '3192136230', 'la clinica del celular', 171, 1, '2022-04-29 10:56:22', '2022-04-29 10:56:22'),
(324, 'oscar ivan gonzalez david', '3207505098', 'jhs repuestos', 171, 1, '2022-04-29 10:56:22', '2022-04-29 10:56:22'),
(325, 'Yeferson  Castaño', '3104538321', 'PUBLICIDAD YC', 172, 1, '2022-04-30 14:22:38', '2022-04-30 14:22:38'),
(326, 'Antony Gutierrez', '3172518166', 'MEDELLIN TECHNOLOGY', 172, 1, '2022-04-30 14:22:38', '2022-04-30 14:22:38'),
(327, 'Yonatan Montes', '3106920635', 'IMPERIUM SHOES', 173, 1, '2022-05-02 11:51:59', '2022-05-02 11:51:59'),
(328, 'Jhon  Giraldo', '3107919364', 'READY SHOES', 173, 1, '2022-05-02 11:51:59', '2022-05-02 11:51:59'),
(329, 'Dahiana zapata', '3053533084', 'Perfumería invictus', 174, 1, '2022-05-03 10:43:49', '2022-05-03 10:43:49'),
(330, 'Andrea gomez', '3116378356', 'Perfumeria distriaromas', 174, 1, '2022-05-03 10:43:49', '2022-05-03 10:43:49'),
(331, 'Duban Arley Zuluaga', '3506839896', 'tu tienda shoes', 175, 1, '2022-05-05 13:00:28', '2022-05-05 13:00:28'),
(332, 'Sergio Andres Gutierez', '3012468471', 'UNIVERSO SHOES', 175, 1, '2022-05-05 13:00:28', '2022-05-05 13:00:28'),
(333, 'Melisa Florez', '3012734130', 'Ruta Shoes', 176, 1, '2022-05-16 11:21:51', '2022-05-16 11:21:51'),
(334, 'Stefany Ciro', '3026519385', 'Punto Calzado', 176, 1, '2022-05-16 11:21:51', '2022-05-16 11:21:51'),
(335, 'Veronica Rivera Giraldo', '3057778042', 'panaderia antojito', 177, 1, '2022-05-18 14:02:30', '2022-05-18 14:02:30'),
(336, 'Dora Hincapie Hoyos', '3135411971', 'soluciones informáticas la 55 bancolombia', 177, 1, '2022-05-18 14:02:30', '2022-05-18 14:02:30'),
(337, 'Mauricio Acevedo', '3003479612', 'Aqua Car Services', 178, 1, '2022-05-19 16:41:11', '2022-05-19 16:41:11'),
(338, 'Paola Sanabria', '3228443384', 'Gef Fit', 178, 1, '2022-05-19 16:41:11', '2022-05-19 16:41:11'),
(339, 'JUAN CAMILO AVENDAÑO', '3046477873', 'TATUAJES INKO', 179, 1, '2022-05-23 11:53:48', '2022-05-23 11:53:48'),
(340, 'ROSA VIVIANA VERGARA', '3014634678', 'MAGENTA', 179, 1, '2022-05-23 11:53:48', '2022-05-23 11:53:48'),
(341, 'Stefanía Oquendo ', '3217605090', 'Salomón tienda multimarcas', 180, 1, '2022-05-23 15:09:45', '2022-05-23 15:09:45'),
(342, 'Julián mesa ', '3206965167', 'Hoa bar ', 180, 1, '2022-05-23 15:09:45', '2022-05-23 15:09:45'),
(343, 'Eliana Vidal Mejía ', '3207836118', 'Emistar ', 181, 1, '2022-05-26 17:14:31', '2022-05-26 17:14:31'),
(344, 'Jhon Alexander Bedolla ', '3117674131', 'JHBO ropa deportiva ', 181, 1, '2022-05-26 17:14:31', '2022-05-26 17:14:31'),
(345, 'LEONARDO FAVIO AGUIRRE ARIAS', '3218382090', 'EBANISTERIA', 182, 1, '2022-05-27 17:52:23', '2022-05-27 17:52:23'),
(346, 'ALVARO ARANGO', '3147053759', 'DROGUERIA', 182, 1, '2022-05-27 17:52:23', '2022-05-27 17:52:23'),
(347, 'Natalia Gaviria', '3006555098', 'Little Boutique', 183, 1, '2022-05-31 15:28:16', '2022-05-31 15:28:16'),
(348, 'Veronica Arenas', '3156782747', 'Capiruso', 183, 1, '2022-05-31 15:28:16', '2022-05-31 15:28:16'),
(349, 'MOHAMMED AWAQ', '3012606666', 'NUNY BABY', 184, 1, '2022-06-09 16:12:36', '2022-06-09 16:12:36'),
(350, 'ABDUL DABAJAH', '3215030182', 'PANORAMA', 184, 1, '2022-06-09 16:12:36', '2022-06-09 16:12:36'),
(351, 'Martha Gomez', '3006157265', 'Tienda de Ropa Infantil', 185, 1, '2022-06-21 08:57:39', '2022-06-21 08:57:39'),
(352, 'Adriana Rodriguez', '3137119148', 'Confeccionista', 185, 1, '2022-06-21 08:57:39', '2022-06-21 08:57:39'),
(355, 'Juan David Jaramillo', '3148318816', 'Urban Shoes', 187, 1, '2022-06-21 15:31:29', '2022-06-21 15:31:29'),
(356, 'Melisa Florez', '3012734130', 'Ruta Shoes', 187, 1, '2022-06-21 15:31:29', '2022-06-21 15:31:29'),
(357, 'liney garavito taborta', '3147382450', 'tienda de alimentos', 188, 1, '2022-06-22 10:49:39', '2022-06-22 10:49:39'),
(358, 'manuel espinosa', '3012097391', 'taller de pintura', 188, 1, '2022-06-22 10:49:39', '2022-06-22 10:49:39'),
(359, 'Wilmer de Jesús aristizabal', '3008118745', 'No aplica ', 189, 1, '2022-06-24 08:42:31', '2022-06-24 08:42:31'),
(360, 'Nidia aguirre ', '3008410830', 'No aplica ', 189, 1, '2022-06-24 08:42:31', '2022-06-24 08:42:31'),
(361, 'Natalia Correa Madrid', '3207540840', 'Abogada', 190, 1, '2022-06-25 11:50:57', '2022-06-25 11:50:57'),
(362, 'Andrea Correa Madrid', '3117628554', 'Psicologa', 190, 1, '2022-06-25 11:50:57', '2022-06-25 11:50:57'),
(363, 'DANIEL ALBERTO ARTEAGA ', '3137686718', 'PROVEEDOR DE MONTURAS', 191, 1, '2022-06-30 17:41:37', '2022-06-30 17:41:37'),
(364, 'NAYID PAOLA ALVAREZ', '3117290686', 'TRANSPORTADORA', 191, 1, '2022-06-30 17:41:37', '2022-06-30 17:41:37'),
(365, 'Servioptica', '3203431123', 'Laboratorio', 192, 1, '2022-07-06 11:47:12', '2022-07-06 11:47:12'),
(366, 'Hector David Quintero', '3183516715', 'Ventas de monturas ', 192, 1, '2022-07-06 11:47:12', '2022-07-06 11:47:12'),
(367, 'Diana Ramírez ', '3202022371', 'Óptica winny bisel ', 193, 1, '2022-07-07 13:44:17', '2022-07-07 13:44:17'),
(368, 'Mateo pareja', '3244625905', 'Óptica estatus ', 193, 1, '2022-07-07 13:44:17', '2022-07-07 13:44:17'),
(369, 'CREACIONES DLD', '3234094285', 'CREACIONES DLD', 194, 1, '2022-07-13 17:58:32', '2022-07-13 17:58:32'),
(370, 'BELMER DARIO LOPEZ DAVID', '3108210630', 'CALZADO L\'BEL', 194, 1, '2022-07-13 17:58:32', '2022-07-13 17:58:32'),
(371, 'WILLIAM ANDRES REALPE ERAZO', '3218432561', 'EL IMPACTO DE LA MODA', 195, 1, '2022-07-16 16:28:22', '2022-07-16 16:28:22'),
(372, 'LEONARDO MARTINEZ LOPEZ', '3126244484', 'CREDICELL', 195, 1, '2022-07-16 16:28:22', '2022-07-16 16:28:22'),
(377, 'Mauricio Hoyos', '3007767734', 'Restaurante Camaos', 198, 1, '2022-07-22 10:39:21', '2022-07-22 10:39:21'),
(378, 'Paula Salazar', '3232275925', 'Óptica God Lens', 198, 1, '2022-07-22 10:39:21', '2022-07-22 10:39:21'),
(379, 'DANIELA', '3202283324', 'PORTOFINO TEXTIL', 199, 1, '2022-07-29 12:50:52', '2022-07-29 12:50:52'),
(380, 'SARA', '3176409413', 'TEXTILES LILI', 199, 1, '2022-07-29 12:50:52', '2022-07-29 12:50:52'),
(381, 'YURLENY', '3116186169', 'JUANJO FANTASIAS', 200, 1, '2022-07-30 10:42:13', '2022-07-30 10:42:13'),
(382, 'PROPIETARIO', '3104247284', 'Distribuidora GOROZ', 200, 1, '2022-07-30 10:42:13', '2022-07-30 10:42:13'),
(383, 'Prueba', '000000000000', 'prueba', 201, 1, '2022-08-17 13:07:04', '2022-08-17 13:07:04'),
(384, 'prueba', '0000000000000', 'prueba', 201, 1, '2022-08-17 13:07:04', '2022-08-17 13:07:04'),
(385, 'ASas', '3187312030', 'ASAs', 202, 1, '2022-08-17 15:52:24', '2022-08-17 15:52:24'),
(386, 'ASas', '3187302030', 'aSAs', 202, 1, '2022-08-17 15:52:24', '2022-08-17 15:52:24'),
(387, 'alirio gonzales', '65676789890', 'dadsdasdsad', 203, 1, '2022-08-18 16:50:08', '2022-08-18 16:50:08'),
(388, 'KHKJHKJHK', '44343444444', 'sdasdsdsdsd', 203, 1, '2022-08-18 16:50:08', '2022-08-18 16:50:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `simulators`
--

CREATE TABLE `simulators` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `commerce_code` varchar(10) NOT NULL,
  `credits_line_id` int(11) NOT NULL,
  `website` varchar(255) NOT NULL,
  `color_code` varchar(255) NOT NULL,
  `state` int(11) NOT NULL DEFAULT 1,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `simulators`
--

INSERT INTO `simulators` (`id`, `name`, `commerce_code`, `credits_line_id`, `website`, `color_code`, `state`, `created`, `modified`) VALUES
(1, 'mulatas', '54696895', 5, 'livio.com.co/prueba-credito/', '#36e633', 1, '2022-01-22 11:13:24', '2022-03-25 11:25:29'),
(2, 'SOMOSZIRO', '73221084', 5, 'develop-demo-co.furap.co/prueba/', '#71d089', 1, '2022-08-04 11:21:15', '2022-08-04 11:25:07'),
(3, 'mulatas', '098908', 2, 'livio.com.co', '#f10909', 1, '2022-08-22 17:42:47', '2022-08-22 17:42:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `has_change_password` varchar(255) DEFAULT NULL,
  `salt_password` varchar(45) DEFAULT NULL,
  `shop_commerce_id` int(11) DEFAULT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `empresa_id` int(11) DEFAULT NULL,
  `customer_complete` int(11) DEFAULT 0,
  `customer_new_request` int(11) NOT NULL DEFAULT 0,
  `role` varchar(45) NOT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `code` int(11) DEFAULT NULL,
  `deadline` bigint(20) DEFAULT NULL,
  `validate` int(11) DEFAULT 0,
  `state` int(11) DEFAULT 1,
  `created` datetime DEFAULT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `has_change_password`, `salt_password`, `shop_commerce_id`, `shop_id`, `customer_id`, `empresa_id`, `customer_complete`, `customer_new_request`, `role`, `phone`, `code`, `deadline`, `validate`, `state`, `created`, `modified`) VALUES
(1, 'Usuario Admin', 'admin@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', 'dd84275ed1b3884bddf0d14b191b594fd53dc2998ca9e52dc5f18d165a172176', NULL, NULL, NULL, NULL, NULL, 0, 0, '1', '3023149867', NULL, NULL, 1, 1, '2020-07-09 03:22:32', '2022-09-02 14:52:18'),
(422, 'soporte prueba', 'pruebasoporte@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '10', '3207045856', 267464, 1644941318, 0, 1, '2020-11-18 11:10:24', '2022-02-15 11:03:42'),
(891, 'cristian daiel villa morales', 'comercial1@crediventas.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', 'ae6ae508192c94c9b0e1176d2c9bff331d9eac0026b7f039779e19f571ebf860', NULL, NULL, 36, NULL, NULL, 0, 0, '4', NULL, NULL, NULL, 0, 1, '2021-02-06 22:27:35', '2022-05-11 15:44:36'),
(1305, 'analistaprueba', 'analistaprueba@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '3', '3126502153', NULL, NULL, 1, 1, '2021-05-28 15:42:32', '2022-09-02 13:29:24'),
(10041, 'Soporte', 'prodigital1@gmail.com', '35c524b51c971242efbde8b965f82897a1d9cdfe', NULL, NULL, 35, NULL, NULL, NULL, 0, 0, '6', NULL, NULL, NULL, 0, 1, '2022-07-23 09:29:51', '2022-07-23 09:29:51'),
(10042, 'Maria Flores', 'mf27950@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', '5b10f73f807cbc3b3c9ef4d8b188b66d180f4b972da929132c3f3ef1bb28ed29', NULL, NULL, NULL, 1, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-08 14:25:55', '2022-09-03 09:22:53'),
(10043, 'Maria Flores', 'pppp@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', NULL, NULL, NULL, NULL, 2, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-12 12:07:38', '2022-08-12 12:07:38'),
(10044, 'Maria Flores', 'adjjjmin@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', NULL, NULL, NULL, NULL, 3, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-12 15:17:30', '2022-08-12 15:17:30'),
(10045, 'Mariank nkknknk', 'adnknkmin@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', NULL, NULL, NULL, NULL, 4, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-13 10:21:41', '2022-08-13 10:21:41'),
(10046, 'Nombre  Apellido', 'mf2jjj7950@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', NULL, NULL, NULL, NULL, 6, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-17 12:50:17', '2022-08-17 12:50:17'),
(10047, 'Asesor', 'asesor@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '8', '30000000', NULL, NULL, 0, 0, '2022-08-17 13:04:26', '2022-08-17 13:04:26'),
(10048, 'ADMIN COMERCIO', 'comercioonline@gmail.com', '64b86fe5b0cce0248d4144a445ff1c889d4d98dc', NULL, NULL, NULL, 201, NULL, NULL, 0, 0, '4', NULL, NULL, NULL, 0, 1, '2022-08-17 13:07:04', '2022-08-17 13:07:04'),
(10049, 'Bzzbzb Jwjwjwj', 'mfbhbb27950@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', NULL, NULL, NULL, NULL, 7, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-17 13:11:37', '2022-08-17 13:11:37'),
(10050, 'daniel villa', 'johngomezlondono@gmail.com', '1a35a75504685884411bdeaaee2626aad7c14426', NULL, NULL, NULL, NULL, 8, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-17 14:25:45', '2022-08-17 14:25:45'),
(10051, 'John Gomez', 'john@somosziro.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '1', '3187312030', NULL, NULL, 1, 1, '2022-08-17 14:47:41', '2022-09-02 14:38:05'),
(10052, 'Sucursal', 'sucursal@gmail.com', 'c2bbff9b0f6ae5c8762d6b7589c1737c2354d4f7', NULL, NULL, 289, NULL, NULL, NULL, 0, 0, '6', NULL, NULL, NULL, 0, 1, '2022-08-17 14:49:46', '2022-08-17 14:49:46'),
(10053, 'Efi', 'efi@soagro.net', 'fa26f653386930ad9a77ad9c366a04e27e0db81e', 'f38b70c92680e52f231b47e38119ff52b9d8502c9dd1c6da949f94c5e92ff227', NULL, NULL, 202, NULL, NULL, 0, 0, '4', NULL, NULL, NULL, 0, 1, '2022-08-17 15:52:24', '2022-08-17 16:03:46'),
(10054, 'Efigenia Gutierrez Arango', 'efi@somosziro.com', 'f862f9a1270c780723825fc9e5eabe80e9c87b18', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '1', '3126502153', NULL, NULL, 1, 1, '2022-08-17 15:56:47', '2022-09-02 09:15:45'),
(10055, 'juan perez', 'juancacreativo@outlook.com', '2d10913c3a0b734978d6a596206b3eb2477b68b2', NULL, NULL, NULL, NULL, 9, NULL, 1, 2, '5', NULL, NULL, NULL, 0, 1, '2022-08-17 16:23:39', '2022-08-17 17:00:58'),
(10056, 'Juan Carlos Parada', 'juancacreativo@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '1', '3173038378', NULL, NULL, 1, 1, '2022-08-17 17:17:17', '2022-09-01 17:05:12'),
(10057, 'Viviana', 'facturacion@soagro.net', 'f2033bf49ec4abe08dd0d18fbbee14de95ef015a', NULL, NULL, 289, NULL, NULL, NULL, 0, 0, '6', NULL, NULL, NULL, 0, 1, '2022-08-17 17:20:36', '2022-08-17 17:20:36'),
(10058, 'daniel ramon', 'cristian89121@hotmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '1', '3207045856', NULL, NULL, 1, 1, '2022-08-18 16:34:34', '2022-08-18 16:35:49'),
(10059, 'effi', 'zefi@soagro.net', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '1', '3126502153', 511655, 1660859304, 0, 1, '2022-08-18 16:38:57', '2022-08-18 16:43:30'),
(10060, 'tomas carrasquilla', 'invercreativos@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, 203, NULL, NULL, 0, 0, '4', NULL, NULL, NULL, 0, 1, '2022-08-18 16:50:08', '2022-08-18 16:50:08'),
(10061, 'RAFAEL VILLA', 'rafa@gmail.com', 'ab8a3a43456e18ca33d3b7625e09cf51a52d3031', NULL, NULL, 290, NULL, NULL, NULL, 0, 0, '6', NULL, NULL, NULL, 0, 1, '2022-08-18 17:22:31', '2022-08-18 17:22:31'),
(10062, 'John Gomez', 'john@gmail.com', '9f3a7259a32a703865e177268e017b50e26d5ac9', NULL, NULL, NULL, NULL, 10, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-08-18 17:31:59', '2022-08-18 17:31:59'),
(10063, 'juridico', 'juridico@gmail.com', '265307e421fbe96bd3e63da55e13d4f1f508c73e', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '11', '3207045856', NULL, NULL, 0, 1, '2022-08-22 17:25:04', '2022-09-01 16:14:14'),
(10064, 'cobranza', 'cobranza@gmail.com', 'f39be7945af73cbd9a62e949db6c997374726ea4', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '9', '3207045856', NULL, NULL, 0, 1, '2022-08-22 17:26:23', '2022-08-22 17:26:23'),
(10065, '', '', '', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, '', NULL, 529194, 1661964551, 0, 1, '2022-08-31 11:44:11', '2022-08-31 11:44:11'),
(10066, 'John Gómez Londoño', 'jagomezlondono@gmail.com', '9f3a7259a32a703865e177268e017b50e26d5ac9', NULL, NULL, NULL, NULL, 11, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-09-02 10:18:56', '2022-09-02 10:18:56'),
(10067, 'Venderor1', 'vendedor1@gmail.com', 'c2bbff9b0f6ae5c8762d6b7589c1737c2354d4f7', NULL, NULL, 289, NULL, NULL, NULL, 0, 0, '6', NULL, NULL, NULL, 0, 1, '2022-09-02 11:08:24', '2022-09-02 11:08:24'),
(10068, 'Contador', 'contador1@gmail.com', 'c2bbff9b0f6ae5c8762d6b7589c1737c2354d4f7', NULL, NULL, 289, NULL, NULL, NULL, 0, 0, '7', NULL, NULL, NULL, 0, 1, '2022-09-02 11:09:23', '2022-09-02 11:09:23'),
(10069, 'asasa asa', 'credito33@gmail.com', '63cb4efe837f733bcef542178d74ed99468ca4c7', NULL, NULL, NULL, NULL, 12, NULL, 1, 6, '5', NULL, NULL, NULL, 0, 1, '2022-09-02 12:19:44', '2022-09-02 12:19:44');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actions`
--
ALTER TABLE `actions`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `automatics`
--
ALTER TABLE `automatics`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `collection_fees`
--
ALTER TABLE `collection_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `commitments`
--
ALTER TABLE `commitments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `configs`
--
ALTER TABLE `configs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credits_lines`
--
ALTER TABLE `credits_lines`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credits_lines_details`
--
ALTER TABLE `credits_lines_details`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credits_plans`
--
ALTER TABLE `credits_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credits_requests`
--
ALTER TABLE `credits_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credits_requests_comments`
--
ALTER TABLE `credits_requests_comments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `credit_limits`
--
ALTER TABLE `credit_limits`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customers_addresses`
--
ALTER TABLE `customers_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customers_codes`
--
ALTER TABLE `customers_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customers_phones`
--
ALTER TABLE `customers_phones`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `customers_references`
--
ALTER TABLE `customers_references`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `disbursements`
--
ALTER TABLE `disbursements`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `nit` (`nit`) USING BTREE;

--
-- Indices de la tabla `empresa_references`
--
ALTER TABLE `empresa_references`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `histories`
--
ALTER TABLE `histories`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `money_collections`
--
ALTER TABLE `money_collections`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `notes_customers`
--
ALTER TABLE `notes_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `repayments`
--
ALTER TABLE `repayments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `requests`
--
ALTER TABLE `requests`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indices de la tabla `requests_details`
--
ALTER TABLE `requests_details`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `requests_payments`
--
ALTER TABLE `requests_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nit` (`nit`);

--
-- Indices de la tabla `shops_debts`
--
ALTER TABLE `shops_debts`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shop_commerces`
--
ALTER TABLE `shop_commerces`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shop_payments`
--
ALTER TABLE `shop_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shop_payment_requests`
--
ALTER TABLE `shop_payment_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `shop_references`
--
ALTER TABLE `shop_references`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `simulators`
--
ALTER TABLE `simulators`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actions`
--
ALTER TABLE `actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `automatics`
--
ALTER TABLE `automatics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `collection_fees`
--
ALTER TABLE `collection_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `commitments`
--
ALTER TABLE `commitments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `configs`
--
ALTER TABLE `configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `credits`
--
ALTER TABLE `credits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `credits_lines`
--
ALTER TABLE `credits_lines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `credits_lines_details`
--
ALTER TABLE `credits_lines_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1706;

--
-- AUTO_INCREMENT de la tabla `credits_plans`
--
ALTER TABLE `credits_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `credits_requests`
--
ALTER TABLE `credits_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `credits_requests_comments`
--
ALTER TABLE `credits_requests_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `credit_limits`
--
ALTER TABLE `credit_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `customers_addresses`
--
ALTER TABLE `customers_addresses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `customers_codes`
--
ALTER TABLE `customers_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `customers_phones`
--
ALTER TABLE `customers_phones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `customers_references`
--
ALTER TABLE `customers_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `disbursements`
--
ALTER TABLE `disbursements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `empresa_references`
--
ALTER TABLE `empresa_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `histories`
--
ALTER TABLE `histories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `money_collections`
--
ALTER TABLE `money_collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notes`
--
ALTER TABLE `notes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notes_customers`
--
ALTER TABLE `notes_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `repayments`
--
ALTER TABLE `repayments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `requests`
--
ALTER TABLE `requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `requests_details`
--
ALTER TABLE `requests_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `requests_payments`
--
ALTER TABLE `requests_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=204;

--
-- AUTO_INCREMENT de la tabla `shops_debts`
--
ALTER TABLE `shops_debts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `shop_commerces`
--
ALTER TABLE `shop_commerces`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=291;

--
-- AUTO_INCREMENT de la tabla `shop_payments`
--
ALTER TABLE `shop_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `shop_payment_requests`
--
ALTER TABLE `shop_payment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `shop_references`
--
ALTER TABLE `shop_references`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=389;

--
-- AUTO_INCREMENT de la tabla `simulators`
--
ALTER TABLE `simulators`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10070;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
