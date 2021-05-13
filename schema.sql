CREATE DATABASE giftube
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE giftube;

CREATE TABLE categories (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  nameCat          CHAR(128) NOT NULL,
  urlCat VARCHAR(255) NOT NULL,
);

CREATE TABLE gifs (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  dt_add        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  category_id   INT NOT NULL,
  user_id       INT NOT NULL,
  title         CHAR(128) NOT NULL,
  description   TEXT NOT NULL,
  img_path      CHAR(128),
  likes_count   INT,
  favs_count    INT,
  views_count   INT,
  votes int(11) NOT NULL default 0,
  points int(11) NOT NULL default 0,
  url VARCHAR(255) NOT NULL,
  avg_points int(11) NOT NULL default 0
);

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `date` int(10) unsigned NOT NULL,
  `obj_id` int(10) unsigned NOT NULL,
  `ip` int(10) unsigned NOT NULL,
  `rating` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE FULLTEXT INDEX gif_search ON gifs(title, description);

CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  dt_add        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  name          CHAR(128) NOT NULL,
  email         CHAR(128) NOT NULL,
  password      CHAR(64) NOT NULL,
  avatar_path   CHAR(128),
  status    int NOT NULL default 2,
  secretkey     CHAR(128),
  cookie_token  CHAR(128)
);

CREATE TABLE all_visits (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  date        INT(10),
  ip          INT(32) NOT NULL
);
CREATE TABLE black_list_ip (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  date        INT(10),
  ip          INT(32) NOT NULL
);

CREATE UNIQUE INDEX email ON users(email);
CREATE UNIQUE INDEX name ON users(name);

CREATE TABLE gifs_like (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NOT NULL,
  gif_id        INT NOT NULL
);

CREATE TABLE gifs_fav (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  user_id       INT NOT NULL,
  gif_id        INT NOT NULL
);

CREATE TABLE comments (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  dt_add        TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  user_id       INT NOT NULL,
  gif_id        INT NOT NULL,
  moderation    int NOT NULL default 0,
  comment_text  TEXT NOT NULL
);

CREATE TABLE session ( 
  id_session tinytext NOT NULL, 
  putdate TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP, 
  user tinytext NOT NULL 
);

CREATE TABLE ips ( 
  ip_id        INT AUTO_INCREMENT PRIMARY KEY,
  ip_address   CHAR(50) NOT NULL 
);

CREATE TABLE visits ( 
  visit_id        INT AUTO_INCREMENT PRIMARY KEY,
  date date NOT NULL,
  hosts  CHAR(12) NOT NULL,
  views  CHAR(12) NOT NULL
);
