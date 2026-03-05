-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 05 2026 г., 22:30
-- Версия сервера: 5.7.39
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `hermanos_education`
--

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

CREATE TABLE `clients` (
  `client_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`client_id`, `email`, `password`, `first_name`, `last_name`, `phone`) VALUES
(1, 'admin@test.com', '$2y$10$Khgbr4707kc4zUmwDEStg.22CLe2gOp7DxJuz.Wess1ukB3S2cpym', 'Admin', 'Admin', '00000000000'),
(2, 'my@email.com', '$2y$10$E0WqX5BJ6lWhSITvXvAkM.XOm6ApbTLIgy2lSJyHuCVaa9XOheMki', 'Иван', 'Иванов', '89001234567'),
(3, 'test1@test.com', '$2y$10$5/yOvUGLbJQe92z.F9grhOw/WclFg3iuo81wJWpKuNhIPvzSnF6wi', 'test', 'test', '89001234567');

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `course_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `description` text,
  `level` enum('Начальный','Средний','Продвинутый') NOT NULL DEFAULT 'Начальный',
  `duration_hours` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`course_id`, `title`, `image_url`, `description`, `level`, `duration_hours`) VALUES
(1, 'Стратегия цифрового маркетинга 2026', 'http://localhost/hermanosEducation/images/course-1.avif', 'Освойте современные инструменты digital-маркетинга: SMM, SEO, таргетинг и аналитику.', 'Начальный', 28),
(2, 'Разработка iOS и Android приложений', 'http://localhost/hermanosEducation/images/course-3.jpg', 'Создавайте кроссплатформенные мобильные приложения с нуля на Flutter.', 'Средний', 45),
(3, 'Бизнес-аналитика и визуализация данных', 'http://localhost/hermanosEducation/images/course-4.webp', 'Анализируйте данные и принимайте решения на основе цифр. Power BI, Tableau.', 'Начальный', 26),
(4, 'Веб-разработка: Full Stack с нуля', 'http://localhost/hermanosEducation/images/course-2.jpeg', 'HTML, CSS, JavaScript, PHP, MySQL — полный курс от вёрстки до бэкенда.', 'Начальный', 60),
(5, 'Data Science и машинное обучение', 'http://localhost/hermanosEducation/images/course-5.jpg', 'Python, Pandas, Scikit-learn и нейронные сети для анализа данных.', 'Средний', 50),
(6, 'UX/UI Дизайн: от идеи до прототипа', 'http://localhost/hermanosEducation/images/course-6.png', 'Figma, пользовательские исследования и построение дизайн-систем.\r\nВы научитесь создавать бизнес-интерфейсы', 'Начальный', 35);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `order_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `rates_id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(10) UNSIGNED NOT NULL,
  `expires_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`order_id`, `client_id`, `course_id`, `rates_id`, `payment_id`, `expires_at`) VALUES
(1, 2, 1, 1, 2, '2027-03-05'),
(2, 2, 4, 4, 5, '2027-03-05'),
(3, 2, 1, 1, 6, '2026-03-04'),
(4, 1, 4, 4, 7, '2026-03-04'),
(5, 3, 5, 5, 8, '2027-03-05'),
(6, 3, 4, 4, 9, '2026-03-04');

-- --------------------------------------------------------

--
-- Структура таблицы `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `payment_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `payment_id`) VALUES
(1, 1, 2),
(2, 2, 5),
(3, 3, 6),
(4, 4, 7),
(5, 5, 8),
(6, 6, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(10) UNSIGNED NOT NULL,
  `client_id` int(11) NOT NULL,
  `rates_id` int(11) UNSIGNED NOT NULL,
  `amount` int(10) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL DEFAULT 'card',
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`payment_id`, `client_id`, `rates_id`, `amount`, `payment_date`, `payment_method`, `status`) VALUES
(1, 2, 1, 5490, '2026-03-05 08:37:05', 'card', 'cancelled'),
(2, 2, 1, 5490, '2026-03-05 08:37:23', 'card', 'paid'),
(3, 2, 4, 7990, '2026-03-05 08:51:20', 'card', 'cancelled'),
(4, 2, 4, 7990, '2026-03-05 08:51:34', 'card', 'cancelled'),
(5, 2, 4, 7990, '2026-03-05 08:51:46', 'card', 'paid'),
(6, 2, 1, 5490, '2026-03-05 08:52:16', 'card', 'paid'),
(7, 1, 4, 0, '2026-03-05 09:36:06', 'manual', 'paid'),
(8, 3, 5, 8990, '2026-03-05 10:07:07', 'card', 'paid'),
(9, 3, 4, 0, '2026-03-05 10:37:11', 'manual', 'paid');

-- --------------------------------------------------------

--
-- Структура таблицы `rates`
--

CREATE TABLE `rates` (
  `rates_id` int(11) UNSIGNED NOT NULL,
  `course_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(10) NOT NULL,
  `description` text,
  `access_duration_days` int(10) UNSIGNED NOT NULL DEFAULT '365'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Дамп данных таблицы `rates`
--

INSERT INTO `rates` (`rates_id`, `course_id`, `name`, `price`, `description`, `access_duration_days`) VALUES
(1, 1, 'Стандарт', 5490, NULL, 365),
(2, 2, 'Стандарт', 6990, NULL, 365),
(3, 3, 'Стандарт', 4490, NULL, 365),
(4, 4, 'Стандарт', 7990, NULL, 365),
(5, 5, 'Стандарт', 8990, NULL, 365),
(6, 6, 'Стандарт', 4990, NULL, 365);

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `role_name` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`role_id`, `client_id`, `role_name`) VALUES
(1, 1, 'admin'),
(2, 2, 'user'),
(3, 3, 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Индексы таблицы `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `payment_id` (`payment_id`),
  ADD KEY `rates_id` (`rates_id`);

--
-- Индексы таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `fk_items_order` (`order_id`),
  ADD KEY `fk_items_plan` (`payment_id`);

--
-- Индексы таблицы `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `rates_id` (`rates_id`);

--
-- Индексы таблицы `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`rates_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD KEY `client_id` (`client_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `rates`
--
ALTER TABLE `rates`
  MODIFY `rates_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_4` FOREIGN KEY (`rates_id`) REFERENCES `rates` (`rates_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_items_plan` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`rates_id`) REFERENCES `rates` (`rates_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `rates_ibfk_1` FOREIGN KEY (`course_id`) REFERENCES `courses` (`course_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `roles`
--
ALTER TABLE `roles`
  ADD CONSTRAINT `roles_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
