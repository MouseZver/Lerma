-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 17 2020 г., 22:34
-- Версия сервера: 8.0.19
-- Версия PHP: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `git`
--

-- --------------------------------------------------------

--
-- Структура таблицы `lerma`
--

CREATE TABLE `lerma` (
  `id` int NOT NULL,
  `name` text NOT NULL,
  `num` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Дамп данных таблицы `lerma`
--

INSERT INTO `lerma` (`id`, `name`, `num`) VALUES
(138, 'Nouvu\\Database\\Lerma', 111),
(139, 'Nouvu\\Database\\ComponentFetch', 111),
(140, 'php7.4', 111),
(141, 'Database', 111),
(142, 'Nouvu\\Database\\Core', 222),
(143, 'InterfaceDriver', 333),
(144, 'Nouvu\\Database\\LermaStatement', 333);

-- --------------------------------------------------------

--
-- Структура таблицы `testingspeed`
--

CREATE TABLE `testingspeed` (
  `id` int NOT NULL,
  `num` int NOT NULL,
  `message` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `lerma`
--
ALTER TABLE `lerma`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `testingspeed`
--
ALTER TABLE `testingspeed`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `lerma`
--
ALTER TABLE `lerma`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT для таблицы `testingspeed`
--
ALTER TABLE `testingspeed`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
