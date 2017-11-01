-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 01 2017 г., 15:03
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `employees`
--

INSERT INTO `employees` (`id`, `name`, `email`, `pass`, `id_role`, `hash`) VALUES
(1, 'Iluha', 'iluha@mail.ru', '95d53bf24b4c267e64fa593e5d543780', 2, 'uuQAwOjLlLVYorkvvHft'),
(2, 'valera', 'valera@mail.ru', 'qweqwe11', 1, ''),
(3, 'gena', 'gena@mail.ru', 'qweqwe11', 1, ''),
(4, 'vasya', 'vasa@mail.ru', 'qweqwe11', 1, '');

-- --------------------------------------------------------

--
-- Структура таблицы `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(255) NOT NULL,
  `id_employee` int(11) NOT NULL,
  `id_room` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `events`
--

INSERT INTO `events` (`id`, `description`, `id_employee`, `id_room`) VALUES
(1, 'eqwewqeqweqweqwe', 2, 1),
(2, 'asdasdasdasdasd', 3, 2),
(3, 'qwewqeqwewa', 2, 3),
(4, 'sdasdadas', 3, 3),
(5, 'ccccccccccccccccccccc', 2, 2),
(6, 'sssssssssssssssss', 3, 3),
(7, 'xxxxxxxxxxx', 3, 2),
(8, 'bnmbnm', 2, 1),
(9, 'vcbcvb', 3, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `event_details`
--

CREATE TABLE IF NOT EXISTS `event_details` (
  `id` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `event_details`
--

INSERT INTO `event_details` (`id`, `start`, `end`) VALUES
(1, '2017-10-31 16:00:00', '2017-10-31 17:00:00'),
(2, '2017-10-31 18:00:00', '2017-10-31 19:00:00'),
(3, '2017-11-07 09:30:00', '2017-11-07 10:30:00'),
(4, '2017-11-07 12:00:00', '2017-11-07 13:00:00'),
(5, '2017-11-02 13:00:00', '2017-11-02 15:00:00'),
(5, '2017-11-09 13:00:00', '2017-11-09 15:00:00'),
(6, '2017-11-03 08:00:00', '2017-11-03 12:00:00'),
(6, '2017-11-17 08:00:00', '2017-11-17 12:00:00'),
(7, '2017-11-27 12:00:00', '2017-11-27 14:00:00'),
(8, '2017-11-09 09:00:00', '2017-11-09 11:00:00'),
(9, '2017-11-14 11:00:00', '2017-11-14 13:00:00');

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
