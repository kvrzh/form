-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Янв 23 2018 г., 17:18
-- Версия сервера: 5.6.37
-- Версия PHP: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `form`
--

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `hashedPassword` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `hashedPassword`, `email`, `image`) VALUES
(2, 'kvrzh', '$2y$10$FAhL4opi7VjwQw4zQaBjLuiY8wJuSOoIhBlWIbWHqsGwQtLyz4cge', 'kovr.anton@gmail.com', ''),
(31, 'anton07', '$2y$10$8O70EppVmPV6xx9XvcKLOOhLeloIl85UJQrPgediOBTuCG0KvTjVq', 'kovr.anton@gmail.ro', 'anton07_AjvZ4o3yHCA.jpg'),
(48, 'swewe', '$2y$10$ZVNbHWO/2OCJwT0eyYEw2eWsHjp.5M5H7ZiUHc2Lg6d2rqMS5QJPC', 'kovr.anton@mail.ru', NULL),
(72, 'maiweqwq', '$2y$10$hrm.t2a7N4tLu4HlcAPfeO/RXVW/2smJ8F/zRLWewve3quonoFRLe', 'mail@mail.com', NULL),
(75, 'mihaloh', '$2y$10$fXXRgOXchGq7dFKP9ZuUweOxUoEl9AlcsbYkfoQHDfZU61TC/CSsS', 'kovr.anton@gmail.cwww', NULL),
(78, 'zzzwwweee', '$2y$10$deKwC6DwCDNO08GUAPqvwOsvXan486BZnjKauqA4DMpuTKDXaFmNe', 'zzzwwweee@gmail.com', NULL),
(80, 'newUser', '$2y$10$hLirVHOE1vnrTt5rz0asCOP8AlDFlWMUeJX78cwCZaCDySrZpVpU6', 'kovr.anton@gmail.ua', NULL),
(83, 'kvrzhy', '$2y$10$SFX4i5dCxVWy8TvvYwZOWuidGw.ASpY.TPIXgpe1c2KTukRpatHem', 'kovr.anton@gmail.comy', NULL),
(85, 'anton07076', '$2y$10$VTU4R5LZSo5qMTGiqzHStuO6EAZfJMba5yCaL9jPX/liZG738I3Ju', 'anton@gmail.ru', NULL),
(90, 'anton07070707', '$2y$10$GBmZ82s7XHNjSx40kZ8eM.iWK8PNnL2Xqsfr6aoDDKEQ2HbvyCmYG', 'kovr.anton@in.ua', NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_2` (`login`),
  ADD UNIQUE KEY `email_2` (`email`),
  ADD KEY `login` (`login`),
  ADD KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
