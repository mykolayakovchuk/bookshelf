-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Апр 10 2021 г., 23:15
-- Версия сервера: 5.6.47
-- Версия PHP: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `bookshelf`
--

-- --------------------------------------------------------

--
-- Структура таблицы `author`
--

CREATE TABLE `author` (
  `idAuthor` int(11) NOT NULL,
  `nameAuthor` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surnameAuthor` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Comment` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `author`
--

INSERT INTO `author` (`idAuthor`, `nameAuthor`, `surnameAuthor`, `Comment`) VALUES
(1, 'Александр', 'Пушкин', NULL),
(3, 'Алексей', 'Толстой', NULL),
(4, 'Михаил', 'Лермонтов', NULL),
(5, 'Николай', 'Гоголь', NULL),
(6, 'Фёдор', 'Достоевский', 'тест'),
(7, 'Илья', 'Ильф', NULL),
(8, 'Евгений', 'Петров', NULL);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `authors_for_join`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `authors_for_join` (
`idBook` int(11)
,`Authors` text
);

-- --------------------------------------------------------

--
-- Структура таблицы `book`
--

CREATE TABLE `book` (
  `idBook` int(11) NOT NULL,
  `nameBook` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `year` year(4) NOT NULL,
  `Comment` text COLLATE utf8mb4_unicode_ci,
  `imagelink` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `book`
--

INSERT INTO `book` (`idBook`, `nameBook`, `year`, `Comment`, `imagelink`) VALUES
(1, 'Сказка о царе Салтане', 1968, NULL, 'Салтан61HLbKabs5L.jpg'),
(2, 'Сборник стихов', 1978, 'В сборник вошли лучшие произведения автора', 'Лермонтов_Михаил_обложка_Правда_1989.jpg'),
(3, 'Война и мир', 1980, 'Массивное произведение которое никого не оставит равнодушным.', 'cover3d1.jpg'),
(5, 'Игрок', 1987, NULL, 'igrok578166.jpg'),
(6, 'Капитанская дочка', 1967, NULL, NULL),
(11, 'Золотой Телёнок', 1983, 'Продолжение культовой книги. Остап продолжает свой путь к успеху...', 'золотой теленок.jpg'),
(15, 'Пиковая дама', 1976, 'Мистический рассказ', NULL),
(16, 'Я памятник себе воздвиг', 0000, NULL, NULL),
(17, 'Колобок', 1999, 'Приключения юноши, рано покинувшего отчий дом...', 'Колобуха.jpg'),
(18, 'Нос', 1956, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `idauthorbook`
--

CREATE TABLE `idauthorbook` (
  `idid` int(11) NOT NULL,
  `idAuthor` int(11) NOT NULL,
  `idBook` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `idauthorbook`
--

INSERT INTO `idauthorbook` (`idid`, `idAuthor`, `idBook`) VALUES
(1, 1, 1),
(2, 4, 2),
(3, 3, 3),
(5, 6, 5),
(6, 1, 6),
(12, 7, 11),
(13, 8, 11),
(17, 1, 15),
(18, 1, 16),
(23, 1, 17),
(24, 5, 18);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `idauthorbook_with_author_names`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `idauthorbook_with_author_names` (
`idid` int(11)
,`idBook` int(11)
,`Author` varchar(101)
);

-- --------------------------------------------------------

--
-- Дублирующая структура для представления `mainview`
-- (См. Ниже фактическое представление)
--
CREATE TABLE `mainview` (
`idBook` int(11)
,`nameBook` text
,`year` year(4)
,`Comment` text
,`imagelink` text
,`Authors` text
);

-- --------------------------------------------------------

--
-- Структура таблицы `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура для представления `authors_for_join`
--
DROP TABLE IF EXISTS `authors_for_join`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yakovchuk`@`localhost` SQL SECURITY DEFINER VIEW `authors_for_join`  AS  select `idauthorbook_with_author_names`.`idBook` AS `idBook`,group_concat(`idauthorbook_with_author_names`.`Author` separator ',') AS `Authors` from `idauthorbook_with_author_names` group by `idauthorbook_with_author_names`.`idBook` ;

-- --------------------------------------------------------

--
-- Структура для представления `idauthorbook_with_author_names`
--
DROP TABLE IF EXISTS `idauthorbook_with_author_names`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yakovchuk`@`localhost` SQL SECURITY DEFINER VIEW `idauthorbook_with_author_names`  AS  select `idauthorbook`.`idid` AS `idid`,`idauthorbook`.`idBook` AS `idBook`,concat(`author`.`surnameAuthor`,' ',`author`.`nameAuthor`) AS `Author` from (`idauthorbook` left join `author` on((`idauthorbook`.`idAuthor` = `author`.`idAuthor`))) ;

-- --------------------------------------------------------

--
-- Структура для представления `mainview`
--
DROP TABLE IF EXISTS `mainview`;

CREATE ALGORITHM=UNDEFINED DEFINER=`yakovchuk`@`localhost` SQL SECURITY DEFINER VIEW `mainview`  AS  select `book`.`idBook` AS `idBook`,`book`.`nameBook` AS `nameBook`,`book`.`year` AS `year`,`book`.`Comment` AS `Comment`,`book`.`imagelink` AS `imagelink`,`authors_for_join`.`Authors` AS `Authors` from (`book` join `authors_for_join` on((`authors_for_join`.`idBook` = `book`.`idBook`))) ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`idAuthor`);

--
-- Индексы таблицы `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`idBook`);

--
-- Индексы таблицы `idauthorbook`
--
ALTER TABLE `idauthorbook`
  ADD PRIMARY KEY (`idid`),
  ADD KEY `idAuthor` (`idAuthor`),
  ADD KEY `idBook` (`idBook`);

--
-- Индексы таблицы `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `author`
--
ALTER TABLE `author`
  MODIFY `idAuthor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `book`
--
ALTER TABLE `book`
  MODIFY `idBook` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT для таблицы `idauthorbook`
--
ALTER TABLE `idauthorbook`
  MODIFY `idid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `idauthorbook`
--
ALTER TABLE `idauthorbook`
  ADD CONSTRAINT `idauthorbook_ibfk_1` FOREIGN KEY (`idBook`) REFERENCES `book` (`idBook`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `idauthorbook_ibfk_2` FOREIGN KEY (`idAuthor`) REFERENCES `author` (`idAuthor`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
