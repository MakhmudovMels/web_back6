# web_back6
Learning Backend at university
## SQL-запросы для создания таблиц
- CREATE TABLE human (
id int(10) unsigned NOT NULL AUTO_INCREMENT,
name VARCHAR(32) NOT NULL DEFAULT '',
email VARCHAR(32) NOT NULL UNIQUE,
year VARCHAR(16) NOT NULL,
gender VARCHAR(16) NOT NULL,
limbs VARCHAR(16) NOT NULL DEFAULT 4,
bio TEXT NOT NULL DEFAULT '',
PRIMARY KEY (id)
);
- CREATE TABLE superability (
human_id int(10) unsigned NOT NULL,
name_of_superability VARCHAR(32) NOT NULL,
PRIMARY KEY (human_id, name_of_superability),
FOREIGN KEY (human_id)  REFERENCES human (id)
);
- CREATE TABLE login_pass (
human_id int(10) unsigned NOT NULL,
login VARCHAR(32) NOT NULL,
pass VARCHAR(32) NOT NULL,
PRIMARY KEY (human_id),
FOREIGN KEY (human_id)  REFERENCES human (id)
);
- CREATE TABLE admin (
id int(10) unsigned NOT NULL AUTO_INCREMENT,
login varchar(32) NOT NULL,
pass VARCHAR(64) NOT NULL,
PRIMARY KEY (id)
);