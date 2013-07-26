-- phpMyAdmin SQL Dump
-- version 3.5.8
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 28, 2013 at 10:46 AM
-- Server version: 5.5.30-30.2-log
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ygeorgi_portfolio`
--

-- --------------------------------------------------------

--
-- Table structure for table `cv_education`
--

CREATE TABLE IF NOT EXISTS `cv_education` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '#',
  `school` text NOT NULL COMMENT 'Училище',
  `level` int(2) NOT NULL COMMENT 'Класове',
  `years` varchar(550) NOT NULL COMMENT 'Години на обучение',
  `added` int(11) NOT NULL COMMENT 'Добавяне',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Образование - CV' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `cv_education`
--

INSERT INTO `cv_education` (`id`, `school`, `level`, `years`, `added`) VALUES
(1, 'Първо Основно Училище &quot;Иван Вазов&quot;', 1, '2006 - 2010', 1369831677);

-- --------------------------------------------------------

--
-- Table structure for table `cv_skills`
--

CREATE TABLE IF NOT EXISTS `cv_skills` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '#',
  `name` varchar(550) NOT NULL COMMENT 'Име',
  `desc` varchar(550) NOT NULL COMMENT 'Описание',
  `rate` int(1) NOT NULL COMMENT 'Колко го знам',
  `added` int(11) NOT NULL COMMENT 'Добавен',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Опит' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `cv_skills`
--

INSERT INTO `cv_skills` (`id`, `name`, `desc`, `rate`, `added`) VALUES
(1, 'PHP', 'MVC Structure, Procedural &amp; OOP style', 4, 1369832332),
(2, 'HTML5 &amp; CSS3', 'Най-новите стандарти!', 3, 1369832358),
(3, 'JavaScript', 'AJAX &amp; jQuery', 3, 1369832370),
(4, 'VB.NET', 'VisualBasic.NET', 2, 1369832378),
(5, 'C#', 'CSharp', 1, 1369832403);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '#',
  `value` text NOT NULL COMMENT 'Съобщение',
  `ip` varchar(15) NOT NULL COMMENT 'IP',
  `page` text NOT NULL COMMENT 'Страница',
  `saved` int(10) NOT NULL COMMENT 'Дата на записване',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Логове' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '#',
  `title` varchar(550) NOT NULL COMMENT 'Заглавие',
  `content` longtext NOT NULL COMMENT 'Съдържание',
  `key` varchar(10) NOT NULL COMMENT 'Съдържание',
  `isHome` int(1) NOT NULL DEFAULT '0' COMMENT 'Начална страница',
  `visiable` int(1) NOT NULL DEFAULT '1',
  `author` int(11) NOT NULL COMMENT 'Автор',
  `added` int(11) NOT NULL COMMENT 'Добавяне',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Страници' AUTO_INCREMENT=2 ;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `key`, `isHome`, `visiable`, `author`, `added`) VALUES
(1, 'За мен', '<p><strong>Хей! Аз съм Ясен Георгиев и съм на четиринадесет години. Занимавам се с музика, театрално изкуства, но моята най-голама страст е уеб програмирането.</strong></p>\r\n<p>Занимавам се с него от десетгодишен и смятам, че знам доста неща, свързани с него. Имам над двадесет проекта, като три от тях за печелили награди от състезания.</p>\r\n<p>Когато съм на компютър, аз постоянно пиша код. Понякога се сблъсквам с неща, които не знам. Точно тогава питам Чичо Гошо<em>(Google)</em> и той винаги ми помага. По този начин аз натрупвам знания, с които сега се гордея.</p>\r\n<p>&nbsp;</p>', 'za_men', 1, 1, 1, 1365683064);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `title` varchar(550) DEFAULT NULL,
  `desc` text,
  `keywords` varchar(5500) DEFAULT NULL,
  `email` varchar(550) DEFAULT NULL,
  `facebook` varchar(550) DEFAULT NULL,
  `twitter` varchar(550) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`title`, `desc`, `keywords`, `email`, `facebook`, `twitter`) VALUES
('Yasen Georgiev - vCard', 'Визитна картичка на Ясен Георгиев в Интернет-пространството. Кой е той? С какво се занимава? Къде е учил? Всичко това тук!', 'Ясен Георгиев, Yasen Georgiev, програмист, свиленград, programmer, svilengrad', 'avbincco@gmail.com', 'yasentr', 'albertvision_bg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '#',
  `username` varchar(255) NOT NULL COMMENT 'Потр. име',
  `password` varchar(64) NOT NULL COMMENT 'Парола (хеширана SHA256)',
  `email` varchar(550) NOT NULL COMMENT 'Имейл',
  `loginKey` int(11) NOT NULL COMMENT 'Ключ за достъп',
  `lastLogin` int(11) NOT NULL COMMENT 'Последен вход',
  `registered` int(11) NOT NULL COMMENT 'Регистрация',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Потребители' AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `loginKey`, `lastLogin`, `registered`) VALUES
(1, 'albertvision', '2b956b64c9ddded1ae46b986e151877f3239a1737e820a8306315d8222465135', 'avbincco@gmail.com', 0, 1369826787, 1369826787),
(2, 'demo', 'f729712f474c231bbeb950aad90cddcf05fba8f89b6b01ec3a664afe699be74a', 'info@1000stipendii.org', 36, 1369832850, 1369832850);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
