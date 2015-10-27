--
-- Скрипт сгенерирован Devart dbForge Studio for MySQL, Версия 6.3.358.0
-- Домашняя страница продукта: http://www.devart.com/ru/dbforge/mysql/studio
-- Дата скрипта: 28.10.2015 0:11:35
-- Версия сервера: 5.5.44-0+deb8u1
-- Версия клиента: 4.1
--


--
-- Описание для базы данных chat_db
--
DROP DATABASE IF EXISTS chat_db;
CREATE DATABASE chat_db
	CHARACTER SET latin1
	COLLATE latin1_swedish_ci;

-- 
-- Отключение внешних ключей
-- 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;

-- 
-- Установить режим SQL (SQL mode)
-- 
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 
-- Установка кодировки, с использованием которой клиент будет посылать запросы на сервер
--
SET NAMES 'utf8';

-- 
-- Установка базы данных по умолчанию
--
USE chat_db;

--
-- Описание для таблицы channel
--
CREATE TABLE channel (
  id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  channelName VARCHAR(20) NOT NULL,
  description VARCHAR(30) DEFAULT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 20
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы usersgroup
--
CREATE TABLE usersgroup (
  id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(20) NOT NULL,
  PRIMARY KEY (id)
)
ENGINE = INNODB
AUTO_INCREMENT = 5
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы user
--
CREATE TABLE user (
  id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(25) NOT NULL,
  passwordHash VARCHAR(40) NOT NULL,
  email VARCHAR(50) NOT NULL,
  groupId MEDIUMINT(8) UNSIGNED DEFAULT 1,
  PRIMARY KEY (id),
  CONSTRAINT fk_user_usersgroup_groupId FOREIGN KEY (groupId)
    REFERENCES usersgroup(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 14
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы message
--
CREATE TABLE message (
  id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  senderId MEDIUMINT(8) UNSIGNED NOT NULL,
  messageText VARCHAR(200) NOT NULL,
  channelId MEDIUMINT(8) UNSIGNED NOT NULL,
  receiverId MEDIUMINT(8) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (id),
  CONSTRAINT fk_message_channel_channelId FOREIGN KEY (channelId)
    REFERENCES channel(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_message_user_senderId FOREIGN KEY (senderId)
    REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AUTO_INCREMENT = 188
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_general_ci;

--
-- Описание для таблицы userChannel
--
CREATE TABLE userChannel (
  userId MEDIUMINT(8) UNSIGNED NOT NULL,
  channelId MEDIUMINT(8) UNSIGNED NOT NULL,
  lastUserRequest DATETIME NOT NULL,
  PRIMARY KEY (userId, channelId),
  CONSTRAINT fk_userChannel_channel_channelId FOREIGN KEY (channelId)
    REFERENCES channel(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_userChannel_user_userId FOREIGN KEY (userId)
    REFERENCES user(id) ON DELETE CASCADE ON UPDATE CASCADE
)
ENGINE = INNODB
AVG_ROW_LENGTH = 8192
CHARACTER SET utf8
COLLATE utf8_general_ci;

-- 
-- Вывод данных для таблицы channel
--
INSERT INTO channel VALUES
(1, 'mainchannel', 'Main channel'),
(2, 'secondchannel', 'Secondary channel');

-- 
-- Вывод данных для таблицы usersgroup
--
INSERT INTO usersgroup VALUES
(1, 'user'),
(2, 'admin');

-- 
-- Вывод данных для таблицы user
--
INSERT INTO user VALUES
(11, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'examplemail1@gmail.com', 2),
(12, 'user1', '4a7d1ed414474e4033ac29ccb8653d9b', 'examplemail2@gmail.com', 1),
(13, 'user2', '4a7d1ed414474e4033ac29ccb8653d9b', 'examplemail3@gmail.com', 1);

-- 
-- Вывод данных для таблицы message
--

-- Таблица chat_db.message не содержит данных

-- 
-- Вывод данных для таблицы userChannel
--

-- Таблица chat_db.userChannel не содержит данных

-- 
-- Восстановить предыдущий режим SQL (SQL mode)
-- 
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;

-- 
-- Включение внешних ключей
-- 
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;