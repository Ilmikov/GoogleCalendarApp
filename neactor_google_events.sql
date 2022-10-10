-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 10 2022 г., 11:01
-- Версия сервера: 10.4.24-MariaDB
-- Версия PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `events`
--

-- --------------------------------------------------------

--
-- Структура таблицы `neactor_google_events`
--

CREATE TABLE `neactor_google_events` (
  `google_calendar_event_id` text NOT NULL,
  `neactor_id` text NOT NULL,
  `summary` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` text NOT NULL,
  `datatime_start` datetime NOT NULL,
  `datatime_end` datetime NOT NULL,
  `attendees` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `neactor_google_events`
--

INSERT INTO `neactor_google_events` (`google_calendar_event_id`, `neactor_id`, `summary`, `description`, `location`, `datatime_start`, `datatime_end`, `attendees`) VALUES
('vr82lo3lidu0kp67ic3th8jnuc', 'biba', 'Google I/O 2015', 'A chance to hear more about Googles developer products.', 'Perm', '2022-09-05 09:00:00', '2022-09-05 17:00:00', 'a:1:{i:0;a:1:{s:5:\"email\";s:21:\"iluha.mikov@gmail.com\";}}');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
