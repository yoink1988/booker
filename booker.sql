-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 09 2017 г., 23:47
-- Версия сервера: 5.6.16
-- Версия PHP: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `booker`
--

-- --------------------------------------------------------

--
-- Структура таблицы `employees`
--

CREATE TABLE IF NOT EXISTS `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `id_role` int(11) NOT NULL,
  `hash` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- Дамп данных таблицы `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `pass`, `id_role`, `hash`) VALUES
(13, 'Admin', 'admin@admin.ru', '95d53bf24b4c267e64fa593e5d543780', 2, 'yLMEymDu8qv7JtKc5qSu'),
(15, 'Valeriy', 'valerka@mail.ru', '95d53bf24b4c267e64fa593e5d543780', 1, 'E38zVJBfNdcytnzx5ffq'),
(16, 'Genadiy', 'gena@mail.ru', '95d53bf24b4c267e64fa593e5d543780', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_room` int(11) NOT NULL,
  `submit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `id_room`, `submit`) VALUES
(6, 2, '2017-11-09 22:18:41'),
(7, 2, '2017-11-09 22:22:14'),
(9, 2, '2017-11-09 22:25:21');

-- --------------------------------------------------------

--
-- Структура таблицы `event_details`
--

CREATE TABLE IF NOT EXISTS `event_details` (
  `id` int(11) NOT NULL,
  `start` timestamp NULL DEFAULT NULL,
  `end` timestamp NULL DEFAULT NULL,
  `desc` varchar(255) NOT NULL,
  `id_employee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `event_details`
--

INSERT INTO `event_details` (`id`, `start`, `end`, `desc`, `id_employee`) VALUES
(6, '2017-11-13 06:00:00', '2017-11-13 07:30:00', 'Some Meeting', 15),
(6, '2017-11-20 06:00:00', '2017-11-20 07:30:00', 'Some Meeting', 15),
(6, '2017-11-27 06:00:00', '2017-11-27 07:30:00', 'Some Meeting', 15),
(7, '2017-11-22 12:00:00', '2017-11-22 14:00:00', 'Another meeting', 16),
(7, '2017-12-06 12:00:00', '2017-12-06 14:00:00', 'Another meeting', 16),
(7, '2017-12-20 12:00:00', '2017-12-20 14:00:00', 'Another meeting', 16),
(9, '2017-11-29 12:00:00', '2017-11-29 14:00:00', 'ggfsdgsdgsdgsd', 13);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` enum('user','admin') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'user'),
(2, 'admin');

-- --------------------------------------------------------

--
-- Структура таблицы `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `name`) VALUES
(2, 'Conference Hall'),
(3, 'Meeting Room'),
(4, 'Quest Room');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
