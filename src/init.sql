CREATE DATABASE IF NOT EXISTS tododb;

USE tododb;

CREATE TABLE IF NOT EXISTS tododb.Category (
    id int(11) NOT NULL AUTO_INCREMENT, 
    title varchar(255) DEFAULT NULL, 
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    complete_until DATETIME DEFAULT NULL,
    UNIQUE (title),
    PRIMARY KEY (id),
    CHECK ((title IS NULL) OR (CHAR_LENGTH(title) >= 4)));

CREATE TABLE IF NOT EXISTS tododb.Todo (
    id int(11) NOT NULL AUTO_INCREMENT, 
    title varchar(255) DEFAULT NULL, 
    description varchar(255), 
    completed tinyint(1) NOT NULL DEFAULT 0, 
    date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    cid int(11) NOT NULL,
    CONSTRAINT fk_category FOREIGN KEY (cid) REFERENCES Category(id) ON DELETE CASCADE,
    CONSTRAINT unique_title_category UNIQUE(title,cid),
    PRIMARY KEY (id),
    CHECK ((title IS NULL) OR (CHAR_LENGTH(title) >= 4)));