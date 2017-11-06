-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 06 2017 г., 14:27
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `pass`, `id_role`, `hash`) VALUES
(1, 'Iluha', 'iluha@mail.ru', '95d53bf24b4c267e64fa593e5d543780', 2, 'fZJrypu5D4621g4PheQu'),
(3, 'gena', 'gena@mail.ru', 'qweqwe11', 1, ''),
(4, 'vasyassdd', 'vasa@mail.ru', 'qweqwe11', 1, ''),
(5, 'zzzz', 'zz@zz.zz', '95d53bf24b4c267e64fa593e5d543780', 1, 'lyBBBBIhoxHVDBRlAPda'),
(6, 'qweqweqwe', 'vasa@mdail.ru', '95d53bf24b4c267e64fa593e5d543780', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_room` int(11) NOT NULL,
  `submit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=74 ;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `id_room`, `submit`) VALUES
(70, 1, '2017-11-07 22:42:38'),
(71, 1, '2017-11-07 23:40:06'),
(72, 1, '2017-11-07 23:41:13'),
(73, 1, '2017-10-31 23:42:42');

-- --------------------------------------------------------

--
-- Структура таблицы `event_details`
--

CREATE TABLE IF NOT EXISTS `event_details` (
  `id` int(11) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `desc` varchar(255) NOT NULL,
  `id_employee` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `event_details`
--

INSERT INTO `event_details` (`id`, `start`, `end`, `desc`, `id_employee`) VALUES
(70, '2017-11-16 12:00:00', '2017-11-16 15:00:00', 'dasdasasd', 3),
(71, '2017-11-23 12:00:00', '2017-11-23 15:00:00', 'dasdasdasd', 5),
(71, '2017-11-30 12:00:00', '2017-11-30 15:00:00', 'dasdasdasd', 5),
(72, '2017-11-09 12:00:00', '2017-11-09 15:00:00', 'dasdasdasd', 3),
(73, '2017-11-02 12:00:00', '2017-11-02 16:00:00', 'sadasasd', 5);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `rooms`
--

INSERT INTO `rooms` (`id`, `name`) VALUES
(1, 'room1'),
(2, 'room2'),
(3, 'room3');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
