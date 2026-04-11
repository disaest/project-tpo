-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 11 2026 г., 06:51
-- Версия сервера: 8.0.30
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `busk_baza`
--

-- --------------------------------------------------------

--
-- Структура таблицы `card_recipes`
--

CREATE TABLE `card_recipes` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `ingridients` varchar(255) NOT NULL,
  `portions` varchar(20) NOT NULL,
  `time_for_cook` varchar(20) NOT NULL,
  `tutorial` text NOT NULL,
  `images` varchar(255) NOT NULL,
  `likes` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `card_recipes`
--

INSERT INTO `card_recipes` (`id`, `title`, `icon`, `description`, `ingridients`, `portions`, `time_for_cook`, `tutorial`, `images`, `likes`) VALUES
(1, 'Том-Ям', '', 'Том ям — кисло-острый суп с креветками, курицей, рыбой или другими морепродуктами. Национальное блюдо Лаоса и Таиланда. Также употребляется в соседних странах: Малайзии, Сингапуре и Индонезии.', 'Куриный бульон / Вода - 500мл, \r\nКреветки - 200 гр, \r\nПомидоры Черри - 4 шт, \r\nПетрушка, \r\nКокосовое молоко / Сливки - 200 мл,\r\nЛимон - 2 шт ,\r\nШампиньоны - 100 гр,\r\nЛемонграсс.', '2-3', '40 минут', 'Перед приготовлением любого блюда нужно подготовить все используемые ингредиенты. Заранее вытащите их из холодильника и доведите до комнатной температуры. Особенно это касается креветок так как искусственными методами правильно разморозить их сложно и при малейшей ошибке они могут потерять свои вкусовые свойства. \r\n\r\nНарежьте лимоны и помидоры на дольки, Шампиньоны нарезать на слайсы.\r\n\r\nНа этом этапе наше блюдо готово. По желанию можете украсить сверху сливками и еще разными травами\r\nТакже рекомендую есть Том-Ям с рисом, чтобы слегка нейтрализовать остроту!', '', ''),
(2, 'Харчо', '', 'Харчо́ — национальный грузинский суп из говядины с рисом, грецкими орехами и тклапи или кислым соусом ткемали. Суп очень пряный, умеренно острый, с обилием чеснока и зелени', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `icon` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `recipes`
--

CREATE TABLE `recipes` (
  `id` int NOT NULL,
  `title` varchar(200) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `likes` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `login` varchar(50) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `pass`) VALUES
(1, 'buserov@icloud.com', 'qwerty78'),
(2, 'user2', '123');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `card_recipes`
--
ALTER TABLE `card_recipes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `card_recipes`
--
ALTER TABLE `card_recipes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
