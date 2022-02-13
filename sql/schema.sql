-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.6.5-MariaDB-1:10.6.5+maria~focal - mariadb.org binary distribution
-- Операционная система:         debian-linux-gnu
-- HeidiSQL Версия:              11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Дамп структуры для таблица slave_market.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `max_work_time` int(11) DEFAULT 16,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы slave_market.category: ~13 rows (приблизительно)
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` (`id`, `name`, `parent_id`, `max_work_time`) VALUES
	(2, 'земледелие', NULL, 16),
	(3, 'скотоводство', NULL, 16),
	(4, 'работа по дому', NULL, 16),
	(5, 'уборка', 4, 16),
	(7, 'работа в каменоломне', NULL, 16),
	(8, 'Охрана', NULL, 24),
	(9, 'Мытье полов', 5, 16),
	(10, 'Мытье окон', 5, 16),
	(11, 'Для кухни', NULL, 16),
	(12, 'Приготовление пищи', 11, 16),
	(13, 'Мытье посуды', 11, 16),
	(14, 'Варить борщ', 12, 16),
	(15, 'Жарить котлетки', 12, 16);
/*!40000 ALTER TABLE `category` ENABLE KEYS */;

-- Дамп структуры для таблица slave_market.lease_contract
CREATE TABLE IF NOT EXISTS `lease_contract` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `master_id` int(11) unsigned NOT NULL,
  `slave_id` int(11) unsigned NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `hours` int(11) NOT NULL,
  `price` double(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_lease_contract_master` (`master_id`),
  KEY `FK_lease_contract_slave` (`slave_id`),
  CONSTRAINT `FK_lease_contract_master` FOREIGN KEY (`master_id`) REFERENCES `master` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_lease_contract_slave` FOREIGN KEY (`slave_id`) REFERENCES `slave` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы slave_market.lease_contract: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `lease_contract` DISABLE KEYS */;
/*!40000 ALTER TABLE `lease_contract` ENABLE KEYS */;

-- Дамп структуры для таблица slave_market.master
CREATE TABLE IF NOT EXISTS `master` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `gold` double(10,2) NOT NULL DEFAULT 0.00,
  `vip` enum('1','2','3') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы slave_market.master: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `master` DISABLE KEYS */;
INSERT INTO `master` (`id`, `name`, `gold`, `vip`) VALUES
	(1, 'Господин Боб', 100000.00, NULL),
	(2, 'Уродливый Фред', 60000.00, NULL),
	(3, 'Сэр Вонючка', 100000.00, '1');
/*!40000 ALTER TABLE `master` ENABLE KEYS */;

-- Дамп структуры для таблица slave_market.slave
CREATE TABLE IF NOT EXISTS `slave` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `sex` enum('male','female') NOT NULL,
  `dob` date NOT NULL,
  `weight` double(4,1) NOT NULL,
  `skin_color` enum('white','yellow','black','green') NOT NULL,
  `grown_place` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `price_per_hour` double(10,2) NOT NULL DEFAULT 0.00,
  `price` double(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `weight` (`weight`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы slave_market.slave: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `slave` DISABLE KEYS */;
INSERT INTO `slave` (`id`, `name`, `sex`, `dob`, `weight`, `skin_color`, `grown_place`, `description`, `price_per_hour`, `price`) VALUES
	(1, 'Майкл', 'male', '1990-02-10', 99.0, 'white', 'Неизвестно', 'Может все', 80.00, 100000.00),
	(2, 'Вильям', 'male', '1988-10-02', 81.0, 'black', 'Леса', 'Любит играть с собакой', 10.00, 60000.00),
	(3, 'Джейкоб', 'male', '1985-02-10', 68.0, 'green', 'Неизвестно', 'Тест', 18.00, 77500.00),
	(4, 'Алекс', 'male', '1980-07-20', 70.0, 'white', 'Москва', 'Охранник из пяторочки', 10.00, 50000.00),
	(5, 'Ариэль', 'female', '1977-10-01', 60.0, 'white', 'Неизвестно', 'Домработница', 15.00, 70000.00),
	(6, 'Джамиля', 'female', '1991-11-12', 55.0, 'white', 'Неизвестно', 'Любть готовить вкусную еду', 12.00, 55000.00),
	(7, 'slave7', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(8, 'slave8', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(9, 'slave9', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(10, 'slave10', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(11, 'slave11', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(12, 'slave12', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(13, 'slave13', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(14, 'slave14', 'male', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(15, 'slave15', 'female', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00),
	(16, 'slave16', 'female', '1980-01-01', 59.0, 'green', '-', 'Охраник', 20.00, 62500.00);
/*!40000 ALTER TABLE `slave` ENABLE KEYS */;

-- Дамп структуры для таблица slave_market.slave_category
CREATE TABLE IF NOT EXISTS `slave_category` (
  `slave_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL,
  KEY `FK_slave_category_category` (`category_id`),
  KEY `FK_slave_category_slave` (`slave_id`),
  CONSTRAINT `FK_slave_category_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `FK_slave_category_slave` FOREIGN KEY (`slave_id`) REFERENCES `slave` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Дамп данных таблицы slave_market.slave_category: ~17 rows (приблизительно)
/*!40000 ALTER TABLE `slave_category` DISABLE KEYS */;
INSERT INTO `slave_category` (`slave_id`, `category_id`) VALUES
	(1, 2),
	(1, 15),
	(1, 7),
	(1, 3),
	(2, 3),
	(2, 2),
	(3, 2),
	(3, 7),
	(3, 5),
	(4, 8),
	(5, 10),
	(5, 9),
	(5, 14),
	(5, 15),
	(5, 13),
	(6, 14),
	(6, 15),
	(7, 8),
	(8, 8),
	(9, 8),
	(10, 8),
	(11, 8),
	(12, 8),
	(13, 8),
	(14, 8),
	(15, 8),
	(16, 8);
/*!40000 ALTER TABLE `slave_category` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
