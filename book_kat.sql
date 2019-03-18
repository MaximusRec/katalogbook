-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 18 2019 г., 12:47
-- Версия сервера: 5.6.41
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
-- База данных: `book_kat`
--

-- --------------------------------------------------------

--
-- Структура таблицы `t_author`
--

CREATE TABLE `t_author` (
  `author_id` int(11) NOT NULL COMMENT 'id автора',
  `name_author` varchar(256) NOT NULL COMMENT 'Имя автора'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список авторов';

--
-- Дамп данных таблицы `t_author`
--

INSERT INTO `t_author` (`author_id`, `name_author`) VALUES
(6, 'Роман Злотников'),
(7, 'Василий Иванович Мельник'),
(8, 'Артур Темиржанов'),
(9, 'Дэвид Гаймер');

-- --------------------------------------------------------

--
-- Структура таблицы `t_author_book`
--

CREATE TABLE `t_author_book` (
  `book_id` int(11) NOT NULL COMMENT 'справочный номер книги',
  `author_id` int(11) NOT NULL COMMENT 'справочный номер автора'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `t_author_book`
--

INSERT INTO `t_author_book` (`book_id`, `author_id`) VALUES
(7, 6),
(7, 7),
(8, 8),
(9, 9);

-- --------------------------------------------------------

--
-- Структура таблицы `t_book`
--

CREATE TABLE `t_book` (
  `book_id` int(11) NOT NULL COMMENT 'id книги',
  `namebook` varchar(512) NOT NULL COMMENT 'название книги',
  `heading_id` int(6) NOT NULL COMMENT 'id издательства',
  `photo` varchar(512) NOT NULL COMMENT 'фото книги',
  `date_heading` date NOT NULL COMMENT 'дата издательства',
  `publishing_id` int(6) NOT NULL COMMENT 'id издательства',
  `file` varchar(1024) NOT NULL COMMENT 'название файла, автогенерация php',
  `typeLoadFile` int(11) DEFAULT NULL COMMENT 'источник загружки (1-локально, 2-внешний)',
  `creared` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_time` datetime NOT NULL COMMENT 'время изменения парам-в книги',
  `url` varchar(1024) NOT NULL COMMENT 'адрес внешнего источника, если есть'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Перечень книг';

--
-- Дамп данных таблицы `t_book`
--

INSERT INTO `t_book` (`book_id`, `namebook`, `heading_id`, `photo`, `date_heading`, `publishing_id`, `file`, `typeLoadFile`, `creared`, `update_time`, `url`) VALUES
(7, 'Пираты XXX века', 16, 'f606ca731d0309d0879e811fe3a4e3b7.jpg', '2019-03-18', 1, '3aab3f465c17cd33f6511cc75db8b331.txt', 1, '2019-03-18 05:24:49', '0000-00-00 00:00:00', ''),
(8, 'Человек, который построил Эдем', 15, '5b09acd56e198416b9b00b54785a0910.jpg', '2019-03-18', 3, 'f4dd5fe0454130edba9021a0516d6ec0.txt', 1, '2019-03-18 08:34:05', '2019-03-18 11:35:31', ''),
(9, 'Я – СТРЕЛА. АКАДЕМИЯ СТРАЖЕЙ (СИ)', 14, '81107978355c86e57daa165dc33ce796.jpg', '2019-03-18', 1, '36bf8e760087c56538908c8a55504495.txt', 2, '2019-03-18 08:48:10', '2019-03-18 11:49:59', 'https://knigolub.net/uploads/book/Subbota_Akademiya-strazhey_1_Otbor-v-Akademiyu-Strazhey.txt');

-- --------------------------------------------------------

--
-- Структура таблицы `t_book_dop_images`
--

CREATE TABLE `t_book_dop_images` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `namefile_images` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `t_book_dop_images`
--

INSERT INTO `t_book_dop_images` (`id`, `book_id`, `namefile_images`) VALUES
(15, 6, 'd4697d3c9e940be4ae97cabcb8cade82.png'),
(17, 8, '55baf18aa454b5a02740f7ca007e1cfa.jpg'),
(18, 8, '6ba567233c40669bcd838606067efad9.jpg'),
(19, 8, '32730dcff6e77fe84dc1de03e31ad101.jpg'),
(20, 9, '695ec701d67f5c7ab841a4bb62f2e094.jpg'),
(21, 9, '60dfe241937cebbb0a18bc35dfbe939f.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `t_heading`
--

CREATE TABLE `t_heading` (
  `heading_id` int(11) NOT NULL COMMENT 'id рубрики',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'родительское id',
  `name_heading` varchar(256) NOT NULL COMMENT 'название рубрики'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Рубрики';

--
-- Дамп данных таблицы `t_heading`
--

INSERT INTO `t_heading` (`heading_id`, `parent_id`, `name_heading`) VALUES
(1, 0, 'История'),
(2, 1, 'Мировая'),
(3, 1, 'Средних веков'),
(4, 3, 'Страны'),
(5, 4, 'Украина'),
(6, 4, 'США'),
(7, 0, 'Программирование'),
(8, 7, 'PHP'),
(9, 7, 'JAVA'),
(13, 4, 'енукене'),
(14, 0, 'Художественная'),
(15, 14, 'Боевая фантастика'),
(16, 14, 'Космическая фантастика');

-- --------------------------------------------------------

--
-- Структура таблицы `t_publishing`
--

CREATE TABLE `t_publishing` (
  `publishing_id` int(11) NOT NULL COMMENT 'id издательства',
  `name_publishing` varchar(512) NOT NULL COMMENT 'Название издательства',
  `addres_publishing` varchar(512) NOT NULL,
  `tel_publishing` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Издательства';

--
-- Дамп данных таблицы `t_publishing`
--

INSERT INTO `t_publishing` (`publishing_id`, `name_publishing`, `addres_publishing`, `tel_publishing`) VALUES
(1, 'Издательство 1', 'г.Днепр, пр. К.Маркса 111', '+(380)5555555'),
(2, 'Издательство 2', 'г. Днепр. пр.Гагарина д,2', '+(380)3333333'),
(3, 'Издательство 3', 'г.Днепр, пр. К.Маркса 112', '+(380)3333334'),
(5, 'Издательство 4', 'г.Днепр', '+(380)3333334');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `t_author`
--
ALTER TABLE `t_author`
  ADD PRIMARY KEY (`author_id`);

--
-- Индексы таблицы `t_book`
--
ALTER TABLE `t_book`
  ADD PRIMARY KEY (`book_id`);

--
-- Индексы таблицы `t_book_dop_images`
--
ALTER TABLE `t_book_dop_images`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `t_heading`
--
ALTER TABLE `t_heading`
  ADD PRIMARY KEY (`heading_id`);

--
-- Индексы таблицы `t_publishing`
--
ALTER TABLE `t_publishing`
  ADD PRIMARY KEY (`publishing_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `t_author`
--
ALTER TABLE `t_author`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id автора', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `t_book`
--
ALTER TABLE `t_book`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id книги', AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT для таблицы `t_book_dop_images`
--
ALTER TABLE `t_book_dop_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `t_heading`
--
ALTER TABLE `t_heading`
  MODIFY `heading_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id рубрики', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `t_publishing`
--
ALTER TABLE `t_publishing`
  MODIFY `publishing_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id издательства', AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
