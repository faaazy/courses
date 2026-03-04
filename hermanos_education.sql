-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 04 2026 г., 16:29
-- Версия сервера: 8.0.30
-- Версия PHP: 8.0.22

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
  `client_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `clients`
--

INSERT INTO `clients` (`client_id`, `email`, `password`, `first_name`, `last_name`, `phone`) VALUES
(1, 'efimov-2018@bk.ru', '$2y$10$sVLRI6RVBLWkMgMK.Oa.G.6MO7cn20u0MNGJe28bU8g9e0SMx2hM2', 'Ярослав', 'Ефимов', '89124567890'),
(2, 'mynew@email.com', '$2y$10$JSNA2RyaVabp3Oj/B3XrE.Vhw02XB.KK9CUTNGdiGuBS9gqfDDjTW', 'Иван', 'Иванов', '79124527020'),
(3, 'efimov-2019@bk.ru', '$2y$10$Qs8CYuxoJB8uvnz0AAUDX.jRUjXQmydqjibVxIKb7vEVu33VdMR4K', 'Ярослав2', 'Ефимов2', '79042212222'),
(5, 'efimov-2021@bk.ru', '$2y$10$2aQ.t6/b5GWUQg0cvr8z1utfOA4u7Ck9jTFGX5rv4vQ5l7MnCTDkG', 'Ярослав4', 'Ефимов4', '89041234567'),
(6, 'bronnikov-2018@bk.ru', '$2y$10$.exZO7/VOCT0uAjl.pAEt.ZOQvT.sh9kegR/T0e6d5tR9G/ffvdjW', 'Бронников', 'Владиславов', '81112223344');

-- --------------------------------------------------------

--
-- Структура таблицы `courses`
--

CREATE TABLE `courses` (
  `course_id` int UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `description` text,
  `level` enum('Начальный','Средний','Продвинутый') NOT NULL DEFAULT 'Начальный',
  `duration_hours` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `courses`
--

INSERT INTO `courses` (`course_id`, `title`, `image_url`, `description`, `level`, `duration_hours`) VALUES
(1, 'Стратегия цифрового маркетинга 2026', 'http://localhost/hermanosEducation/img/course-1.avif', 'Освойте современные инструменты digital-маркетинга: SMM, SEO, таргетинг и аналитику.', 'Начальный', 28),
(2, 'Разработка iOS и Android приложений', 'http://localhost/hermanosEducation/img/course-3.jpg', 'Создавайте кроссплатформенные мобильные приложения с нуля на Flutter.', 'Средний', 45),
(3, 'Бизнес-аналитика и визуализация данных', 'http://localhost/hermanosEducation/img/course-4.webp', 'Анализируйте данные и принимайте решения на основе цифр. Power BI, Tableau.', 'Начальный', 26),
(4, 'Веб-разработка: Full Stack с нуля', 'http://localhost/hermanosEducation/img/course-2.jpeg', 'HTML, CSS, JavaScript, PHP, MySQL — полный курс от вёрстки до бэкенда.', 'Начальный', 60),
(5, 'Data Science и машинное обучение', 'http://localhost/hermanosEducation/img/course-5.jpg', 'Python, Pandas, Scikit-learn и нейронные сети для анализа данных.', 'Средний', 50),
(6, 'UX/UI Дизайн: от идеи до прототипа', 'http://localhost/hermanosEducation/img/course-6.png', 'Figma, пользовательские исследования и построение дизайн-систем.', 'Начальный', 32);

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `order_id` int UNSIGNED NOT NULL,
  `client_id` int NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `rates_id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `expires_at` date NOT NULL,
  `status` enum('pending','paid','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`order_id`, `client_id`, `course_id`, `rates_id`, `payment_id`, `expires_at`, `status`) VALUES
(1, 1, 1, 1, 1, '2027-03-04', 'pending'),
(2, 1, 4, 4, 2, '2027-03-04', 'pending'),
(3, 1, 4, 4, 3, '2027-03-04', 'pending'),
(4, 1, 6, 6, 4, '2027-03-04', 'pending'),
(5, 1, 4, 4, 5, '2027-03-04', 'pending');

-- --------------------------------------------------------

--
-- Структура таблицы `order_payments`
--

CREATE TABLE `order_payments` (
  `op_id` int UNSIGNED NOT NULL,
  `order_id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `order_payments`
--

INSERT INTO `order_payments` (`op_id`, `order_id`, `payment_id`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 4, 4),
(5, 5, 5);

-- --------------------------------------------------------

--
-- Структура таблицы `payments`
--

CREATE TABLE `payments` (
  `payment_id` int UNSIGNED NOT NULL,
  `client_id` int NOT NULL,
  `rates_id` int UNSIGNED NOT NULL,
  `amount` int NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL DEFAULT 'card',
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `payments`
--

INSERT INTO `payments` (`payment_id`, `client_id`, `rates_id`, `amount`, `payment_date`, `payment_method`, `status`) VALUES
(1, 1, 1, 5490, '2026-03-04 13:14:20', 'card', 'paid'),
(2, 1, 4, 7990, '2026-03-04 13:14:36', 'card', 'paid'),
(3, 1, 4, 7990, '2026-03-04 13:14:37', 'card', 'paid'),
(4, 1, 6, 4990, '2026-03-04 13:20:00', 'card', 'paid'),
(5, 1, 4, 7990, '2026-03-04 13:26:26', 'card', 'paid');

-- --------------------------------------------------------

--
-- Структура таблицы `rates`
--

CREATE TABLE `rates` (
  `rates_id` int UNSIGNED NOT NULL,
  `course_id` int UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int NOT NULL,
  `description` text,
  `access_duration_days` int UNSIGNED NOT NULL DEFAULT '365'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
  `role_id` int NOT NULL,
  `client_id` int NOT NULL,
  `role_name` enum('admin','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`role_id`, `client_id`, `role_name`) VALUES
(1, 2, 'user'),
(2, 3, 'user'),
(4, 5, 'user'),
(5, 6, 'user');

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
-- Индексы таблицы `order_payments`
--
ALTER TABLE `order_payments`
  ADD PRIMARY KEY (`op_id`),
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
  MODIFY `client_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `courses`
--
ALTER TABLE `courses`
  MODIFY `course_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `order_payments`
--
ALTER TABLE `order_payments`
  MODIFY `op_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `rates`
--
ALTER TABLE `rates`
  MODIFY `rates_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Ограничения внешнего ключа таблицы `order_payments`
--
ALTER TABLE `order_payments`
  ADD CONSTRAINT `fk_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_items_plan` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`payment_id`) ON DELETE CASCADE;

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
